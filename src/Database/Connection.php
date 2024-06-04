<?php

namespace Factory\PhpFramework\Database;

use PDO;
use PDOException;

class Connection
{
    private static ?Connection $instance = null;
    private PDO $connection;

    private function __construct()
    {
        try {
            $this->connection = new PDO('mysql:host=localhost;dbname=php_framework', 'root', '');
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Return the instance of the Connection singleton
     *
     * @return Connection|null
     */
    public static function getInstance(): ?Connection
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get the PDO connection
     *
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * Execute a SELECT query and return the first row as an either associative or numeric array
     *
     * @param string $query The SQL query
     * @param array $params The query parameters
     * @return bool
     */
    public function fetchAssoc(string $query, array $params = []): bool
    {
        $stmt = $this->connection->prepare($query);

        foreach ($params as $key => $value) {
            $placeholder = array_is_list($params) ? "$key =?" : "?";
            $stmt->bindValue($placeholder, $value);
        }

        $stmt->execute();

        return array_is_list($params) ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetch(PDO::FETCH_NUM);
    }

    /**
     * Execute a SELECT query and return all rows as an array of either associative or numeric arrays
     *
     * @param string $query The SQL query
     * @param array $params The query parameters
     * @return bool|array
     */
    public function fetchAssocAll(string $query, array $params = []): bool|array
    {
        $stmt = $this->connection->prepare($query);

        foreach ($params as $key => $value) {
            $placeholder = array_is_list($params) ? "$key =?" : "?";
            $stmt->bindValue($placeholder, $value);
        }

        $stmt->execute();

        return array_is_list($params) ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_NUM);
    }

    /**
     * Execute an INSERT query with an associative array
     *
     * @param string $table The table name
     * @param array $data The data to insert
     * @return bool
     */
    public function insert(string $table, array $data): bool
    {
        $columns = implode(', ', array_keys(reset($data)));
        $values = implode(', ', array_fill(0, count(reset($data)), '?'));

        $query = "INSERT INTO $table ($columns) VALUES ($values)";

        $stmt = $this->connection->prepare($query);

        if (is_array($data)) {
            foreach ($data as $row) {
                $stmt->execute(array_values($row));
            }
            return true;
        }

        return $stmt->execute(array_values($data));
    }

    /**
     * Execute an UPDATE query with an associative array
     *
     * @param string $table The table name
     * @param array $data The data to update
     * @param array $conditions The conditions to match
     * @return bool
     */
    public function update(string $table, array $data, array $conditions): bool
    {
        $setClause = implode(', ', array_map(fn($key) => "$key = ?", array_keys($data)));
        $conditionClause = implode(' AND ', array_map(fn($key) => "$key = ?", array_keys($conditions)));

        $query = "UPDATE $table SET $setClause WHERE $conditionClause";

        $stmt = $this->connection->prepare($query);

        return $stmt->execute(array_merge(array_values($data), array_values($conditions)));
    }
}