<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mariupol extends Model
{
    use HasFactory;
    protected $table = 'mariupol';
    protected $primaryKey = 'id';
    protected $guarded = []; 
    public $timestamps = false;
}
