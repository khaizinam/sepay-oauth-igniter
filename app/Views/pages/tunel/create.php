<div class="content">
    <div class="container mt-5">
        <h2 class="mb-4">Tạo Tunnel Mới</h2>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form method="post" action="/tunel/store">
            <?= csrf_field() ?>
            <div class="form-group mb-3">
                <label for="key">Key bí mật</label>
                <input type="text" class="form-control" id="key" name="key" required>
            </div>

            <div class="form-group mb-3">
                <label for="url">URL đích</label>
                <input type="text" class="form-control" id="url" name="url" required>
            </div>

            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="/tunel" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>