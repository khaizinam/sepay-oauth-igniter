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
                    <th>Thời gian cập nhật</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($domains)): ?>
                    <?php foreach ($domains as $domain): ?>
                        <tr>
                            <td><?= esc($domain['id']) ?></td>
                            <td><?= esc($domain['key']) ?></td>
                            <td><a href="<?= esc($domain['url']) ?>" target="_blank"><?= esc($domain['url']) ?></a></td>
                            <td><?= esc($domain['updated_at']) ?></td>
                            <td>
                                <a href="<?= base_url('tunel/edit/' . $domain['id']) ?>" class="btn btn-sm btn-warning">Sửa</a>
                                <form action="<?= base_url('tunel/delete/' . $domain['id']) ?>" method="post" style="display:inline;" onsubmit="return confirm('Bạn chắc chắn muốn xoá?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger">Xoá</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center">Không có tunnel nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            <?= $pager->links() ?>
        </div>

        <hr class="my-5">
        <h4>Gần đây</h4>
        <table class="table table-striped table-sm mt-3">
            <thead>
                <tr style="font-size:12px;">
                    <th style="width: 12%;">Domain ID</th>
                    <th style="width: 75%;">Data</th>
                    <th style="width: 13%;">Thông tin</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($latestHooks)): ?>
                    <?php foreach ($latestHooks as $hook): ?>
                         <tr>
                            <td><?= esc($hook['domain_id']) ?></td>
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
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center">Không có domain hook gần đây.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
