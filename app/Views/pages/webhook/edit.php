<div class="content">
    <div class="container py-4">
        <form action="<?= base_url('api/v1/se-pay/webhooks') . '/' . app_get_data($webhook, 'id') . '/update' ?>" method="POST" id="webhookForm">
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
                                        <option value="<?= esc($bank['id']) ?>" <?= $bank['id'] == app_get_data($webhook, 'bank_account_id') ? 'selected' : '' ?>>
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
                            <input type="text" class="form-control" id="name" name="name" value="<?= app_get_data($webhook, 'name') ?>" required>
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
                                <option value="All" value="<?= app_get_data($webhook, 'event_type') == 'All' ? 'selected' : '' ?>" >
                                    All
                                </option>
                                <option value="In_only" value="<?= app_get_data($webhook, 'event_type') == 'In_only' ? 'selected' : '' ?>" >
                                    In only
                                </option>
                                <option value="Out_only" value="<?= app_get_data($webhook, 'event_type') == 'Out_only' ? 'selected' : '' ?>" >
                                    Out only
                                </option>
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
                                <option value="No_Authen" value="<?= app_get_data($webhook, 'authen_type') == 'No_Authen' ? 'selected' : '' ?>" >
                                    No Authen
                                </option>
                                 <option value="OAuth2.0" value="<?= app_get_data($webhook, 'authen_type') == 'OAuth2.0' ? 'selected' : '' ?>" >
                                    OAuth 2.0
                                </option>
                                <option value="Api_Key" value="<?= app_get_data($webhook, 'authen_type') == 'Api_Key' ? 'selected' : '' ?>" >
                                    Api Key
                                </option>
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
                            <input type="text" class="form-control" id="webhook_url" name="webhook_url" value="<?= app_get_data($webhook, 'webhook_url') ?>" required>
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
                                <option value="0" value="<?= app_get_data($webhook, 'active') == 0 ? 'selected' : '' ?>" >
                                    không hoạt động
                                </option>
                                <option value="1" value="<?= app_get_data($webhook, 'active') == 1 ? 'selected' : '' ?>" >
                                    Đang hoạt động
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="webhook_id" value="<?= app_get_data($webhook, 'webhook_id') ?>">
                <input type="hidden" name="is_verify_payment" value="<?= app_get_data($webhook, 'is_verify_payment') ?>">
                <input type="hidden" name="skip_if_no_code" value="<?= app_get_data($webhook, 'skip_if_no_code') ?>">
                <input type="hidden" name="only_va" value="<?= app_get_data($webhook, 'only_va') ?>">
                <input type="hidden" name="request_content_type" value="<?= app_get_data($webhook, 'request_content_type') ?>">
                <div class="col-md-12 mb-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>