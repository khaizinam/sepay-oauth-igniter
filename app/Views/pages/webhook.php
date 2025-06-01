<div class="content">
    <div class="container py-4">
        <form action="<?= base_url('api/v1/se-pay/bank-account/update') ?>" method="POST" id="bankAccountForm">
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