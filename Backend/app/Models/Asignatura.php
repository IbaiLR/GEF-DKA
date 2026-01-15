<?php

namespace App\Models;

use Dom\Notation;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\CssSelector\Node\FunctionNode;

class Asignatura extends Model
{
    protected $table = 'asignatura';

    protected $fillable = [
        'Nombre',
        'ID_Grado'
    ];

    public function grado(){
        return $this-> belongsTo(Grado::class, 'ID_Grado');
    }

    public function ras(){
        return $this -> hasMany(Ra::class, 'ID_Asignatura')
    }
    public function compsRa(){
        return $this -> hasMany(CompRa::class, 'ID_Grado');
    }

    public function notaEgibide(){
        return $this -> hasOne(NotaEgibide::class, 'ID_Asignatura');
    }
}
