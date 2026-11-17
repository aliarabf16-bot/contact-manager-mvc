<?php
declare(strict_types=1);

final class Contact
{
    public function all(): array
    {
        $statement = db()->query('SELECT id, name, email, phone, created_at FROM contacts ORDER BY id DESC');
        return $statement->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = db()->prepare('SELECT id, name, email, phone, created_at FROM contacts WHERE id = :id');
        $statement->execute(['id' => $id]);
        $contact = $statement->fetch();
        return $contact === false ? null : $contact;
    }

    public function create(array $data): void
    {
        $statement = db()->prepare('INSERT INTO contacts (name, email, phone) VALUES (:name, :email, :phone)');
        $statement->execute($data);
    }

    public function update(int $id, array $data): void
    {
        $statement = db()->prepare(
            'UPDATE contacts SET name = :name, email = :email, phone = :phone WHERE id = :id'
        );
        $statement->execute($data + ['id' => $id]);
    }

    public function delete(int $id): void
    {
        $statement = db()->prepare('DELETE FROM contacts WHERE id = :id');
        $statement->execute(['id' => $id]);
    }
}
