<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaEgibide extends Model
{
    protected $table = "nota_egibide";
    protected $fillable =[
        'ID_Asignatura',
        'ID_Alumno',
        'nota'
    ];
    
    public function asignatura(){
        return $this->belongsTo(Asignatura::class,'ID_Asignatura','id');
    }
    public function alumno(){
        return $this->belongsTo(Alumno::class,'ID_Alumno','id');
    }
}
