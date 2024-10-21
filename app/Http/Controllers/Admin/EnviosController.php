<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Models\Envios;
use App\Http\Models\Sucursales;
use App\Http\Models\Vehiculos;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use \PDF;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Models\Envio;



class EnviosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('isadmin');
        $this->middleware('user.status');
        $this->middleware('user.permissions'); 
    }
    //ENVIOS
    public function getEnvios(){
        $departamentos = DB::table('departamentos')->get();
        $sucursales = Sucursales::all();
        $vehiculos = Vehiculos::whereIn('estado',[0, 2])->get();
        $vehiculosEnEnvios = Vehiculos::join('envios', 'vehiculos.id', '=', 'envios.vehiculo_id')
                                        ->whereIn('envios.estado', [0, 1])
                                        ->distinct()
                                        ->get(['vehiculos.*']);

                                        $envios = Envios::all();

        $data = ['departamentos' => $departamentos,'sucursales' => $sucursales,'vehiculos' => $vehiculos,'vehiculosEnEnvios' => $vehiculosEnEnvios];
        return view('admin.envios.home',$data);
    }
    public function filterDepartamentoEnvios(Request $request){
        $provincias = DB::table('provincias')
                            ->where('department_id',$request->department_id)
                            ->get();
        if(count($provincias) > 0){
            return response()->json($provincias);
        }  
    }
    public function filterSucursalEnvios(Request $request){
        $sucursales = DB::table('sucursales')
                            ->where('provincia_id',$request->provincia_id)
                            ->get();
        if(count($sucursales) > 0){
            return response()->json($sucursales);
        }  
    }
    public function postEnvios(Request $request){ 
        $rules = [
            'sucursal_emisor_id' => 'required',
            'sucursal_receptor_id' => 'required',
            'dni_emisor' => 'required',
            'dni_receptor' => 'required',
            'contraseña' => 'required',
        ];
        $message = [
           'sucursal_emisor_id.required' => 'Se requiere sucursal de emisor',
           'sucursal_receptor_id.required' => 'Se requiere sucursal de receptor',
           'dni_emisor.required' => 'Se requiere el DNI del emisor',
           'dni_receptor.required' => 'Se requiere el DNI del receptor',
           'contraseña.required' => 'Se requiere una contraseña',
        ];
        $validator = Validator::make($request->all(), $rules,$message);
        if($validator->fails()):
            return back()->withErrors($validator)->with('message','Se ha producido un error:')->with('typealert','danger');
        else:
            $envios = new Envios();
            $envios-> codigo = Str::random(10);
            $envios-> sucursal_emisor_id = e($request->input('sucursal_emisor_id'));
            $envios-> sucursal_receptor_id = e($request->input('sucursal_receptor_id'));
            $envios-> name_emisor = e($request->input('name_emisor'));
            $envios-> dni_emisor = e($request->input('dni_emisor'));
            $envios-> name_receptor = e($request->input('name_receptor'));
            $envios-> dni_receptor = e($request->input('dni_receptor'));
            $envios-> peso = e($request->input('peso'));
            $envios-> precio = e($request->input('precio'));
            $envios-> vehiculo_id = e($request->input('vehiculo_id'));
            $envios-> fecha_salida = e($request->input('fecha_salida'));
            $envios-> contraseña = e($request->input('contraseña'));
            $envios-> fragil = request('fragil') ? 1 : 0;
            $envios-> estado = 0; 
            $envios->codigo_qr = $this->generarCodigoQrUnico();
            if($envios->save()):
                //HISTORIAL ENVIOS AGREGAR 
                $codigo = $envios->codigo;
                $user = Auth::user()->id;
                $data = array(["accion" => "Nuevo Envio","Estado" => "En Espera","name" =>  $codigo]);
                $audit = json_encode($data);
                $data_envio = array(["accion" => "Nuevo Envio","table" => "Envio","name" =>  $codigo]);
                $audit_envio = json_encode($data_envio);
                $date_zone = date('Y-m-d\TH:i');
                $codigoQr = QrCode::size(300)->generate($envios->codigo_qr);
                Storage::put('public/codigosqr/'.$envios->id.'.svg', $codigoQr);
                DB::insert('insert into historial_envio (user_id, accion,created_at) values (?, ?, ?)', [$user, $audit,$date_zone]);
                DB::insert('insert into auditoria (user_id, homework,created_at) values (?, ?, ?)', [$user, $audit_envio,$date_zone]);
                $codigoQr = QrCode::size(300)->generate(route('admin.envio.show', $envios->id));
                Storage::put('public/codigosqr/'.$envios->id.'.svg', $codigoQr);
                //HISTORIAL ENVIOS AGREGAR 
                return back()->with('message','Se Guardo con exito el envio')->with('typealert','info ');
            endif; 
        endif;
    }

    private function generarCodigoQrUnico()
{
    return uniqid('ENV-', true);
}
public function escanearQr($codigo_qr)
{
    $envio = Envios::where('codigo_qr', $codigo_qr)->firstOrFail();
    return view('admin.envios.escanear', compact('envio'));
}

    public function postEstadosEnvios(Request $request,$id){ 
        $vehiculo = Vehiculos::findOrFail($id);
        $vehiculo-> estado = e($request->input('estado'));
        // Obtenemos los envíos actualizados
        $enviosActualizados = Envios::where('vehiculo_id', $id)
        ->where('estado', '!=', 4)
        ->get();

        // Actualizamos el estado de los envíos
        Envios::where('vehiculo_id', $id)
        ->where('estado', '!=', 4)
        ->update(['estado' => $request->input('estado')]);

        if($vehiculo->save()):
           // HISTORIAL ENVIOS AGREGAR
            foreach ($enviosActualizados as $envio) {
                $codigo = $envio->codigo;
                $user = Auth::user()->id;
                if ($vehiculo-> estado == 0) {
                    $data = array(["accion" => "Envio", "Estado" => "En Espera", "name" => $codigo]);
                }elseif ($vehiculo-> estado  == 1) {
                    $data = array(["accion" => "Envio", "Estado" => "En Ruta", "name" => $codigo]);
                }elseif ($vehiculo-> estado  == 2) {
                    $data = array(["accion" => "Envio", "Estado" => "Ya Llego", "name" => $codigo]);
                }
                $audit = json_encode($data);
                $date_zone = date('Y-m-d\TH:i');
                DB::insert('insert into historial_envio (user_id, accion, created_at) values (?, ?, ?)', [$user, $audit, $date_zone]);
            }
            //HISTORIAL ENVIOS AGREGAR 
            return back()->with('message','Se Guardo con exito')->with('typealert','info ');
        endif;
    }

    public function show($id)
{
    $envio = Envios::findOrFail($id);
    return view('admin.envios.show', compact('envio'));
}
    
    public function getEnviosEdit($id){
        $departamentos = DB::table('departamentos')->get();
        $sucursales = Sucursales::all();
        $vehiculos = Vehiculos::whereIn('estado',[0, 2])->get();
        $envio = Envios::findOrFail($id);

        $data = ['departamentos' => $departamentos,'sucursales' => $sucursales,'vehiculos' => $vehiculos,'envio' => $envio];
        return view('admin.envios.edit',$data);
    }
    public function postEnviosEdit(Request $request,$id){ 
        $rules = [
            'sucursal_emisor_id' => 'required',
            'sucursal_receptor_id' => 'required',
            'dni_emisor' => 'required',
            'dni_receptor' => 'required',
            'contraseña' => 'required',
        ];
        $message = [
           'sucursal_emisor_id.required' => 'Se requiere sucursal de emisor',
           'sucursal_receptor_id.required' => 'Se requiere sucursal de receptor',
           'dni_emisor.required' => 'Se requiere el DNI del emisor',
           'dni_receptor.required' => 'Se requiere el DNI del receptor',
           'contraseña.required' => 'Se requiere una contraseña',
        ];
        $validator = Validator::make($request->all(), $rules,$message);
        if($validator->fails()):
            return back()->withErrors($validator)->with('message','Se ha producido un error:')->with('typealert','danger');
        else:
            $envio = Envios::findOrFail($id);
            $envio-> sucursal_emisor_id = e($request->input('sucursal_emisor_id'));
            $envio-> sucursal_receptor_id = e($request->input('sucursal_receptor_id'));
            $envio-> name_emisor = e($request->input('name_emisor'));
            $envio-> dni_emisor = e($request->input('dni_emisor'));
            $envio-> name_receptor = e($request->input('name_receptor'));
            $envio-> dni_receptor = e($request->input('dni_receptor'));
            $envio-> peso = e($request->input('peso'));
            $envio-> precio = e($request->input('precio'));
            $envio-> vehiculo_id = e($request->input('vehiculo_id'));
            $envio-> fecha_salida = e($request->input('fecha_salida'));
            $envio-> contraseña = e($request->input('contraseña'));
            $envio-> fragil = request('fragil') ? 1 : 0;
            if($envio->save()):
                //HISTORIAL ENVIOS AGREGAR 
                $codigo = $envio->codigo;
                $user = Auth::user()->id;
                $data = array(["accion" => "Edito Envio","Estado" => "En Espera","name" =>  $codigo]);
                $audit = json_encode($data);
                $data_envio = array(["accion" => "Edito Envio","table" => "Envio","name" =>  $codigo]);
                $audit_envio = json_encode($data_envio);
                $date_zone = date('Y-m-d\TH:i');
                DB::insert('insert into historial_envio (user_id, accion,created_at) values (?, ?, ?)', [$user, $audit,$date_zone]);
                DB::insert('insert into auditoria (user_id, homework,created_at) values (?, ?, ?)', [$user, $audit_envio,$date_zone]);
                //HISTORIAL ENVIOS AGREGAR 
                return back()->with('message','Se Edito con exito el envio')->with('typealert','info ');
            endif; 
        endif;
    }

    public function generatePDF($id){
        $envio = Envios::findOrFail($id);
        $pdf = FacadePdf::loadView('admin.envios.vista',['envio' => $envio])->setPaper('8.5x14');
        return $pdf->stream('Clientes.pdf');
    }

    //RECEPCION
    public function getRecepcion(){
        $envios = Envios::whereIn('estado',[2,3])->orderby('estado', 'desc')->get();
        $data = ['envios' => $envios];
        return view('admin.recepcion.home',$data);
    }
    public function getStadoRecepcion($id){
        $envios = Envios::findOrFail($id);
        if($envios->estado == "2"):
           $envios->estado = "3";
           $msg = "Entregado";
        else:
           $envios->estado = "2";
           $msg = "Ya Llego"; 
        endif;
        if($envios->save()):
            if($envios->estado == "2"):
               //HISTORIAL ENVIOS AGREGAR 
               $codigo = $envios->codigo;
               $user = Auth::user()->id;
               $data = array(["accion" => "Envio","Estado" => "Ya Llego","name" =>  $codigo]);
               $audit = json_encode($data);
               $date_zone = date('Y-m-d\TH:i');
               DB::insert('insert into historial_envio (user_id, accion,created_at) values (?, ?, ?)', [$user, $audit,$date_zone]);
               return back()->with('message', $msg)->with('typealert','warning');
               //HISTORIAL ENVIOS AGREGAR 
            else:
               //HISTORIAL ENVIOS AGREGAR 
               $codigo = $envios->codigo;
               $user = Auth::user()->id;
               $data = array(["accion" => "Envio","Estado" => "Entregado","name" =>  $codigo]);
               $audit = json_encode($data);
               $date_zone = date('Y-m-d\TH:i');
               DB::insert('insert into historial_envio (user_id, accion,created_at) values (?, ?, ?)', [$user, $audit,$date_zone]);
               //HISTORIAL ENVIOS AGREGAR 
            endif;
           return back()->with('message',$msg)->with('typealert','warning');
        endif;
    } 
}
