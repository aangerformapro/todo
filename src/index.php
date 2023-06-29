<?php

declare(strict_types=1);

use DataBase\FichierUnique;

require_once dirname(__DIR__) . '/vendor/autoload.php';

session_start();

$table     = new FichierUnique('todo');

$newRecord = $_SESSION['newRecord'] ??= false;
$modRecord = $_SESSION['modRecord'] ??= false;
$isRemoved = $_SESSION['isRemoved'] ??= false;
$error     = $_SESSION['error']     ??= null;
$inputdata = [];

if ($newRecord || $isRemoved || $error)
{
    unset($_SESSION['isRemoved'], $_SESSION['error'], $_SESSION['newRecord'], $_SESSION['modRecord']);
}

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
        $inputdata = $table->getRecord($id) ?? [];
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
