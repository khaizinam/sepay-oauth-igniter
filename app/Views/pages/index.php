<div class="content">
    <div class="container py-4">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">ID DG</th>
                    <th scope="col">Tài khoản</th>
                    <th scope="col">Tài khoản ảo</th>
                    <th scope="col">Tiền</th>
                    <th scope="col">Loại giao dịch</th>
                    <th scope="col">Nội dung chuyển khoản</th>
                </tr>
            </thead>
            <tbody id="transactionTableBody">
                <?php foreach ($transactions ?? [] as $key => $item): ?>
                    <tr>
                        <th scope="row">
                            <?= app_get_data($item, 'id') ?>
                        </th>
                        <th>
                            <?= app_get_data($item, 'transaction_id') ?>
                        </th>
                        <td>
                            <?= app_get_data($item, 'account_number') ?>
                        </td>
                        <td>
                            <?= app_get_data($item, 'sub_account') ?>
                        </td>
                        <td>
                            <?= app_get_data($item, 'amount_out') > 0 ? '-' . app_get_data($item, 'amount_out') : '+' .app_get_data($item, 'amount_in') ?>
                        </td>
                        <td>
                            <?= app_get_data($item, 'amount_out') > 0 ? 'Tiền ra' : 'Tiền vào' ?>
                        </td>
                        <td>
                            <?= app_get_data($item, 'transaction_content') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>

</script>