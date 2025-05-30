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
                    <div class="row">
                        <div class="col-md-4">
                            <label for="va_account_id" class="form-label">VA ACOUNT</label>
                        </div>
                        <div class="col-md-8">
                            <select name="va_account_id" id="va_account_id" class="form-select">
                                <option value="">---</option>
                                <?php if (!blank($va_accounts)) : ?>
                                    <?php foreach ($va_accounts as $index => $value) : ?>
                                        <option value="<?= $value['id'] ?>" <?= app_get_data($value, 'id') == app_get_data($se_pay_setting, 'va_account_id') ? 'selected' : '' ?>>
                                        <?= app_get_data($value, 'account_number', '') . ' - ' . app_get_data($value, 'label', '') ?>
                                    <?php endforeach; ?>
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
        $('#bank_account_id').on('change', function() {
            const selectedBank = $(this).val();
            if(!selectedBank) {
                $('#va_account_id').html('<option value="" selected>---</option>');
                return;
            }
            $.ajax({
                url: '<?= base_url('api/v1/se-pay/bank-account') ?>/' + selectedBank + '/sub-accounts',
                method: 'GET',
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(res) {
                    $('meta[name="csrf-token"]').attr('content', res.csrf_hash);
                    $('#va_account_id').html(res.data)
                },
                error: function(xhr, status, error) {
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.csrf_hash) {
                            $('meta[name="csrf-token"]').attr('content', xhr.responseJSON.csrf_hash);
                        }
                    }
                }
            });
        });
    });
</script>