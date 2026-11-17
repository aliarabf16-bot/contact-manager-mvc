# MVC walkthrough for explaining your assignment

**Ali mohamed — 202202179**

## What happens when someone opens the app?

1. A browser requests `index.php?action=list`.
2. `index.php`, the **front controller**, checks the action and HTTP method and calls `ContactController::index()`.
3. The controller calls `Contact::all()`.
4. The model obtains PDO through `db()` and queries MySQL for contacts.
5. The controller calls `render('contacts/list', ...)`.
6. `views/layout.php` draws the shared header/footer and includes `views/contacts/list.php` to show records with escaped HTML.

This is **custom MVC** because the routing table, controller classes, model methods and template rendering are implemented in this codebase rather than an external framework.

## Create example

A signed-in administrator chooses **Add contact**. The GET `new` route displays the form with a CSRF token. Submitting it sends a POST to `create`, so `ContactController::store()`:

- checks authentication (`require_login()`),
- checks CSRF token (`require_csrf()`),
- validates the posted name, email and phone (`validate_contact()`),
- calls `Contact::create()` which uses a PDO prepared INSERT,
- redirects to `list` with a success message.

An invalid input returns errors to the form without inserting a record. A duplicate email is caught because the database column is UNIQUE.

## Login example

`AuthController::signIn()` checks the login CSRF token; validates input lengths and a basic per-session attempt limit; looks up the admin account by prepared SQL through `Admin::verify()`; and verifies the hash with `password_verify()`. A successful login regenerates the session ID and stores the admin's ID and username in the session. Later write actions must pass `require_login()`.

## Key questions to rehearse

- Why use `PDO::prepare()` instead of concatenating user input into SQL?
- How are `password_hash()` and `password_verify()` different from encryption or plaintext storage?
- Why is HTML escaping necessary even after input validation?
- Why is a `POST` request for deletion safer than a simple link?
- What does a CSRF token prevent?
- What is stored in `$_SESSION`, and when does login regenerate the session ID?
- How do Controller and Model differ?
- Why can a visitor view the list but not add, edit or delete contacts?

**Rehearse these with the real source files open, and test each feature locally before demonstrating.**
