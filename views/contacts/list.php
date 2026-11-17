<section class="page-heading">
    <div>
        <p class="eyebrow">CONTACTS DIRECTORY</p>
        <h1>Manage contacts</h1>
        <p class="muted">Keep names, email addresses, and phone numbers in one place.</p>
    </div>
    <?php if (current_admin() !== null): ?>
        <a class="btn btn-primary" href="index.php?action=new">+ Add contact</a>
    <?php endif; ?>
</section>

<div class="panel">
    <div class="panel-heading">
        <h2>All contacts</h2>
        <span class="count"><?= count($contacts) ?> total</span>
    </div>
    <?php if ($contacts === []): ?>
        <div class="empty-state">
            <h3>No contacts yet</h3>
            <p class="muted">Sign in as an admin to create the first contact.</p>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Added</th><?php if (current_admin() !== null): ?><th>Actions</th><?php endif; ?></tr></thead>
                <tbody>
                <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td class="contact-name"><?= e($contact['name']) ?></td>
                        <td><a href="mailto:<?= e($contact['email']) ?>"><?= e($contact['email']) ?></a></td>
                        <td><?= e($contact['phone']) ?></td>
                        <td class="muted nowrap"><?= e($contact['created_at']) ?></td>
                        <?php if (current_admin() !== null): ?>
                            <td>
                                <div class="table-actions">
                                    <a class="btn btn-small" href="index.php?action=edit&amp;id=<?= (int) $contact['id'] ?>">Edit</a>
                                    <form action="index.php?action=delete" method="post" class="inline-form" onsubmit="return confirm('Delete this contact?');">
                                        <input type="hidden" name="id" value="<?= (int) $contact['id'] ?>">
                                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                        <button class="btn btn-small btn-danger" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
