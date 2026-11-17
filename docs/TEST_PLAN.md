# Test plan — run on your actual PHP + MySQL installation

Student: **Ali mohamed (202202179)**

## Automated checks

From the project root:

```bash
php tests/validation_test.php
```

The script checks valid/invalid contact input, Unicode name support, and HTML escaping. A passing CLI test **does not prove** database CRUD or browser authentication works; follow the manual scenarios below.

## Manual scenarios (record actual results)

| # | Scenario | Expected | Observed / pass / fail |
|---|---|---|---|
| 1 | Import `database/schema.sql` in phpMyAdmin | `contact_manager` database with `admins` and `contacts` | Not tested |
| 2 | Copy `config.example.php` to `config.php`, set database credentials, create admin via CLI | Admin stored as a password hash | Not tested |
| 3 | Open `index.php` before signing in | Contacts list visible, **no** Add/Edit/Delete buttons | Not tested |
| 4 | Visit `?action=new` when signed out | Redirect to admin login | Not tested |
| 5 | Attempt invalid admin login | Generic error, login not granted | Not tested |
| 6 | Log in with the actual admin account | Redirect to contact list, edit controls visible | Not tested |
| 7 | Add valid contact | Contact appears with a created timestamp | Not tested |
| 8 | Add invalid email, phone, or short name | Validation errors, database unchanged | Not tested |
| 9 | Add a duplicate email | Helpful duplicate-email message, no second record | Not tested |
| 10 | Edit a contact | Updated values appear on list | Not tested |
| 11 | Delete a contact and confirm | Row removed from list | Not tested |
| 12 | While signed in, submit contact POST without valid CSRF token | Request rejected with HTTP 419 | Not tested |
| 13 | Sign out, then try edit/update/delete URLs | Not permitted; redirect to login | Not tested |
| 14 | Try a name containing `<script>` | Rendered as text, not executed | Not tested |
| 15 | Open `config.php` or `/models/Contact.php` directly over Apache | HTTP 403 | Not tested |

**Important:** Replace “Not tested” only after genuinely running that scenario. The project ZIP contains no populated database or plaintext administrator credentials.
