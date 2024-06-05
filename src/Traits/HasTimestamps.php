<?php

namespace Factory\PhpFramework\Traits;

trait HasTimestamps
{
    /**
     * Update the timestamps for the model
     *
     * @return void
     */
    protected function updateTimestamps(): void
    {
        $now = date('Y-m-d H:i:s');
        if (!$this->exists && !isset($this->fields['created_at'])) {
            $this->fields['created_at'] = $now;
        }
        $this->fields['updated_at'] = $now;
    }

    /**
     * Save the model to the database with timestamps
     *
     * @return void
     */
    public function save(): void
    {
        $this->updateTimestamps();
        parent::save();
    }

    /**
     * Update the model in the database with timestamps
     *
     * @return void
     */
    public function update(): void
    {
        $this->fields['updated_at'] = date('Y-m-d H:i:s');
        parent::update();
    }
}