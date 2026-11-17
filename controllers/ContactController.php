<?php
declare(strict_types=1);

final class ContactController
{
    private Contact $contacts;

    public function __construct()
    {
        $this->contacts = new Contact();
    }

    public function index(): void
    {
        render('contacts/list', ['title' => 'Contacts', 'contacts' => $this->contacts->all()]);
    }

    public function createForm(): void
    {
        require_login();
        $form = $_SESSION['form'] ?? [];
        unset($_SESSION['form']);
        render('contacts/form', [
            'title' => 'Add contact',
            'mode' => 'create',
            'contact' => $form['values'] ?? ['name' => '', 'email' => '', 'phone' => ''],
            'errors' => $form['errors'] ?? [],
        ]);
    }

    public function store(): void
    {
        require_login();
        require_csrf();
        ['data' => $data, 'errors' => $errors] = validate_contact($_POST);
        if ($errors !== []) {
            $_SESSION['form'] = ['values' => $data, 'errors' => $errors];
            redirect('new');
        }
        try {
            $this->contacts->create($data);
        } catch (PDOException $error) {
            if ($error->getCode() === '23000') {
                $_SESSION['form'] = ['values' => $data, 'errors' => ['email' => 'This email address is already used.']];
                redirect('new');
            }
            throw $error;
        }
        flash('success', 'Contact added successfully.');
        redirect('list');
    }

    public function editForm(): void
    {
        require_login();
        $id = contact_id_from_query();
        $contact = $this->contacts->find($id);
        if ($contact === null) {
            abort_request(404, 'Contact not found.');
        }
        $form = $_SESSION['form'] ?? [];
        unset($_SESSION['form']);
        if (($form['id'] ?? null) === $id) {
            $contact = array_merge($contact, $form['values'] ?? []);
        }
        render('contacts/form', [
            'title' => 'Edit contact',
            'mode' => 'update',
            'contact' => $contact,
            'errors' => ($form['id'] ?? null) === $id ? ($form['errors'] ?? []) : [],
        ]);
    }

    public function update(): void
    {
        require_login();
        require_csrf();
        $id = contact_id_from_post();
        if ($this->contacts->find($id) === null) {
            abort_request(404, 'Contact not found.');
        }
        ['data' => $data, 'errors' => $errors] = validate_contact($_POST);
        if ($errors !== []) {
            $_SESSION['form'] = ['id' => $id, 'values' => $data, 'errors' => $errors];
            redirect('edit', ['id' => $id]);
        }
        try {
            $this->contacts->update($id, $data);
        } catch (PDOException $error) {
            if ($error->getCode() === '23000') {
                $_SESSION['form'] = ['id' => $id, 'values' => $data, 'errors' => ['email' => 'This email address is already used.']];
                redirect('edit', ['id' => $id]);
            }
            throw $error;
        }
        flash('success', 'Contact updated successfully.');
        redirect('list');
    }

    public function destroy(): void
    {
        require_login();
        require_csrf();
        $id = contact_id_from_post();
        if ($this->contacts->find($id) === null) {
            abort_request(404, 'Contact not found.');
        }
        $this->contacts->delete($id);
        flash('success', 'Contact deleted successfully.');
        redirect('list');
    }
}
