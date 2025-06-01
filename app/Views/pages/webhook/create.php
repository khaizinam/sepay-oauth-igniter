<div class="content">
    <div class="container py-4">
        <form action="<?= base_url('api/v1/se-pay/webhooks/store') ?>" method="POST" id="webhookForm">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="bank_account_id" class="form-label">Bank Account</label>
                        </div>
                        <div class="col-md-8">
                            <select name="bank_account_id" id="bank_account_id" class="form-select">
                                <option value="">Select a bank account</option>
                                <?php if (!empty($banks)) : ?>
                                    <?php foreach ($banks as $bank) : ?>
                                        <option value="<?= esc($bank['id']) ?>" <?= $bank['id'] == app_get_data($se_pay_setting, 'bank_account_id') ? 'selected' : '' ?>>
                                            <?= esc($bank['bank']['brand_name'] . ' - ' . $bank['account_holder_name'] .' - '. $bank['account_number']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <option value="">No bank accounts available</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="name" class="form-label">Name</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="name" name="name" value="Tích hợp SePay" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="event_type" class="form-label">Event Type</label>
                        </div>
                        <div class="col-md-8">
                            <select name="event_type" id="event_type" class="form-select">
                                <option value="All" selected>All</option>
                                <option value="In_only">In only</option>
                                <option value="Out_only">Out only</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="authen_type" class="form-label">Authen Type</label>
                        </div>
                        <div class="col-md-8">
                            <select name="authen_type" id="authen_type" class="form-select">
                                <option value="No_Authen" selected>No Authen</option>
                                <option value="OAuth2.0">OAuth 2.0</option>
                                <option value="Api_Key">Api Key</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="webhook_url" class="form-label">Webhook Url</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="webhook_url" name="webhook_url" value="<?= base_url('api/v1/se-pay/webhooks/leadgen') ?>" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="active" class="form-label">Active</label>
                        </div>
                        <div class="col-md-8">
                            <select name="active" id="active" class="form-select">
                                <option value="0">không hoạt động</option>
                                <option value="1" selected>Đang hoạt động</option>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="is_verify_payment" value="0">
                <input type="hidden" name="skip_if_no_code" value="0">
                <input type="hidden" name="only_va" value="0">
                <input type="hidden" name="request_content_type" value="Json">
                <div class="col-md-12 mb-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
       
    });
</script>