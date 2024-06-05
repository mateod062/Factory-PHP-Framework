<?php

namespace Factory\PhpFramework\Model;

use Factory\PhpFramework\Database\Connection;

abstract class Model
{
    protected static string $table;
    protected static string $primaryKey = 'id';
    protected array $fields = [];
    protected bool $exists = false;

    public function __construct(array $fields = [])
    {
        $this->fields = $fields;
    }

    public function __get(string $name): mixed
    {
        return $this->fields[$name] ?? null;
    }

    public function __set(string $name, mixed $value): void
    {
        $this->fields[$name] = $value;
    }

    /**
     * Save the model to the database
     *
     * @return void
     */
    public function save(): void
    {
        $db = Connection::getInstance();
        if ($this->exists) {
            $this->update();
        } else {
            $db->insert(static::$table, $this->fields);
            $this->{static::$primaryKey} = $db->getConnection()->lastInsertId();
            $this->exists = true;
        }
    }

    /**
     * Update the model in the database
     *
     * @return void
     */
    public function update(): void
    {
        $db = Connection::getInstance();
        $db->update(static::$table, $this->fields, [static::$primaryKey => $this->fields[static::$primaryKey]]);
    }

    /**
     * Delete the model from the database
     *
     * @param $primaryKeyValue
     * @return Model|null
     */
    public static function find($primaryKeyValue): ?self
    {
        $db = Connection::getInstance();
        $result = $db->fetchAssoc(
            "SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = :id",
            ['id' => $primaryKeyValue]
        );

        if ($result) {
            $instance = new static($result);
            $instance->exists = true;
            return $instance;
        }

        return null;
    }

    /**
     * Get all models from the database
     *
     * @return array
     */
    public static function all(): array
    {
        $db = Connection::getInstance();
        $results = $db->fetchAssocAll("SELECT * FROM " . static::$table);
        $instances = [];
        foreach ($results as $result) {
            $instance = new static($result);
            $instance->exists = true;
            $instances[] = $instance;
        }
        return $instances;
    }

    /**
     * Convert the model to an array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->fields;
    }
}