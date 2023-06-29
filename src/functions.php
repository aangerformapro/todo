<?php

declare(strict_types=1);

use voku\helper\AntiXSS;

require_once __DIR__ . '/constants.php';

function loadView(string $view, array $data = []): string
{
    ob_start();

    $cwd  = getcwd();

    if ( ! str_ends_with($view, '.php'))
    {
        $view .= '.php';
    }

    $file = TEMPLATES . DIRECTORY_SEPARATOR . $view;

    if (is_file(TEMPLATES . DIRECTORY_SEPARATOR . $view))
    {
        chdir(dirname($file));
        extract($data);

        include $view;
        chdir($cwd);
    }

    return ob_get_clean() ?: '';
}

function formatTime(DateTime $date): string
{
    return $date->format('Y-m-d') . 'T' . $date->format('H:i');
}

function getPostdata(array $keys = []): array
{
    static $xss;
    $xss ??= new AntiXSS();

    $values = [];

    if ( ! count($keys))
    {
        $keys = array_keys($_POST);
    }

    foreach ($keys as $key)
    {
        if ( ! isset($_POST[$key]))
        {
            $values[$key] = null;
            continue;
        }
        $values[$key] = $xss->xss_clean($_POST[$key]);
    }

    return $values;
}

function isExpired(string $date)
{
    return date_create('now')->getTimestamp() > date_create($date)->getTimestamp();
}
