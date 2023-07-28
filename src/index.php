<?php

declare(strict_types=1);

use DataBase\MySQLDatabase;

require_once dirname(__DIR__) . '/vendor/autoload.php';

session_start();

$table     = new MySQLDatabase(
    'todo',
    include __DIR__ . '/config.php'
);

$newRecord = $_SESSION['newRecord'] ??= false;
$modRecord = $_SESSION['modRecord'] ??= false;
$isRemoved = $_SESSION['isRemoved'] ??= false;
$error     = $_SESSION['error']     ??= null;
$inputdata = $_SESSION['inputdata'] ??= [];

unset(
    $_SESSION['isRemoved'],
    $_SESSION['error'],
    $_SESSION['newRecord'],
    $_SESSION['modRecord'],
    $_SESSION['inputdata']
);

$now       = date_create('now');

$action    = getPostdata(['action'])['action'];

if ('add' === $action)
{
    $data = getPostdata(['name', 'description', 'end_date']);

    foreach ($data as $value)
    {
        if (null === $value)
        {
            $_SESSION['error'] = $error = 'Tous les champs n\' ont pas été remplis';
            break;
        }
    }

    if (isExpired($data['end_date']))
    {
        $_SESSION['error'] = $error = 'Vous ne pouvez pas créer une tâche à effectuer dans le passé !!!';
    }

    if ( ! $error)
    {
        $data['done']          = false;
        $_SESSION['newRecord'] = $newRecord = $table->addRecord($data);

        header('Location: ./');
    }
}

if ('edit' === $action)
{
    if ($id = getPostdata(['id'])['id'])
    {
        $newdata = getPostdata(['name', 'description', 'end_date']);

        if (isExpired($newdata['end_date']))
        {
            $_SESSION['inputdata'] = $table->getRecord($id) ?? [];

            $_SESSION['error']     = $error = 'Vous ne pouvez pas créer une tâche à effectuer dans le passé !!!';
            header('Location: ./');

            exit;
        }

        if ($_SESSION['modRecord'] = $table->updateRecord($id, $newdata))
        {
            header('Location: ./');
        }
    }
}

if ('edit_entry' === $action)
{
    if ($id = getPostdata(['id'])['id'])
    {
        $_SESSION['inputdata'] = $table->getRecord($id) ?? [];
        header('Location: ./');
    }
}

if ('update' === $action)
{
    $data    = getPostdata(['id', 'done']);

    $newdata = ['done' => 'on' === $data['done']];

    if ($table->updateRecord($data['id'], $newdata))
    {
        header('Location: ./');
    }
}

if ('delete' === $action)
{
    $id = getPostdata(['id'])['id'];

    if ($id)
    {
        $isRemoved = $table->removeRecord($id);
    }

    if ($_SESSION['isRemoved'] = $isRemoved)
    {
        header('Location: ./');
    }
}

echo loadView('todo', [
    'modRecord' => $modRecord,
    'inputdata' => $inputdata,
    'newRecord' => $newRecord,
    'isRemoved' => $isRemoved,
    'tasks'     => $table,
    'error'     => $error,
]);
