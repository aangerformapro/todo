<?php

declare(strict_types=1);

use DataBase\Table;
use voku\helper\AntiXSS;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$table     = new Table('todo');

$antiXss   = new AntiXSS();

$newRecord = false;

if (isset($_POST['name']))
{
    $formData  = [
        'name'        => $antiXss->xss_clean($_POST['name']),
        'description' => $antiXss->xss_clean($_POST['description']),
        'endDate'     => $antiXss->xss_clean($_POST['endDate']),
    ];

    $newRecord = $table->addRecord($formData);
}

// var_dump($table->getRecords());

// if (isset($_POST['endDate']))
// {
//     var_dump(date_create($_POST['endDate']));
// }

echo loadView('todo', ['newRecord' => $newRecord, 'tasks' => $table]);
