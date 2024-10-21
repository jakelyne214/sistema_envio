<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Anuncios extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'anuncios';
    protected $hidden = ['created_at','updated_at'];

    public function vehi(){
        return $this->hasOne(Vehiculos::class,'id','vehiculo_id'); 
    }

    public function suc(){
        return $this->hasOne(Sucursales::class,'id','sucursal_id');
    }
}
