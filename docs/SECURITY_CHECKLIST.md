# Security checklist

Project owner: **Ali mohamed** | Student ID: **202202179**

Implemented safeguards, with where to verify each item:

- [x] **SQL injection prevention:** `models/Contact.php` and `models/Admin.php` use PDO prepared statements for all queries containing inputs; `lib/Database.php` disables emulated prepares.
- [x] **Output encoding / XSS:** `lib/helpers.php` defines `e()` using `htmlspecialchars` with `ENT_QUOTES | ENT_SUBSTITUTE` and UTF-8. Views call `e()` for contact values, messages and titles.
- [x] **Password hashing:** `scripts/create_admin.php` calls `password_hash()`, and `models/Admin.php` verifies with `password_verify()`. Neither plaintext passwords nor hashes are included in the SQL script.
- [x] **Server-side validation:** `lib/validation.php` checks names, emails and phone numbers before database writes; request IDs are validated in `lib/helpers.php`.
- [x] **Authentication and authorization:** `controllers/AuthController.php` controls the admin login; `ContactController.php` calls `require_login()` for add/edit/delete actions. Public visitors can view the list but cannot change records.
- [x] **CSRF protection:** `lib/helpers.php` issues per-session tokens; login, logout, create, update and delete POST requests check them.
- [x] **HTTP method safety:** `index.php` routes requests by exact action and method; mutations are POST-only.
- [x] **Session protection:** strict sessions, HTTP-only cookies, SameSite=Lax, HTTPS-only cookie flag when served over HTTPS, and session ID regeneration after login.
- [x] **Basic login throttling:** the login controller limits failed attempts in the session. This is a teaching-level measure, not a substitute for server-side / IP-based production rate limiting.
- [x] **Least exposure:** the root `.htaccess` and directory-specific `.htaccess` rules deny direct browser access to source folders and local configuration under Apache. `config.php` is `.gitignore`d.
- [x] **Error handling:** unexpected exceptions are logged; the browser receives a generic error page.
- [ ] **Deployment check:** before public hosting, enable HTTPS, confirm Apache honors `.htaccess`, restrict database privileges, and disable directory exposure at the web server level.
- [ ] **Manual live check:** after configuring MySQL locally, complete the testing steps in `docs/TEST_PLAN.md` and add real observations.

**Security caveat:** This student project is intended for a local learning environment. It has not been independently audited or penetration-tested.
