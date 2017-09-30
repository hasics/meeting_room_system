<?php

namespace Laravel;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
  protected $table = 'events'; // you may change this to your name table
  public $timestamps = true; // set true if you are using created_at and updated_at
  protected $primaryKey = 'id'; // the default is id
}
