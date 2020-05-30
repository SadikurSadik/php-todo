<?php

namespace todo\storage;

use PDO;
use todo\enums\TaskStatus;
use todo\Models\Task;

class TaskStorageManager implements TaskStorageInterface
{
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db->getConnection();
    }

    public function all()
    {
        $statement = $this->db->prepare("SELECT id, name, status, created_at FROM tasks order by id desc");
        $statement->setFetchMode(PDO::FETCH_CLASS, Task::class);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $statement = $this->db->prepare("SELECT id, name, status, created_at, updated_at FROM tasks WHERE id = :id");
        $statement->setFetchMode(PDO::FETCH_CLASS, Task::class);
        $statement->execute([
            'id' => $id,
        ]);

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function store(Task $task)
    {
        $statement = $this->db->prepare("INSERT INTO tasks (name) VALUES (:name)");
        $statement->execute(['name' => $task->getName()]);

        return $this->getById($this->db->lastInsertId());
    }

    public function update(Task $task)
    {
        $statement = $this->db->prepare("UPDATE tasks SET name = :name, status = :status WHERE id = :id");
        $statement->execute(['name' => $task->getName(), 'status' => $task->getStatus(), 'id' => $task->getid()]);

        return $this->getById($task->getid());
    }

    public function delete($id)
    {
        $statement = $this->db->prepare("DELETE FROM tasks WHERE id = :id");
        $statement->execute(['id' => $id]);
        return $statement->fetch();
    }

    public function deleteCompleted()
    {
        $statement = $this->db->prepare("DELETE FROM tasks WHERE status = :status");
        $statement->execute(['status' => TaskStatus::COMPLETED]);
        return $statement->fetch();
    }
}