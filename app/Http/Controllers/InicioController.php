<?php

namespace App\Http\Controllers;

use App\Http\Models\Anuncios;
use App\Http\Models\Historial_envio;
use App\Http\Models\Sucursales;
use Illuminate\Http\Request;

class InicioController extends Controller
{
    public function getInicio(){
        $anuncios = Anuncios::all();
        $sucursales = Sucursales::all();
        $data = ['anuncios' => $anuncios,'sucursales' => $sucursales];
        return view('welcome',$data);
    }
    public function getConsulta(Request $request){
        $name = $request->get('name');
        if ($name !== null && $name !== '' && $name !== '0') {
            $query = Historial_envio::query();
            if ($name != null) {
                $query->whereJsonContains('accion', ['name' => $name]);
            }
            $auditoria = $query->get();
        } else {
            $auditoria = [];
        }
        //dd($auditoria); 
        $data['auditoria'] = $auditoria;
        return view('consulta',$data);
    }
    
}
