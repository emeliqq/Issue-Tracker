<?php

require_once __DIR__ . '/../../config/Database.php';

class IssueRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

/* LOAD ALL ISSUES ---------------------------------------------------------------------------------------------------------------------------------------------------------- */

    public function getAll(): array
    {
        $stmt = $this->connection->query(
            "SELECT * FROM issues"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

/* ADD NEW ISSUE ------------------------------------------------------------------------------------------------------------------------------------------------------------- */

    public function add(array $issue): void
    {
            $statusMap = [
                'Open' => 1,
                'In Progress' => 2,
                'Resolved' => 3
            ];

            $severityMap = [
                'Low' => 1,
                'Medium' => 2,
                'High' => 3
            ];

            $stmt = $this->connection->prepare(
                "INSERT INTO issues (
                    id,
                    summary,
                    description,
                    steps_to_reproduce,
                    status_id,
                    severity_id,
                    reporter_id,
                    assignee_id,
                    updater_id,
                    created_at,
                    updated_at
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );

            $stmt->execute([
                $issue['id'],
                $issue['summary'],
                $issue['description'],
                $issue['steps_to_reproduce'],

                $statusMap[$issue['status']],
                $severityMap[$issue['priority']],

                $issue['reporter'],
                $issue['assignee'],
                $issue['updater'],

                $issue['created_at'],
                $issue['updated_at']
            ]);
        }

    /* FIND ISSUE BY ID ---------------------------------------------------------------------------------------------------------------------------------------------------------- */

        public function findById(string $id): ?array
        {
            $stmt = $this->connection->prepare(
                "SELECT * FROM issues
                WHERE id = ?"
            );

            $stmt->execute([$id]);

            $issue = $stmt->fetch(PDO::FETCH_ASSOC);

            return $issue ?: null;
        }

    /* UPDATE ISSUE FIELD -------------------------------------------------------------------------------------------------------------------------------------------------------- */

    public function updateField(
        string $id,
        string $field,
        string $value,
        string $updaterId
    ): void
    {
        $fieldMap = [
            'summary' => 'summary',
            'description' => 'description',
            'steps_to_reproduce' => 'steps_to_reproduce',

            'status' => 'status_id',
            'priority' => 'severity_id',
            'assignee' => 'assignee_id'
        ];

        if (!isset($fieldMap[$field])) {
            return;
        }

        $statusMap = [
            'Open' => 1,
            'In Progress' => 2,
            'Resolved' => 3
        ];

        $severityMap = [
            'Low' => 1,
            'Medium' => 2,
            'High' => 3
        ];

        if ($field === 'status') {
            $value = $statusMap[$value] ?? 1;
        }

        if ($field === 'priority') {
            $value = $severityMap[$value] ?? 1;
        }

        if (
            $field === 'assignee'
            && $value === ''
        ) {
            $value = null;
        }

        $databaseField = $fieldMap[$field];

        $stmt = $this->connection->prepare(
            "UPDATE issues
            SET {$databaseField} = ?,
                updater_id = ?,
                updated_at = NOW()
            WHERE id = ?"
        );

        $stmt->execute([
            $value,
            $updaterId,
            $id
        ]);
    }

/* GENERATE NEXT ISSUE ID ---------------------------------------------------------------------------------------------------------------------------------------------------- */

    public function getNextId(): string
    {
        $issues = $this->getAll();

        if (empty($issues)) {
            return 'QA-00001';
        }

        $lastIssue = end($issues);

        $lastId = $lastIssue['id'] ?? 'QA-00000';

        $number = (int) str_replace('QA-', '', $lastId);

        $nextNumber = $number + 1;

        return 'QA-' . str_pad(
            $nextNumber,
            5,
            '0',
            STR_PAD_LEFT
        );
    }
}