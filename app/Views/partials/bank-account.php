<?php if (!blank($va_accounts)) : ?>
    <?php foreach ($va_accounts as $index => $value) : ?>
        <option value="<?= $value['id'] ?>" <?= $index === 0 ? 'selected' : '' ?>>
           <?= $value['account_number'] . ' - ' . $value['label'] ?>
    <?php endforeach; ?>
<?php endif; ?>
