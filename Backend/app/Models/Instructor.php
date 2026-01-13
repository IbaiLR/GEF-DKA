<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $table = "instructor";
    protected $primaryKey="ID_Usuario";

    public function instructor(){
        return $this->belongsTo(User::class,"ID_Usuario","ID");
    }
    public function tutores(){
        return $this->belongsToMany(Tutor::class,"tutor_instructor","ID_Instructor","ID_Tutor");
    }
    public function empresa(){
        return $this->belongsTo(Empresa::class,"CIF_Empresa","CIF");
    }
}
