<div class="content">
    <div class="container py-4" style="max-width: 600px;">
        <div class="row">
            <div class="col-md-12">
                <label for="bank_accounts" class="form-label">Select Bank Account</label>
                <select name="bank_account" id="bank_accounts" class="form-select">
                    <option value="">Select a bank account</option>
                    <?php if (!empty($banks)) : ?>
                        <?php foreach ($banks as $bank) : ?>
                            <option value="<?= esc($bank['id']) ?>">
                                <?= esc($bank['account_holder_name']) .' - '. esc($bank['account_number']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <option value="">No bank accounts available</option>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#bank_accounts').on('change', function() {
            const selectedBank = $(this).val();
            
        });
    });
</script>