<div class="form-page">
    <div class="panel form-panel">
        <p class="eyebrow">ADMIN ACCESS</p>
        <h1>Sign in</h1>
        <p class="muted">Administrators can add, update, and delete contacts. Visitors can only view them.</p>
        <form action="index.php?action=signin" method="post">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <div class="field">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" maxlength="50" autocomplete="username" required>
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" autocomplete="current-password" required>
            </div>
            <button class="btn btn-primary full-width" type="submit">Sign in</button>
        </form>
    </div>
</div>
