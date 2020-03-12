<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /**
     * This attributes are fill via request
     * @var array
     */
    protected $fillable = [
        'title',
        'code',
        'description'
    ];
}
