<?php

declare(strict_types=1);

namespace DataBase;

class Table
{
    protected string $name = '';

    protected string $dir  = '';

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->dir  = DATA_DIR . DIRECTORY_SEPARATOR . $name;
    }

    public function getIDs(): array
    {
        $dir   = self::getPath($this->name);
        $files = scandir($dir) ?: [];
        $files = array_filter($files, fn ($f) => str_ends_with($f, '.json'));

        return array_map(fn ($f) => mb_substr($f, 0, -5), $files);
    }

    public function getRecord(string $id): ?array
    {
        $file = self::getPath($this->name, $id);

        if ( ! is_file($file))
        {
            return null;
        }

        return json_decode(file_get_contents($file));
    }

    public function updateRecord(string $id, array $record): bool
    {
        $file         = self::getPath($this->name, $id);
        $record['id'] = $id;
        return file_put_contents(json_encode($record), $file) > 0;
    }

    public function addRecord(array $record): bool
    {
        return $this->updateRecord(uniqid(), $record);
    }

    public function removeRecord(string $id): bool
    {
        $file = self::getPath($this->name, $id);

        return ! is_file($file) || @unlink($file);
    }

    protected static function getPath(string $table, ?string $id = null): string
    {
        $result = DATA_DIR . DIRECTORY_SEPARATOR . $table;

        if ( ! is_null($id))
        {
            $result .= "{$id}.json";
        }

        return $result;
    }
}

$a = new Table('table');

var_dump($a->getRecord('dffjkdjfdjk'));
