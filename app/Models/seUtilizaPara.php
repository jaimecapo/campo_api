<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class seUtilizaPara extends Model
{
    use HasFactory;
    protected $table = 'SeUtilizaPara';

    public $timestamps = false;
    
    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id_actividad',
        'id_maquinaria',
    ];
}
