<div class="content">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Danh sách các Tunnel</h2>
            <a href="<?= base_url('tunel/create') ?>" class="btn btn-primary">+ Tạo Tunnel</a>
        </div>

        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Key</th>
                    <th>URL</th>
                    <th>Thời gian tạo</th>
                    <th>Thời gian cập nhật</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($domains)): ?>
                    <?php foreach ($domains as $index => $domain): ?>
                        <tr>
                            <td><?= esc($domain['id']) ?></td>
                            <td><?= esc($domain['key']) ?></td>
                            <td><a href="<?= esc($domain['url']) ?>" target="_blank"><?= esc($domain['url']) ?></a></td>
                            <td><?= esc($domain['created_at']) ?></td>
                            <td><?= esc($domain['updated_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center">Không có tunnel nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
