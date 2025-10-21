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
                            <i class="fas fa-plus-circle text-primary"></i> 
                            Tạo Tunnel Mới
                        </h2>
                        <p class="text-muted mb-0">Tạo một tunnel domain mới để nhận webhook</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="row justify-content-center">
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

                        <form method="post" action="<?= base_url('tunel/store') ?>">
                            <?= csrf_field() ?>
                            
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
                                           placeholder="myapp, webhook1, api-endpoint..."
                                           required>
                                    <div class="form-text">
                                        Tên định danh duy nhất cho tunnel (sẽ dùng trong URL webhook)
                                    </div>
                                </div>

                                <!-- Method -->
                                <div class="col-md-6 mb-3">
                                    <label for="method" class="form-label">
                                        <i class="fas fa-exchange-alt text-primary"></i> HTTP Method
                                    </label>
                                    <select class="form-select" id="method" name="method">
                                        <option value="POST" selected>POST</option>
                                        <option value="GET">GET</option>
                                        <option value="PUT">PUT</option>
                                        <option value="DELETE">DELETE</option>
                                        <option value="PATCH">PATCH</option>
                                    </select>
                                    <div class="form-text">
                                        HTTP method mà tunnel sẽ chấp nhận
                                    </div>
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
                                       placeholder="http://localhost:3000/webhook, https://myapp.com/api/webhook..."
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
                                          maxlength="500"
                                          placeholder="Mô tả ngắn về mục đích sử dụng tunnel này..."></textarea>
                                <div class="form-text">
                                    Mô tả ngắn về tunnel (tối đa 500 ký tự)
                                </div>
                                <div class="text-muted text-end">
                                    <span id="char-count">0</span>/500
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="mb-4">
                                <label for="status" class="form-label">
                                    <i class="fas fa-toggle-on text-primary"></i> Trạng thái
                                </label>
                                <select class="form-select" id="status" name="status">
                                    <option value="active" selected>Active - Hoạt động</option>
                                    <option value="inactive">Inactive - Tạm dừng</option>
                                    <option value="maintenance">Maintenance - Bảo trì</option>
                                </select>
                                <div class="form-text">
                                    Trạng thái hoạt động của tunnel
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?= base_url('tunel') ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Hủy
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Tạo Tunnel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card mt-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-lightbulb"></i> Hướng dẫn sử dụng
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Webhook URL sẽ là:</strong></p>
                        <code id="webhook-url-preview" class="bg-light p-2 rounded d-block mb-3">
                            https://your-ngrok-domain.ngrok-free.app/gateway/[TUNNEL_KEY]
                        </code>
                        
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Lưu ý:</h6>
                            <ul class="mb-0">
                                <li>Thay thế <code>[TUNNEL_KEY]</code> bằng key bạn vừa nhập</li>
                                <li>URL này sẽ được sử dụng để nhận webhook từ các service bên ngoài</li>
                                <li>Dữ liệu sẽ được tự động chuyển tiếp đến Target URL</li>
                            </ul>
                        </div>
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

// Preview webhook URL
function updateWebhookPreview() {
    const key = document.getElementById('key').value;
    const preview = document.getElementById('webhook-url-preview');
    
    if (key) {
        preview.textContent = `https://your-ngrok-domain.ngrok-free.app/gateway/${key}`;
    } else {
        preview.textContent = 'https://your-ngrok-domain.ngrok-free.app/gateway/[TUNNEL_KEY]';
    }
}

document.getElementById('key').addEventListener('input', updateWebhookPreview);

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const key = document.getElementById('key').value.trim();
    const url = document.getElementById('url').value.trim();
    
    if (!key || !url) {
        e.preventDefault();
        alert('Vui lòng điền đầy đủ thông tin bắt buộc.');
        return;
    }
    
    // Check key format (alphanumeric and hyphens only)
    if (!/^[a-zA-Z0-9-_]+$/.test(key)) {
        e.preventDefault();
        alert('Tunnel Key chỉ được chứa chữ cái, số, dấu gạch ngang và gạch dưới.');
        return;
    }
});
</script>

<style>
.form-label {
    font-weight: 600;
    color: #495057;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card.shadow {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

#char-count {
    font-size: 0.875rem;
}

code {
    color: #d63384;
    font-size: 0.875rem;
}
</style>