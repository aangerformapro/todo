<?php

declare(strict_types=1);

use DataBase\MySQLDatabase;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$table  = new MySQLDatabase(
    'todo',
    include __DIR__ . '/config.php'
);

function render($data)
{
    $resp = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Length: ' . strlen($resp));
    echo $resp;

    exit;
}

if ('GET' === $method)
{
    $action = $_GET['action'] ?? 'all';

    switch ($action)
    {
        case 'all':
            $data = $table->getRecords();
            break;

        case 'last':
            if (is_numeric($_GET['id'] ?? ''))
            {
                $id   = $_GET['id'];
                $data = $table->getLastRecords($id);
                break;
            }

            // no break
        default:
            http_response_code(400);
            $data = [
                'code'  => 400,
                'error' => 'Bad Request',
            ];
    }

    render($data);
} elseif ('POST' === $method)
{
    $action = $_GET['action'] ?? '';

    switch ($action)
    {
        case 'add':

            $record = getPostdata(['name', 'description', 'end_date']);

            if (
                3 === count(array_filter($record, fn ($str) => null !== $str))
            ) {
                $record['done'] = false;

                if ($table->addRecord($record))
                {
                    $data = ['result' => 'ok'];
                    break;
                }
            }

            // no break
        default:
            http_response_code(400);
            $data   = [
                'code'  => 400,
                'error' => 'Bad Request',
            ];
    }
    render($data);
}
