<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cctv extends Model
{
    use HasFactory;

    protected $table = 'cctvs';
    protected $guarded = ['id'];

    public function fotos()
    {
        return $this->hasMany(Foto::class);
    }

    public function lokasi(): HasOne
    {
        return $this->hasOne(LokasiCctv::class);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function statuses()
    {
        return $this->hasMany(StatusCctv::class);
    }

    public function statusCctvTerbaru()
    {
        return $this->hasOne(StatusCctv::class)->latestOfMany();
    }
}
