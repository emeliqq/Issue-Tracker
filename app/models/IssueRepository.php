<?php

class IssueRepository
{
    private string $file;

    public function __construct()
    {
        $this->file = __DIR__ . '/../../data/issues.json';
    }

/* LOAD ALL ISSUES ---------------------------------------------------------------------------------------------------------------------------------------------------------- */

    public function getAll(): array
    {
        $data = file_get_contents($this->file);

        return json_decode($data, true) ?? [];
    }

/* ADD NEW ISSUE ------------------------------------------------------------------------------------------------------------------------------------------------------------- */

    public function add(array $issue): void
    {
        $issues = $this->getAll();

        $issues[] = $issue;

        file_put_contents(
            $this->file,
            json_encode($issues, JSON_PRETTY_PRINT)
        );
    }

/* FIND ISSUE BY ID ---------------------------------------------------------------------------------------------------------------------------------------------------------- */

    public function findById(string $id): ?array
    {
        $issues = $this->getAll();

        foreach ($issues as $issue) {

            if (($issue['id'] ?? '') === $id) {
                return $issue;
            }

        }
        return null;
    }

/* UPDATE ISSUE FIELD -------------------------------------------------------------------------------------------------------------------------------------------------------- */

    public function updateField(
        string $id,
        string $field,
        string $value,
        string $updaterId
    ): void
    {
        $issues = $this->getAll();

        foreach ($issues as &$issue) {

            if (($issue['id'] ?? '') === $id) {

                $issue[$field] = $value;

                $issue['updated_at'] = date('Y-m-d H:i:s');

                $issue['updater'] = $updaterId;

            break;
            }
        }

        file_put_contents(
            $this->file,
            json_encode($issues, JSON_PRETTY_PRINT)
        );
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