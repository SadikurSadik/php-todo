<?php

namespace todo\Storage;

use todo\Models\Task;

interface TaskStorageInterface
{
    public function all();
    public function getById($id);
    public function store(Task $task);
    public function update(Task $task);
    public function delete($id);
}