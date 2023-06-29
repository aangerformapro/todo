<?php

declare(strict_types=1);

namespace DataBase;

class Table implements \IteratorAggregate, \Countable
{
    protected string $name = '';

    public function __construct(string $name)
    {
        $this->name = $name;

        @mkdir(self::getPath($name), 0777, true);
    }

    public function getIDs(): array
    {
        $ids   = [];
        $dir   = self::getPath($this->name);
        $files = scandir($dir) ?: [];
        $files = array_filter($files, fn ($f) => str_ends_with($f, '.json'));

        foreach ($files as $file)
        {
            $path  = $dir . DIRECTORY_SEPARATOR . $file;

            $id    = mb_substr($file, 0, -5);

            $ctime = @filectime($path);

            if ( ! $ctime)
            {
                $ids[] = $id;
            } else
            {
                $ids[$ctime] = $id;
            }
        }

        ksort($ids);
        return array_values($ids);
    }

    public function count(): int
    {
        return count($this->getIDs());
    }

    public function getIterator(): \Traversable
    {
        yield from $this->getRecords();
    }

    public function hasRecord(string $id): bool
    {
        return is_file(self::getPath($this->name, $id));
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
        if ( ! $this->hasRecord($id))
        {
            return false;
        }
        $old                     = $this->getRecord($id);
        $file                    = self::getPath($this->name, $id);
        $newRecord               = array_replace($old, $record);
        $newRecord['updated_at'] = formatTime(date_create('now'));
        return @file_put_contents($file, json_encode($newRecord)) > 0;
    }

    public function addRecord(array $record): bool
    {
        $record['created_at'] = $record['updated_at'] = formatTime(date_create('now'));
        $id                   = $record['id'] = uniqid();

        return
               @file_put_contents(
                   self::getPath($this->name, $id),
                   json_encode($record)
               ) > 0;
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
