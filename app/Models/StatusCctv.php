<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusCctv extends Model
{
    use HasFactory;

    protected $table = 'status_cctvs';
    protected $guarded = ['id'];
}
