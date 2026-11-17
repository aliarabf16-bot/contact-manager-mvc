# Contact Manager — Assignment 1

**Student:** Ali mohamed  
**Student ID:** 202202179  
**Course assessment:** Assignment 1 (topics 3–7: MySQL with PDO and custom MVC)  
**Stack:** Plain PHP 8.1+, MySQL/MariaDB, PDO, HTML/CSS; **no frameworks**.

## Purpose and features

A secure starter contact-management website using a manually implemented **Model–View–Controller** pattern. The contacts list is public, while only a signed-in administrator can create, edit and delete contacts.

- **CRUD:** list, add, edit and delete contacts with `id`, `name`, `email`, `phone`, `created_at`.
- **Authentication:** one administrator, created through a command-line script, with hashed password and secure session.
- **Security:** PDO prepared statements, escaped HTML output, validation, CSRF tokens, POST-only writes, and protected configuration.
- **Documentation:** security checklist, transparent AI attribution and test/progress templates.

## Directory structure

```text
contact-manager-mvc/
├── index.php                 # Front controller: route name + allowed HTTP method
├── controllers/
│   ├── AuthController.php
│   └── ContactController.php
├── models/
│   ├── Admin.php
│   └── Contact.php
├── views/
│   ├── layout.php
│   ├── auth/login.php
│   └── contacts/{list,form}.php
├── lib/
│   ├── Database.php
│   ├── helpers.php
│   └── validation.php
├── database/schema.sql       # MySQL schema
├── scripts/create_admin.php  # CLI-only: prompt for one admin account
├── assets/style.css
├── tests/validation_test.php
├── docs/                     # Security, integrity, test plan and progress log
└── config.example.php        # Copy locally to config.php (not tracked)
```

## Local setup (XAMPP / PHP + MySQL)

**Requirements:** PHP 8.1+, `pdo_mysql`, MySQL or MariaDB, Apache with `.htaccess` support, and a terminal. XAMPP can supply PHP, Apache, MySQL and phpMyAdmin.

1. Extract this directory into the Apache document root (on Windows/XAMPP usually `C:\xampp\htdocs\contact-manager-mvc`). Start **Apache** and **MySQL**.
2. Open **http://localhost/phpmyadmin**. Choose *Import*, select `database/schema.sql`, and import. It creates the `contact_manager` database and the `admins` and `contacts` tables.
3. Copy `config.example.php` to **`config.php`** and set the correct MySQL host, port, database name, username and password. The example has XAMPP's typical local root/blank-password default **for local development only**. Never publish the real config.
4. Open a terminal inside the project directory and create the one admin:

   ```bash
   php scripts/create_admin.php
   ```

   Enter a username (3–50 letters/numbers/periods/underscores/dashes) and a unique **12–72 byte** password; confirm it. This operation creates only one admin, and stores **only the hash**, not the plaintext password. If PHP is not on your Windows PATH, run `C:\xampp\php\php.exe scripts\create_admin.php`.
5. Visit **http://localhost/contact-manager-mvc/index.php** to view the contacts list. Click **Admin sign in** to log in and enable Add/Edit/Delete.
6. Run the lightweight checks:

   ```bash
   php tests/validation_test.php
   ```

7. Complete and record the real database/browser scenarios in `docs/TEST_PLAN.md` before submitting.

**No MySQL yet?** You can inspect and run validation tests without MySQL, but full CRUD and login require a configured MySQL server. There is no default admin password provided in the project.

## How MVC is organized

- `index.php` is the **front controller**. It maps `?action=list|new|create|edit|update|delete|login|signin|logout` to a controller and explicitly checks GET vs POST.
- **Controllers** decide what a request is allowed to do, validate it and select a view or redirect.
- **Models** hold the PDO database operations; SQL never uses concatenated user input.
- **Views** are responsible for HTML and call `e()` to escape dynamic strings.
- `lib/` contains shared connection, validation, session/CSRF and rendering helpers.

## SQL schema and CRUD workflow

See `database/schema.sql`. The `contacts` table includes all five fields required by the assignment. `id` automatically increments, `created_at` defaults to the current timestamp, and `email` is unique. The `admins` table stores one administrator's `username` and `password_hash` (created separately with the CLI script).

## Security checklist

| Requirement | Implementation |
|---|---|
| Prepared statements | PDO in `models/Contact.php` and `models/Admin.php`, emulation disabled |
| HTML escaping | `e()` in `lib/helpers.php` and its use in all views |
| Password hashing | `password_hash()` and `password_verify()` |
| Validation | Name/email/phone validation + positive numeric IDs |
| Login protection | PHP sessions, `require_login()` for every create/edit/update/delete path |
| CSRF | Random session token checked on all changes and login/logout |
| Safe HTTP actions | Only POST can mutate records |
| Secure configuration | Local config excluded from Git; Apache `.htaccess` restrictions |

For a full checklist, production caveats and evidence file locations, see **`docs/SECURITY_CHECKLIST.md`**. For a study-oriented explanation of the MVC request flow, see **`docs/MVC_EXPLANATION.md`**.

## Academic integrity, sources and project history

**AI assistance was used and must be disclosed.** The code and documentation were drafted with ChatGPT assistance on 17 November 2026. The supplied Assignment 1 PDF was the basis for requirements. See `docs/INTEGRITY_STATEMENT.md` for an explicit disclosure, source list, and **unsigned** student statement. The student must review/test the code, determine whether submission is allowed by the instructor's policy, and sign personally if appropriate.

The assessment requires **8+ meaningful Git commits over time** and **week-5 progress evidence**. These cannot be retrospectively created or truthfully certified by a ZIP or AI. Log actual work and real screenshots/commit URLs in `docs/PROGRESS_LOG.md`. Use normal Git commands when making real changes:

```bash
git init
git add .
git commit -m "Initialize PHP MVC contact manager"
git branch -M main
git remote add origin https://github.com/YOUR-USERNAME/YOUR-REPO.git
git push -u origin main
```

Replace the placeholder URL with your repository's actual address (and create the repository first). Do **not** fabricate or backdate commits. You should make meaningful further improvements in separate commits as you actually develop.

## Deliverables

1. **Git repository link** — create/push your own remote and submit the genuine URL (not created by this ZIP).
2. **ZIP archive** — project code with `database/schema.sql`.
3. **README with security list and integrity statement** — this README plus `docs/SECURITY_CHECKLIST.md` and `docs/INTEGRITY_STATEMENT.md`.

Refer to **`docs/SUBMISSION_CHECKLIST.md`** for remaining steps and `START_HERE_AR.md` for Arabic quick-start instructions.
