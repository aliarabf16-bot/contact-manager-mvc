<?php $isEditing = $mode === 'update'; ?>
<div class="form-page">
    <a class="back-link" href="index.php?action=list">← Back to contacts</a>
    <div class="panel form-panel">
        <p class="eyebrow"><?= $isEditing ? 'EDIT CONTACT' : 'NEW CONTACT' ?></p>
        <h1><?= $isEditing ? 'Edit contact' : 'Add a contact' ?></h1>
        <p class="muted">All fields are required.</p>
        <form method="post" action="index.php?action=<?= $isEditing ? 'update' : 'create' ?>" novalidate>
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <?php if ($isEditing): ?>
                <input type="hidden" name="id" value="<?= (int) $contact['id'] ?>">
            <?php endif; ?>
            <?php foreach (['name' => 'Full name', 'email' => 'Email address', 'phone' => 'Phone number'] as $field => $label): ?>
                <div class="field">
                    <label for="<?= e($field) ?>"><?= e($label) ?></label>
                    <input id="<?= e($field) ?>" type="<?= $field === 'email' ? 'email' : 'text' ?>"
                           name="<?= e($field) ?>" value="<?= e($contact[$field] ?? '') ?>"
                           maxlength="<?= $field === 'name' ? '100' : ($field === 'email' ? '190' : '30') ?>"
                           required aria-invalid="<?= isset($errors[$field]) ? 'true' : 'false' ?>">
                    <?php if (isset($errors[$field])): ?>
                        <p class="field-error" role="alert"><?= e($errors[$field]) ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= $isEditing ? 'Save changes' : 'Create contact' ?></button>
                <a href="index.php?action=list" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
