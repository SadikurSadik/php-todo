<?php

require __DIR__ . "/vendor/autoload.php";

if (isset($_GET['event'])) {
    echo (new \todo\TaskApi())->action($_GET['event']);
}
exit();

