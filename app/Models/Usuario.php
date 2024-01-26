<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;
    protected $table = 'Usuario';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
        'apellidos',
        'correo',
        'telefono',
        'contraseña',
    ];

    public $timestamps = false;
}
