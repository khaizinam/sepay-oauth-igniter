<div class="content">
    <div class="container mt-5">
        <h2>Sửa Tunnel</h2>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('tunel/update/' . $domain['id']) ?>" method="post">
            <?= csrf_field() ?>

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
</div>
