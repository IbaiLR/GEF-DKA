<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaCuaderno extends Model
{
    protected $table = 'notas_cuaderno';

    protected $fillable = [
        'ID_Alumno',
        'Fecha',
        'Nota'
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'ID_Usuario','ID_Alumno');
    }
}
