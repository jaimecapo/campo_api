<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campo extends Model
{
    use HasFactory;

    protected $table = 'Campo'; 

    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
        'provincia',
        'municipio',
        'agregado',
        'zona',
        'poligono',
        'parcela',
        'recinto',
    ];

    public $timestamps = false;}
