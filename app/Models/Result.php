<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;


    protected $fillable = [
        "casa_id",
        "gols_casa",
        "fora_id",
        "gols_fora",
        "penaltis_casa",
        "penaltis_fora",
    ];

    public function usuario_casa()
    {
        return $this->belongsTo(Club::class, "casa_id", "id");
    }

    public function usuario_fora()
    {
        return $this->belongsTo(Club::class, "fora_id", "id");
    }
}
