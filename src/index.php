<?php

declare(strict_types=1);

use DataBase\Table;
use voku\helper\AntiXSS;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$table     = new Table('todo');

$antiXss   = new AntiXSS();

$newRecord = $isRemoved = false;
$error     = null;

$now       = date_create('now');

$action    = getPostdata(['action'])['action'];

if ('add' === $action)
{
    $data = getPostdata(['name', 'description', 'end_date']);

    foreach ($data as $value)
    {
        if (null === $value)
        {
            $error = 'Tous les champs n\' ont pas été remplis';
            break;
        }
    }

    if (isExpired($data['end_date']))
    {
        $error = 'Vous ne pouvez pas créer une tâche à effectuer dans le passé !!!';
    }

    if ( ! $error)
    {
        $data['done'] = false;
        $newRecord    = $table->addRecord($data);
    }
}

if ('update' === $action)
{
    $data    = getPostdata(['id', 'done']);

    $newdata = ['done' => 'on' === $data['done']];

    $table->updateRecord($data['id'], $newdata);
}

echo loadView('todo', ['newRecord' => $newRecord, 'isRemoved' => $isRemoved, 'tasks' => $table, 'error' => $error]);
