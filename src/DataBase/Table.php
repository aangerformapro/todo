<?php

declare(strict_types=1);

namespace DataBase;

class Table implements \IteratorAggregate
{
    protected string $name = '';

    public function __construct(string $name)
    {
        $this->name = $name;

        @mkdir(self::getPath($name), 0777, true);
    }

    public function getIDs(): array
    {
        $dir   = self::getPath($this->name);
        $files = scandir($dir) ?: [];
        $files = array_filter($files, fn ($f) => str_ends_with($f, '.json'));

        return array_map(fn ($f) => mb_substr($f, 0, -5), $files);
    }

    public function getIterator(): \Traversable
    {
        yield from $this->getRecords();
    }

    public function getRecords(): array
    {
        $ids     = $this->getIDs();

        $results = [];

        foreach ($ids as $id)
        {
            $results[] = $this->getRecord($id);
        }

        return $results;
    }

    public function getRecord(string $id): ?array
    {
        $file = self::getPath($this->name, $id);

        if ( ! is_file($file))
        {
            return null;
        }

        return json_decode(file_get_contents($file), true);
    }

    public function updateRecord(string $id, array $record): bool
    {
        $file         = self::getPath($this->name, $id);
        $record['id'] = $id;
        return file_put_contents($file, json_encode($record)) > 0;
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
            $result .= DIRECTORY_SEPARATOR . "{$id}.json";
        }

        return $result;
    }
}
