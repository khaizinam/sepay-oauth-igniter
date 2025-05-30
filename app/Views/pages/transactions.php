<div class="content">
    <div class="container py-4">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Tài khoản</th>
                    <th scope="col">Tài khoản ảo</th>
                    <th scope="col">Tiền</th>
                    <th scope="col">Loại giao dịch</th>
                </tr>
            </thead>
            <tbody id="transactionTableBody">
                <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                    <td>@mdo</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Fetch transactions when the page loads
        fetchTransactions();

        function fetchTransactions() {
            $.ajax({
                url: '<?= base_url('api/v1/se-pay/transactions') ?>',
                type: 'GET',
                data: {
                    bank_account_id: '<?= esc($se_pay_setting['bank_account_id']) ?>',
                    from_date : '2025-05-25',
                    to_date : '2025-05-30',
                    limit : 20
                },
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    $('meta[name="csrf-token"]').attr('content', res.csrf_hash);
                },
                error: function(xhr, status, error) {
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.csrf_hash) {
                            $('meta[name="csrf-token"]').attr('content', xhr.responseJSON.csrf_hash);
                        }
                    }
                }
            });
        }
    });
</script>