<div class="content">
    <div class="container-fluid mt-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center">
                    <a href="<?= base_url('tunel') ?>" class="btn btn-outline-secondary me-3">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h2 class="mb-1">
                            <i class="fas fa-edit text-primary"></i> 
                            Chỉnh sửa Tunnel
                        </h2>
                        <p class="text-muted mb-0">Cập nhật thông tin tunnel #<?= esc($domain['id']) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Edit Form -->
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-network-wired"></i> Thông tin Tunnel
                        </h5>
                    </div>
                    <div class="card-body">
        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible">
                                <i class="fas fa-exclamation-circle"></i>
                                <?= session()->getFlashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
        <?php endif; ?>

        <form action="<?= base_url('tunel/update/' . $domain['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            
                            <!-- ID Display -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-hashtag text-muted"></i> Tunnel ID
                                </label>
                                <input type="text" class="form-control" value="#<?= esc($domain['id']) ?>" disabled>
                                <div class="form-text">ID không thể thay đổi</div>
                            </div>

                            <div class="row">
                                <!-- Key -->
                                <div class="col-md-6 mb-3">
                                    <label for="key" class="form-label">
                                        <i class="fas fa-key text-primary"></i> Tunnel Key *
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="key" 
                                           name="key" 
                                           value="<?= esc($domain['key']) ?>" 
                                           required>
                                    <div class="form-text">
                                        Tên định danh duy nhất cho tunnel
                                    </div>
                                </div>

                                <!-- Method -->
                                <div class="col-md-6 mb-3">
                                    <label for="method" class="form-label">
                                        <i class="fas fa-exchange-alt text-primary"></i> HTTP Method
                                    </label>
                                    <select class="form-select" id="method" name="method">
                                        <option value="GET" <?= ($domain['method'] ?? 'POST') === 'GET' ? 'selected' : '' ?>>GET</option>
                                        <option value="POST" <?= ($domain['method'] ?? 'POST') === 'POST' ? 'selected' : '' ?>>POST</option>
                                        <option value="PUT" <?= ($domain['method'] ?? 'POST') === 'PUT' ? 'selected' : '' ?>>PUT</option>
                                        <option value="DELETE" <?= ($domain['method'] ?? 'POST') === 'DELETE' ? 'selected' : '' ?>>DELETE</option>
                                        <option value="PATCH" <?= ($domain['method'] ?? 'POST') === 'PATCH' ? 'selected' : '' ?>>PATCH</option>
                                    </select>
                                </div>
                            </div>

                            <!-- URL -->
                            <div class="mb-3">
                                <label for="url" class="form-label">
                                    <i class="fas fa-link text-primary"></i> Target URL *
                                </label>
                                <input type="url" 
                                       class="form-control" 
                                       id="url" 
                                       name="url" 
                                       value="<?= esc($domain['url']) ?>" 
                                       required>
                                <div class="form-text">
                                    URL đích mà tunnel sẽ chuyển tiếp dữ liệu đến
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-info-circle text-primary"></i> Mô tả
                                </label>
                                <textarea class="form-control" 
                                          id="description" 
                                          name="description" 
                                          rows="3" 
                                          maxlength="500"><?= esc($domain['description'] ?? '') ?></textarea>
                                <div class="form-text">
                                    Mô tả ngắn về tunnel (tối đa 500 ký tự)
                                </div>
                                <div class="text-muted text-end">
                                    <span id="char-count"><?= strlen($domain['description'] ?? '') ?></span>/500
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="mb-4">
                                <label for="status" class="form-label">
                                    <i class="fas fa-toggle-on text-primary"></i> Trạng thái
                                </label>
                                <select class="form-select" id="status" name="status">
                                    <option value="active" <?= ($domain['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active - Hoạt động</option>
                                    <option value="inactive" <?= ($domain['status'] ?? 'active') === 'inactive' ? 'selected' : '' ?>>Inactive - Tạm dừng</option>
                                    <option value="maintenance" <?= ($domain['status'] ?? 'active') === 'maintenance' ? 'selected' : '' ?>>Maintenance - Bảo trì</option>
                                </select>
                            </div>

                            <!-- Actions -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?= base_url('tunel') ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Hủy
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Cập nhật Tunnel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Sidebar -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-info-circle"></i> Thông tin hiện tại
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Webhook URL:</strong>
                            <code class="bg-light p-2 rounded d-block mt-1">
                                <?= base_url('gateway/' . $domain['key']) ?>
                            </code>
            </div>

                        <div class="mb-3">
                            <strong>Trạng thái hiện tại:</strong>
                            <?php
                            $status = $domain['status'] ?? 'active';
                            $statusColors = [
                                'active' => 'success',
                                'inactive' => 'danger',
                                'maintenance' => 'warning'
                            ];
                            $statusColor = $statusColors[$status] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $statusColor ?> ms-2">
                                <?= ucfirst($status) ?>
                            </span>
                        </div>

                        <div class="mb-3">
                            <strong>Method:</strong>
                            <?php
                            $method = $domain['method'] ?? 'POST';
                            $methodColors = [
                                'GET' => 'success',
                                'POST' => 'primary', 
                                'PUT' => 'warning',
                                'DELETE' => 'danger',
                                'PATCH' => 'info'
                            ];
                            $methodColor = $methodColors[$method] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $methodColor ?> ms-2"><?= $method ?></span>
                        </div>

                        <div class="mb-3">
                            <strong>Tạo lúc:</strong>
                            <div class="text-muted">
                                <?= date('d/m/Y H:i:s', strtotime($domain['created_at'])) ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <strong>Cập nhật lần cuối:</strong>
                            <div class="text-muted">
                                <?= date('d/m/Y H:i:s', strtotime($domain['updated_at'])) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card mt-3">
                    <div class="card-header bg-success text-white">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-bolt"></i> Thao tác nhanh
                        </h6>
                    </div>
                    <div class="card-body">
                        <button type="button" 
                                class="btn btn-outline-primary btn-sm w-100 mb-2"
                                onclick="copyToClipboard('<?= base_url('gateway/' . $domain['key']) ?>')">
                            <i class="fas fa-copy"></i> Copy Webhook URL
                        </button>
                        
                        <a href="<?= base_url('gateway/' . $domain['key']) ?>" 
                           target="_blank" 
                           class="btn btn-outline-info btn-sm w-100 mb-2">
                            <i class="fas fa-external-link-alt"></i> Test Webhook
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


        <!-- Webhook History -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-history"></i> Lịch sử Webhooks
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($hooks)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Chưa có webhook nào</h5>
                                <p class="text-muted">Lịch sử webhook sẽ hiển thị ở đây khi có request đến tunnel này.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th><i class="fas fa-hashtag text-muted"></i> ID</th>
                                            <th><i class="fas fa-clock text-muted"></i> Thời gian</th>
                                            <th><i class="fas fa-signal text-muted"></i> Status</th>
                                            <th><i class="fas fa-server text-muted"></i> Headers</th>
                                            <th><i class="fas fa-envelope text-muted"></i> Data</th>
                                            <th><i class="fas fa-reply text-muted"></i> Response</th>
                                            <th><i class="fas fa-cogs text-muted"></i> Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($hooks as $hook): ?>
                                            <?php
                                            $statusCode = $hook['status_code'] ?? 0;
                                            $statusColor = 'secondary';
                                            if ($statusCode >= 200 && $statusCode < 300) $statusColor = 'success';
                                            elseif ($statusCode >= 300 && $statusCode < 400) $statusColor = 'info';
                                            elseif ($statusCode >= 400 && $statusCode < 500) $statusColor = 'warning';
                                            elseif ($statusCode >= 500) $statusColor = 'danger';
                                            ?>
                                            <tr>
                                                <td class="align-middle">
                                                    <span class="badge bg-secondary">#<?= esc($hook['id']) ?></span>
                                                </td>
                                                <td class="align-middle">
                                                    <small class="text-muted">
                                                        <?= date('d/m/Y', strtotime($hook['created_at'])) ?><br>
                                                        <?= date('H:i:s', strtotime($hook['created_at'])) ?>
                                                    </small>
                                                </td>
                                                <td class="align-middle">
                                                    <span class="badge bg-<?= $statusColor ?>">
                                                        <?= $statusCode ?: 'N/A' ?>
                                                    </span>
                                                </td>
                                                <td class="align-middle">
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-info" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#headersModal<?= $hook['id'] ?>">
                                                        <i class="fas fa-eye"></i> Xem
                                                    </button>
                                                </td>
                                                <td class="align-middle">
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-primary" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#dataModal<?= $hook['id'] ?>">
                                                        <i class="fas fa-eye"></i> Xem
                                                    </button>
                                                </td>
                                                <td class="align-middle">
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-success" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#responseModal<?= $hook['id'] ?>">
                                                        <i class="fas fa-eye"></i> Xem
                                                    </button>
                                                </td>
                                                <td class="align-middle">
                                                    <div class="btn-group btn-group-sm">
                                                        <form action="<?= base_url('hook/reload/' . $hook['id']) ?>" method="get" style="display: inline;">
                                                            <?= csrf_field() ?>
                                                            <button type="submit" 
                                                                    class="btn btn-outline-warning" 
                                                                    title="Gửi lại webhook">
                                                                <i class="fas fa-redo"></i>
                                                            </button>
                                                        </form>
                                                        <form action="<?= base_url('hook/delete/' . $hook['id']) ?>" 
                                                              method="get" 
                                                              style="display: inline;" 
                                                              onsubmit="return confirm('Bạn chắc chắn muốn xóa webhook này?')">
                                                            <?= csrf_field() ?>
                                                            <button type="submit" 
                                                                    class="btn btn-outline-danger" 
                                                                    title="Xóa bản ghi">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Headers Modal -->
                                            <div class="modal fade" id="headersModal<?= $hook['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Headers - Hook #<?= $hook['id'] ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <pre class="bg-light p-3 rounded"><code><?= esc($hook['headers']) ?></code></pre>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Data Modal -->
                                            <div class="modal fade" id="dataModal<?= $hook['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Request Data - Hook #<?= $hook['id'] ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <pre class="bg-light p-3 rounded"><code><?= esc($hook['data']) ?></code></pre>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Response Modal -->
                                            <div class="modal fade" id="responseModal<?= $hook['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Response - Hook #<?= $hook['id'] ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <pre class="bg-light p-3 rounded"><code><?= esc($hook['response_body']) ?></code></pre>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                <?= $pager->links('hooks', 'default_full') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Character counter for description
document.getElementById('description').addEventListener('input', function() {
    const count = this.value.length;
    document.getElementById('char-count').textContent = count;
    
    if (count > 500) {
        this.value = this.value.substring(0, 500);
        document.getElementById('char-count').textContent = '500';
    }
});

// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.innerHTML = `
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> Đã copy webhook URL vào clipboard!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 3000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        alert('Không thể copy URL. Vui lòng copy thủ công: ' + text);
    });
}
</script>

<style>
.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card.shadow {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

code {
    color: #d63384;
    font-size: 0.875rem;
}

pre {
    max-height: 400px;
    overflow-y: auto;
}

.btn-group .btn {
    margin: 0 1px;
}
</style>
