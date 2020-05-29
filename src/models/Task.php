<?php

namespace todo\Models;

use todo\enums\TaskStatus;

class Task
{
    protected $id;
    protected $name;
    protected $status;

    public function __construct($name = null, $status = TaskStatus::PENDING, $id = null)
    {
        if(!empty($name)) {
            $this->name = $name;
            $this->status = $status;
            $this->id = $id;
        }
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getId()
    {
        return $this->id;
    }
}
