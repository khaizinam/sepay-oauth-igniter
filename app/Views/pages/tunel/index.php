<div class="content">
    <div class="container-fluid mt-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">
                            <i class="fas fa-network-wired text-primary"></i> 
                            Tunnel Management
                        </h2>
                        <p class="text-muted mb-0">Quản lý các tunnel domains và webhook endpoints</p>
                    </div>
                    <a href="<?= base_url('tunel/create') ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus"></i> Tạo Tunnel Mới
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?= count($domains) ?></h4>
                                <p class="mb-0">Total Tunnels</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-network-wired fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?= count(array_filter($domains, fn($d) => ($d['status'] ?? 'active') === 'active')) ?></h4>
                                <p class="mb-0">Active</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?= count(array_filter($domains, fn($d) => ($d['status'] ?? 'active') === 'inactive')) ?></h4>
                                <p class="mb-0">Inactive</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-pause-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?= count($latestHooks) ?></h4>
                                <p class="mb-0">Recent Hooks</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-bolt fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Table -->
        <div class="card shadow">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list"></i> Danh sách Tunnels
                </h5>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($domains)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">
                                        <i class="fas fa-hashtag text-muted"></i> ID
                                    </th>
                                    <th class="border-0">
                                        <i class="fas fa-key text-muted"></i> Key
                                    </th>
                                    <th class="border-0">
                                        <i class="fas fa-link text-muted"></i> URL
                                    </th>
                                    <th class="border-0">
                                        <i class="fas fa-info-circle text-muted"></i> Mô tả
                                    </th>
                                    <th class="border-0">
                                        <i class="fas fa-exchange-alt text-muted"></i> Method
                                    </th>
                                    <th class="border-0">
                                        <i class="fas fa-toggle-on text-muted"></i> Status
                                    </th>
                                    <th class="border-0">
                                        <i class="fas fa-clock text-muted"></i> Cập nhật
                                    </th>
                                    <th class="border-0 text-center">
                                        <i class="fas fa-cogs text-muted"></i> Thao tác
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($domains as $domain): ?>
                                    <?php 
                                    $status = $domain['status'] ?? 'active';
                                    $method = $domain['method'] ?? 'POST';
                                    $description = $domain['description'] ?? '';
                                    ?>
                                    <tr>
                                        <td class="align-middle">
                                            <span class="badge bg-secondary">#<?= esc($domain['id']) ?></span>
                                        </td>
                                        <td class="align-middle">
                                            <code class="bg-light p-2 rounded"><?= esc($domain['key']) ?></code>
                                        </td>
                                        <td class="align-middle">
                                            <a href="<?= esc($domain['url']) ?>" target="_blank" class="text-decoration-none">
                                                <?= esc(strlen($domain['url']) > 40 ? substr($domain['url'], 0, 40) . '...' : $domain['url']) ?>
                                                <i class="fas fa-external-link-alt text-muted ms-1"></i>
                                            </a>
                                        </td>
                                        <td class="align-middle">
                                            <?php if ($description): ?>
                                                <span title="<?= esc($description) ?>">
                                                    <?= esc(strlen($description) > 30 ? substr($description, 0, 30) . '...' : $description) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-middle">
                                            <?php
                                            $methodColors = [
                                                'GET' => 'success',
                                                'POST' => 'primary', 
                                                'PUT' => 'warning',
                                                'DELETE' => 'danger',
                                                'PATCH' => 'info'
                                            ];
                                            $color = $methodColors[$method] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $color ?>"><?= $method ?></span>
                                        </td>
                                        <td class="align-middle">
                                            <?php
                                            $statusColors = [
                                                'active' => 'success',
                                                'inactive' => 'danger',
                                                'maintenance' => 'warning'
                                            ];
                                            $statusColor = $statusColors[$status] ?? 'secondary';
                                            $statusIcons = [
                                                'active' => 'check-circle',
                                                'inactive' => 'times-circle', 
                                                'maintenance' => 'wrench'
                                            ];
                                            $statusIcon = $statusIcons[$status] ?? 'question-circle';
                                            ?>
                                            <span class="badge bg-<?= $statusColor ?>">
                                                <i class="fas fa-<?= $statusIcon ?>"></i> <?= ucfirst($status) ?>
                                            </span>
                                        </td>
                                        <td class="align-middle text-muted">
                                            <small><?= date('d/m/Y H:i', strtotime($domain['updated_at'])) ?></small>
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="btn-group" role="group">
                                                <a href="<?= base_url('tunel/edit/' . $domain['id']) ?>" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="Chỉnh sửa tunnel">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-info" 
                                                        onclick="copyToClipboard('<?= base_url('gateway/' . $domain['key']) ?>')"
                                                        title="Copy webhook URL">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                                <form action="<?= base_url('tunel/delete/' . $domain['id']) ?>" 
                                                      method="post" 
                                                      style="display:inline;" 
                                                      onsubmit="return confirm('Bạn chắc chắn muốn xóa tunnel này?')">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            title="Xóa tunnel">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-network-wired fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Chưa có tunnel nào</h5>
                        <p class="text-muted">Tạo tunnel đầu tiên để bắt đầu sử dụng hệ thống.</p>
                        <a href="<?= base_url('tunel/create') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tạo Tunnel Đầu Tiên
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            <?= $pager->links() ?>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.innerHTML = `
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> Đã copy webhook URL vào clipboard!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        document.body.appendChild(toast);
        
        // Auto remove after 3 seconds
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

.table th {
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge {
    font-size: 0.75rem;
    padding: 0.4em 0.6em;
}

code {
    font-size: 0.85rem;
    color: #d63384;
}

.btn-group .btn {
    margin: 0 1px;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card.shadow {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.recent-hooks-card {
    margin-top: 2rem;
}

.hook-item {
    border-left: 4px solid #007bff;
    padding: 1rem;
    margin-bottom: 0.5rem;
    background: #f8f9fa;
    border-radius: 0.25rem;
}

.hook-status-success {
    border-left-color: #28a745;
}

.hook-status-error {
    border-left-color: #dc3545;
}
</style>
