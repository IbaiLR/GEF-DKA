<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompRa extends Model
{
    protected $table="comp_ra";


    public function competencia(){
        $this->belongTo(Competencia::class,"ID_Comp");
    }
    public function ra(){
        $this->belongTo(Ra::class,"ID_Ra");
    }
}
