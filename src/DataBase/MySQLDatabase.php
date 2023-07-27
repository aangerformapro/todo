<?php

declare(strict_types=1);

namespace DataBase;

class MySQLDatabase implements \IteratorAggregate, \Countable
{
    protected string $name    = '';
    protected ?array $entries = null;
    protected \PDO $connection;

    public function __construct(string $name, $config)
    {
        $this->name       = $name;

        $this->connection = $this->connectDatabase($config);
        $this->createTable();
    }

    public function count(): int
    {
        return count($this->getIDs());
    }

    public function getIterator(): \Traversable
    {
        yield from $this->getRecords();
    }

    public function getIDs(): array
    {
        return array_keys($this->getEntries());
    }

    public function getRecords(): array
    {
        return array_values($this->getEntries());
    }

    public function getRecord($id): ?array
    {
        return $this->getEntries()[(int) $id] ?? null;
    }

    public function hasRecord(string $id): bool
    {
        return in_array($id, $this->getIDs());
    }

    public function addRecord(array $record): bool
    {
        $record['created_at'] = $record['updated_at'] = formatTimeSQL(date_create('now'));
    }

    protected function connectDatabase(array $config): \PDO
    {
        $pdo = new \PDO(
            sprintf(
                'mysql:host=localhost;dbname=%s',
                $config['db']
            ),
            $config['user'],
            $config['password']
        );

        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }

    protected function createTable()
    {
        $query = sprintf(
            'CREATE TABLE IF NOT EXISTS %s (' .
            'id int(11) NOT NULL AUTO_INCREMENT,' .
            'name varchar(255) NOT NULL,' .
            'description varchar(255) NOT NULL,' .
            'done tinyint(1) NOT NULL DEFAULT 0,' .
            'end_date datetime NOT NULL,' .
            'updated_at datetime NOT NULL,' .
            'created_at datetime NOT NULL,' .
            'PRIMARY KEY(id)' .
          ') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;',
            $this->name
        );

        $this->connection->exec($query);
    }

    protected function getEntries()
    {
        if (is_null($this->entries))
        {
            $this->entries = [];
            $entries       = &$this->entries;

            if (
                $stmt = $this->connection->query(sprintf('SELECT * FROM %s ORDER BY end_date ASC', $this->name), \PDO::FETCH_ASSOC)
            ) {
                foreach ($stmt as $item)
                {
                    foreach ($item as $row => &$value)
                    {
                        if (in_array($row, ['created_at', 'updated_at', 'end_date']))
                        {
                            $value = date_create($value);
                        } elseif ('done' === $row)
                        {
                            $value = (bool) $value;
                        }
                    }

                    $entries[$item['id']] = $item;
                }
            }
        }

        return $this->entries;
    }
}
