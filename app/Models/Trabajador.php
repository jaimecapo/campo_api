<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
    use HasFactory;
    protected $table = 'Trabajador';

    protected $primaryKey = 'nif';

    protected $casts = [
        'nif' => 'string', // Esto garantiza que siempre se maneje como string
    ];

    protected $fillable = [
        'nif',
        'nombre',
        'apellidos',
        'correo',
        'telefono',
        'puesto',
    ];

    public $timestamps = false;
}
