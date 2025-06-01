<div class="content">
    <div class="container py-4">
        <a href="<?= base_url('/webhooks/create') ?>" class="btn btn-primary">
            Create new
        </a>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Webhook ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Active</th>
                    <th scope="col">Webhook URL</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody id="transactionTableBody">
                <?php foreach (@$webhooks as $key => $item): ?>
                    <tr>
                        <th scope="row">
                            <?= app_get_data($item, 'id') ?>
                        </th>
                        <td>
                            <?= app_get_data($item, 'webhook_id') ?>
                        </td>
                        <td>
                            <?= app_get_data($item, 'name') ?>
                        </td>
                        <td>
                            <?= app_get_data($item, 'active') == 0 ? 'Không hoạt động' : 'Hoạt động'; ?>
                        </td>
                        <td>
                            <?= app_get_data($item, 'webhook_url') ?>
                        </td>
                        <td>
                            <a class="btn btn-primary" href="<?= base_url('webhooks') .'/'. app_get_data($item, 'id') . '/edit'; ?>">
                                Edit
                            </a>
                            <a class="btn btn-danger" href="<?= base_url('webhooks') .'/'. app_get_data($item, 'id') . '/delete'; ?>">
                                DELETE
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>