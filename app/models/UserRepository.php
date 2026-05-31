<?php

require_once __DIR__ . '/../../config/Database.php';

class UserRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

/* LOAD ALL USERS ------------------------------------------------------------------------------------------------------------------------------------------------------------ */

    public function getAll(): array
    {
        $stmt = $this->connection->query(
            "SELECT * FROM users"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

 /* ADD NEW USER ------------------------------------------------------------------------------------------------------------------------------------------------------------- */

    public function add(array $user): void
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO users (
                id,
                first_name,
                last_name,
                email,
                password,
                created_at
            )
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        $stmt->execute([
            $user['id'],
            $user['first_name'],
            $user['last_name'],
            $user['email'],
            $user['password'],
            $user['created_at']
        ]);
    }

/* FIND USER BY EMAIL -------------------------------------------------------------------------------------------------------------------------------------------------------- */

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM users
            WHERE email = ?"
        );

        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

/* FIND USER BY ID ------------------------------------------------------------------------------------------------------------------------------------------------------------- */

    public function findById(string $id): ?array
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM users
            WHERE id = ?"
        );

        $stmt->execute([$id]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
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

        $number = (int) str_replace(
            'USR-',
            '',
            $lastId
        );

        $nextNumber = $number + 1;

        return 'USR-' . str_pad(
            $nextNumber,
            5,
            '0',
            STR_PAD_LEFT
        );
    }
}