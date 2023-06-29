<?php

declare(strict_types=1);

namespace DataBase;

class FichierUnique implements \IteratorAggregate, \Countable
{
    protected string $name    = '';

    protected ?array $entries = null;

    public function __construct(string $name)
    {
        $this->name = $name;
        @touch(self::getPath($name));
    }

    public function getIDs(): array
    {
        return array_keys($this->getEntries());
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
        return in_array($id, $this->getIDs());
    }

    public function getRecords(): array
    {
        return array_values($this->getEntries());
    }

    public function getRecord(string $id): ?array
    {
        return $this->getEntries()[$id] ?? null;
    }

    public function updateRecord(string $id, array $record): bool
    {
        $save     = false;
        $entries  = $this->getEntries();

        $contents = [];

        foreach ($entries as $entry)
        {
            if ($entry['id'] === $id)
            {
                $entry               = array_replace($entry, $record);
                $entry['updated_at'] = formatTime(date_create('now'));

                $save                = true;

                $this->entries       = null;
            }

            $contents[] = serialize($entry);
        }

        return $save && @file_put_contents(self::getPath($this->name), implode("\n", $contents)) > 0;
    }

    public function addRecord(array $record): bool
    {
        $record['created_at'] = $record['updated_at'] = formatTime(date_create('now'));
        $record['id']         = uniqid();

        $contents             = $this->getContents();

        if ( ! empty($contents))
        {
            $contents .= "\n";
        }

        $contents .= serialize($record);

        return @file_put_contents(self::getPath($this->name), $contents) > 0;
    }

    public function removeRecord(string $id): bool
    {
        $save     = false;
        $entries  = $this->getEntries();

        $contents = [];

        foreach ($entries as $entry)
        {
            if ($entry['id'] === $id)
            {
                $save          = true;
                $this->entries = null;

                continue;
            }

            $contents[] = serialize($entry);
        }

        return $save && @file_put_contents(self::getPath($this->name), implode("\n", $contents)) > 0;
    }

    protected static function getPath(string $table): string
    {
        return DATA_DIR . DIRECTORY_SEPARATOR . $table . '.txt';
    }

    protected function getContents(): string
    {
        return @file_get_contents(self::getPath($this->name)) ?: '';
    }

    protected function getEntries()
    {
        if (is_null($this->entries))
        {
            $this->entries = [];
            $entries       = &$this->entries;
            $contents      = $this->getContents();

            foreach (explode("\n", $contents) as $line)
            {
                if (empty($line))
                {
                    continue;
                }
                $entry                 = unserialize($line);
                $entries[$entry['id']] = $entry;
            }
        }

        return $this->entries;
    }
}
