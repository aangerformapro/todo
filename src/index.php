<?php

declare(strict_types=1);

use DataBase\Table;

require_once dirname(__DIR__) . '/vendor/autoload.php';

require_once __DIR__ . '/constants.php';

$table = new Table('todo');

var_dump(mb_substr('cljdkfhdjhf.json', 0, -4));
