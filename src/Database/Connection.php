<?php

namespace Factory\PhpFramework\Database;

use Dotenv\Dotenv;
use PDO;
use PDOException;
use PDOStatement;

class Connection
{
    private static ?Connection $instance = null;
    private PDO $connection;

    private function __construct()
    {
        try {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
            $dotenv->load();

            $host = $_ENV['DB_HOST'];
            $dbname = $_ENV['DB_NAME'];
            $user = $_ENV['DB_USER'];
            $password = $_ENV['DB_PASSWORD'];

            $this->connection = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $user, $password);
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
     * Execute a SELECT query and return the first row with parameters
     * as either an associative or a numeric array
     *
     * @param string $query The SQL query
     * @param array $params The query parameters
     * @return mixed
     */
    public function fetchAssoc(string $query, array $params = []): mixed
    {
        $stmt = $this->connection->prepare($query);

        $stmt->execute($params);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Execute a SELECT query and return all rows with parameters
     * as either an array of associative or a numeric arrays
     *
     * @param string $query The SQL query
     * @param array $params The query parameters
     * @return bool|array
     */
    public function fetchAssocAll(string $query, array $params = []): bool|array
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);


        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        if (!is_array(reset($data))) {
            // Single row insert
            return $this->insertSingle($table, $data);
        } else {
            // Batch insert
            return $this->insertBatch($table, $data);
        }
    }

    /**
     * Helper function to insert a single row in table
     *
     * @param string $table The table name
     * @param array $data The data to insert
     * @return false|string
     */
    private function insertSingle(string $table, array $data): false|string
    {
        $columns = implode(", ", array_keys($data));
        $values = implode(", ", array_fill(0, count($data), '?'));

        $query = "INSERT INTO $table ($columns) VALUES ($values)";

        $stmt = $this->connection->prepare($query);
        $stmt->execute(array_values($data));

        return $this->connection->lastInsertId();
    }

    /**
     * Helper function to insert multiple rows in table
     *
     * @param string $table The table name
     * @param array $data The data to insert
     * @return int Number of rows affected
     */
    private function insertBatch(string $table, array $data): int
    {
        $columns = implode(", ", array_keys($data[0]));
        $values = '(' . implode(", ", array_fill(0, count($data[0]), '?')) . ')';
        $valuesPlaceholder = implode(', ', array_fill(0, count($data), $values));

        $query = "INSERT INTO $table ($columns) VALUES $valuesPlaceholder";

        $stmt = $this->connection->prepare($query);

        $params = [];
        foreach ($data as $row) {
            $params = array_merge($params, array_values($row));
        }

        $stmt->execute($params);

        return $stmt->rowCount();
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