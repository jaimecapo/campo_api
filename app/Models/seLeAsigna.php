<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class seLeAsigna extends Model
{
    use HasFactory;
    protected $table = 'SeLeAsigna';

    public $timestamps = false;
    
    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id_actividad',
        'nif_trabajador',
    ];
}
