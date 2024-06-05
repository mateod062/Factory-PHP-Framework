<?php

namespace Factory\PhpFramework\Traits;

use Factory\PhpFramework\Database\Connection;

trait SoftDeletes
{
    /**
     * Delete the model from the database
     *
     * @return void
     */
    public function delete(): void
    {
        $db = Connection::getInstance();
        $db->delete(static::$table, [static::$primaryKey => $this->fields[static::$primaryKey]]);
    }

    /**
     * Soft delete the model from the database
     *
     * @return void
     */
    public function softDelete(): void
    {
        $this->fields['deleted_at'] = date('Y-m-d H:i:s');
        $this->update();
    }
}