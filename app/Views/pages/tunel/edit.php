<div class="content">
    <div class="container mt-5">
        <h2>Sửa Tunnel</h2>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('tunel/update/' . $domain['id']) ?>" method="post">
            <div class="form-group mb-2">
                <label for="id">ID (Không thay đổi)</label>
                <input type="text" id="id" class="form-control" value="<?= esc($domain['id']) ?>" disabled>
            </div>

            <div class="form-group mb-2">
                <label for="key">Key</label>
                <input type="text" name="key" id="key" class="form-control" value="<?= esc($domain['key']) ?>" required>
            </div>

            <div class="form-group mb-2">
                <label for="url">URL</label>
                <input type="url" name="url" id="url" class="form-control" value="<?= esc($domain['url']) ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="<?= base_url('tunel') ?>" class="btn btn-secondary">Huỷ</a>
        </form>
    </div>


    <hr>
    <h4 class="mt-5">Lịch sử truy cập (có phân trang)</h4>

    <div class="table-responsive">
        <table class="table table-bordered table-hover mt-3" style="table-layout: fixed;">
            <thead>
                <tr style="font-size:12px;">
                    <th style="width: 5%;">ID</th>
                    <th style="width: 75%;">Data</th>
                    <th style="width: 10%;">Thông tin</th>
                    <th style="width: 10%;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($hooks)): ?>
                    <tr>
                        <td colspan="6" class="text-center">Không có dữ liệu.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($hooks as $hook): ?>
                        <tr>
                            <td><?= esc($hook['id']) ?></td>
                            <td>
                                <div style="max-height: 200px; overflow: auto; word-break: break-word;font-size:10px;">
                                    HEADER: <?= esc($hook['headers']) ?>
                                </div>
                                <hr>
                                <div style="max-height: 200px; overflow: auto; word-break: break-word;font-size:10px;">
                                    BODY: <?= esc($hook['data']) ?>
                                </div>
                                <hr>
                                <div style="max-height: 200px; overflow: auto; word-break: break-word;font-size:10px;">
                                    RESPONSE: <?= esc($hook['response_body']) ?>
                                </div>
                            </td>
                            <td style="font-size:10px;">
                                <span>STATUS: <?= esc($hook['status_code']) ?></span>
                                <br>
                                <span>TIME: <?= esc($hook['created_at']) ?></span>
                            </td>
                            <td>
                                <form action="<?= base_url('hook/reload/' . $hook['id']) ?>" method="get" style="display: inline;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-warning" title="Gửi lại webhook">
                                        🔄
                                    </button>
                                </form>

                                <form action="<?= base_url('hook/delete/' . $hook['id']) ?>" method="get" style="display: inline;" onsubmit="return confirm('Bạn chắc chắn muốn xoá?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger" title="Xoá bản ghi">
                                        🗑️
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        <?= $pager->links('hooks', 'default_full') ?>
    </div>

</div>
