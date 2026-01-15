<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ra extends Model
{
    protected $table="ra";
    protected $incrementing=false;
    protected $fillable=[
        "Descripcion",
        "ID_Asignatura"
    ];

    public function asignatura(){
        return $this-> belongsTo(Asignatura::class, 'ID_Asignatura');
    }

    public function compRa(){
        return $this->hasOne(compRa::class,"comp_ra","ID_Ra");
    }

}
