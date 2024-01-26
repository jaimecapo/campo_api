<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maquinaria extends Model
{
    use HasFactory;
    protected $table = 'Maquinaria';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
        'marca',
        'modelo',
        'matricula',
        'activa',
        'alquilada',
        'tipo',
        'adquisicion',
        'ultima_revision',
        'capacidad',
    ];

    public $timestamps = false;
}
