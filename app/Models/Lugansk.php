<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lugansk extends Model
{
    use HasFactory;
    protected $table = 'lugansk';
    protected $primaryKey = 'id';
    protected $guarded = []; 
    public $timestamps = false;
}
