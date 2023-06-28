<?php

declare(strict_types=1);

use DataBase\Table;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$table     = new Table('todo');

$newRecord = false;

if (isset($_POST['name']))
{
}

// var_dump($table->getRecords());

if (isset($_POST['endDate']))
{
    var_dump(date_create($_POST['endDate']));
}

echo loadView('todo');
