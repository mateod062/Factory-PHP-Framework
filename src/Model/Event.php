<?php

namespace Factory\PhpFramework\Model;

use Factory\PhpFramework\Traits\HasTimestamps;
use Factory\PhpFramework\Traits\SoftDeletes;

class Event extends Model
{
    use HasTimestamps, SoftDeletes;

    protected static string $table = 'event';
}