const express = require('express');
const http = require('http');
const socketIO = require('socket.io');
const axios = require('axios');

const CONFIG = [
  {
    "event": "send_message",
    "response": "receive_message",
    "is_restful": true,
    "url": "api/send_message",
    "method": "POST"
  },
  {
    "event": "notify_user",
    "response": null,
    "is_restful": false
  }
];

const app = express();
const server = http.createServer(app);
const io = socketIO(server, {
  cors: {
    origin: '*',
  },
});

const userSockets = {};

const eventMap = {};
CONFIG.forEach(e => eventMap[e.event] = e);

io.on('connection', (socket) => {
  const userId = socket.handshake.query.user_id || 'unknown';
  console.log(`🟢 user_id ${userId} connected with socket.id ${socket.id}`);

  if (!userSockets[userId]) userSockets[userId] = [];
  userSockets[userId].push(socket.id);

  socket.on('event', async (payload) => {
    const { event, data, to_user_id } = payload;
    const token = data?.token || '';
    const headers = {
        Authorization: `Bearer ${token}`
    };
    const config = eventMap[event];
    if (!config) {
      console.warn(`❌ Event '${event}' không được định nghĩa`);
      return;
    }

    // Nếu là BE gửi (user_id = 0) và is_restful = false => gửi thẳng đến client
    if (userId == 0 && !config.is_restful && to_user_id) {
      const targets = userSockets[to_user_id] || [];
      targets.forEach(id => io.to(id).emit(event, data));
      console.log(`📤 BE gửi event '${event}' tới user_id ${to_user_id}`);
      return;
    }

    // Nếu là client gửi (user_id > 0) và is_restful = true => gọi API
    if (userId != 0 && config.is_restful) {
      try {
        const fullUrl = `http://localhost/${config.url}`;
        let response;
        if (config.method.toUpperCase() === 'POST') {
          response = await axios.post(fullUrl, {
            user_id: userId,
            data: data
          }, { headers });
        } else {
          response = await axios.get(fullUrl, {
            headers,
            params: {
              user_id: userId,
              ...data
            }
          });
        }

        if (config.response && response?.data) {
          socket.emit(config.response, response.data);
        }

        console.log(`✅ Gọi API ${config.method} ${config.url} thành công từ user_id ${userId}`);
      } catch (err) {
        console.error(`❌ Lỗi gọi API ${config.url}: ${err.message}`);
        socket.emit(`rest_error`, { message: err.message });
      }
    }
  });

  socket.on('disconnect', () => {
    console.log(`🔌 user_id ${userId} disconnected`);
    userSockets[userId] = userSockets[userId]?.filter(id => id !== socket.id);
    if (userSockets[userId]?.length === 0) delete userSockets[userId];
  });
});

const PORT = 9000;
server.listen(PORT, () => {
  console.log(`🚀 WebSocket server running on http://localhost:${PORT}`);
});
