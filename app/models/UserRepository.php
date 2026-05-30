<?php

class UserRepository
{
    private string $file;

    public function __construct()
    {
        $this->file = __DIR__ . '/../../data/users.json';
    }

/* LOAD ALL USERS ------------------------------------------------------------------------------------------------------------------------------------------------------------ */

    public function getAll(): array
    {
        $data = file_get_contents($this->file);

        return json_decode($data, true) ?? [];
    }

 /* ADD NEW USER ------------------------------------------------------------------------------------------------------------------------------------------------------------- */

    public function add(array $user): void
    {
        $users = $this->getAll();

        $users[] = $user;

        file_put_contents(
            $this->file,
            json_encode($users, JSON_PRETTY_PRINT)
        );
    }

/* FIND USER BY EMAIL -------------------------------------------------------------------------------------------------------------------------------------------------------- */

    public function findByEmail(string $email): ?array
    {
        $users = $this->getAll();

        foreach ($users as $user) {

            if (($user['email'] ?? '') === $email) {
                return $user;
            }
        }
        return null;
    }

/* FIND USER BY ID ------------------------------------------------------------------------------------------------------------------------------------------------------------- */

    public function findById(string $id): ?array
    {
        $users = $this->getAll();

        foreach ($users as $user) {

            if (($user['id'] ?? '') === $id) {
                return $user;
            }

        }
        return null;
    }

/* GENERATE NEXT USER ID ----------------------------------------------------------------------------------------------------------------------------------------------------- */

    public function getNextId(): string
    {
        $users = $this->getAll();

        if (empty($users)) {
            return 'USR-00001';
        }

        $lastUser = end($users);

        $lastId = $lastUser['id'] ?? 'USR-00000';

        $number = (int) str_replace('USR-', '', $lastId);

        $nextNumber = $number + 1;

        return 'USR-' . str_pad(
            $nextNumber,
            5,
            '0',
            STR_PAD_LEFT
        );
    }
}