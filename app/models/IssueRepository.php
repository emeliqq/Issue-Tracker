<?php

class IssueRepository
{
    private string $file;

    public function __construct()
    {
        $this->file = __DIR__ . '/../../data/issues.json';
    }

    public function getAll(): array
    {
        $data = file_get_contents($this->file);

        return json_decode($data, true) ?? [];
    }

    public function add(array $issue): void
    {
        $issues = $this->getAll();

        $issues[] = $issue;

        file_put_contents(
            $this->file,
            json_encode($issues, JSON_PRETTY_PRINT)
        );
    }

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

    public function updateField(
        string $id,
        string $field,
        string $value
    ): void {

        $issues = $this->getAll();

        foreach ($issues as &$issue) {

            if (($issue['id'] ?? '') === $id) {

                $issue[$field] = $value;

                $issue['updated_at'] = date('Y-m-d H:i:s');

                break;
            }

        }

        file_put_contents(
            $this->file,
            json_encode($issues, JSON_PRETTY_PRINT)
        );
    }

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