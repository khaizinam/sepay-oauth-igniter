<div class="content">
    <div class="container py-4">
        <form action="<?= base_url('settings/se-pay/update') ?>" method="post">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="mb-3">
                <label for="Name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" placeholder="Tích hợp SePay" name="name" value="<?= esc($setting['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <input type="text" class="form-control" id="description" placeholder="Tích hợp SePay" name="description" value="<?= esc($setting['description']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="client_id" class="form-label">Client ID</label>
                <input type="text" class="form-control" id="client_id" name="client_id" value="<?= esc($setting['client_id']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="redirect_uri" class="form-label">Redirect Uri</label>
                <input type="text" class="form-control" id="redirect_uri" name="redirect_uri" value="<?= esc($setting['redirect_uri']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="state" class="form-label">State</label>
                <input type="text" class="form-control" id="state" name="state" value="<?= esc($setting['state']) ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>