<?php

declare(strict_types=1);

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

        var_dump($data);
        extract($data);

        include TEMPLATES . DIRECTORY_SEPARATOR . $view;
        chdir($cwd);
    }

    return ob_get_clean() ?: '';
}
