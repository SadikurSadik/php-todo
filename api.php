<?php

require __DIR__ . "/vendor/autoload.php";

use todo\enums\HttpCode;
use todo\Models\Task;
use todo\Storage\Connection;
use todo\storage\TaskStorageManager;

const SUCCESS = 'Success!';
const FAILED = 'Failed!';
$eventMapper = [
    'create_task' => 'createNewTask',
    'update_task' => 'updateTask',
    'delete_task' => 'deleteTask',
    'get_task' => 'getTask',
    'get_all_task' => 'getAllTasks',
];

if (isset($_GET['event']) &&  array_key_exists($_GET['event'], $eventMapper)) {
    $taskManager = new TaskStorageManager(new Connection());
    call_user_func($eventMapper[$_GET['event']], $taskManager);
} else {
    apiResponse([], FAILED, HttpCode::BAD_REQUEST, 'Opps! Something went wrong.');
}

function createNewTask(TaskStorageManager $manager)
{
    try {
        $task = new Task($_POST['name']);
        apiResponse($manager->update($task));
    } catch (Exception $e) {
        apiResponse([], FAILED, HttpCode::BAD_REQUEST, 'Opps! Something went wrong.');
    }
}

function updateTask(TaskStorageManager $manager)
{
    try {
        $task = new Task($_POST['name'], $_POST['status'], $_POST['id']);
        apiResponse($manager->update($task));
    } catch (Exception $e) {
        apiResponse([], FAILED, HttpCode::BAD_REQUEST, 'Opps! Something went wrong.');
    }
}

function deleteTask(TaskStorageManager $manager)
{
    try {
        apiResponse($manager->delete($_GET['id']));
    } catch (Exception $e) {
        apiResponse([], FAILED, HttpCode::BAD_REQUEST, 'Opps! Something went wrong.');
    }
}

function getTask(TaskStorageManager $manager)
{
    $task = $manager->getById($_GET['id']);
    if (empty($task)) {
        apiResponse([], FAILED, HttpCode::NOT_FOUND, 'Not Found!');
    }

    apiResponse($task);
}

function getAllTasks(TaskStorageManager $manager)
{
    apiResponse($manager->all());
}

function apiResponse($data = [], $status = SUCCESS, $code = HttpCode::SUCCESS, $message = '')
{
    echo json_encode([
        'code' => $code,
        'status' => $status,
        'message' => $message,
        'data' => $data,
    ]);

    exit();
}