<?php

namespace Laravel;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books'; // you may change this to your name table
    public $timestamps = true; // set true if you are using created_at and updated_at
    protected $primaryKey = 'id'; // the default is id
}
