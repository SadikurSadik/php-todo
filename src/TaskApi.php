<?php

namespace todo;

use todo\enums\HttpCode;
use todo\Models\Task;
use todo\Storage\Connection;
use todo\storage\TaskStorageManager;

class TaskApi
{
    const SUCCESS = 'Success!';
    const FAILED = 'Failed!';
    private $taskManager;

    public function __construct()
    {
        $this->taskManager = new TaskStorageManager(new Connection());
    }

    public function action($event = '')
    {
        switch ($event) {
            case 'create_task':
                return $this->createNewTask();
                break;

            case 'update_task':
                return $this->updateTask();
                break;

            case 'delete_task':
                return $this->deleteTask();
                break;

            case 'delete_completed_task':
                return $this->deleteCompletedTask();
                break;

            case 'get_task':
                return $this->getTask();
                break;

            case 'get_all_task':
                return $this->getAllTasks();
                break;

            default:
                return $this->apiResponse([], self::FAILED, HttpCode::BAD_REQUEST, 'Opps! Something went wrong.');
        }
    }

    private function createNewTask()
    {
        try {
            $task = new Task($_POST['name']);
            return $this->apiResponse($this->taskManager->update($task));
        } catch (\Exception $e) {
            return $this->apiResponse([], self::FAILED, HttpCode::BAD_REQUEST, 'Opps! Something went wrong.');
        }
    }

    private function updateTask()
    {
        try {
            $task = new Task($_POST['name'], $_POST['status'], $_POST['id']);
            return $this->apiResponse($this->taskManager->update($task));
        } catch (\Exception $e) {
            return $this->apiResponse([], self::FAILED, HttpCode::BAD_REQUEST, 'Opps! Something went wrong.');
        }
    }

    private function deleteTask()
    {
        try {
            return $this->apiResponse($this->taskManager->delete($_GET['id']));
        } catch (\Exception $e) {
            return $this->apiResponse([], self::FAILED, HttpCode::BAD_REQUEST, 'Opps! Something went wrong.');
        }
    }

    private function getTask()
    {
        $task = $this->taskManager->getById($_GET['id']);
        if (empty($task)) {
            return $this->apiResponse([], self::FAILED, HttpCode::NOT_FOUND, 'Not Found!');
        }

        return $this->apiResponse($task);
    }

    private function getAllTasks()
    {
        return $this->apiResponse($this->taskManager->all());
    }

    private function deleteCompletedTask()
    {
        return $this->apiResponse($this->taskManager->deleteCompleted());
    }

    private function apiResponse($data = [], $status = self::SUCCESS, $code = HttpCode::SUCCESS, $message = '')
    {
        return json_encode([
            'code' => $code,
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ]);
    }

}