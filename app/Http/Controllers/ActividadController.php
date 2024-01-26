<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Campo;
use App\Models\Maquinaria;
use App\Models\seLeAsigna;
use App\Models\seUtilizaPara;
use App\Models\Trabajador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActividadController extends Controller
{
    public function actividades(Request $request){
        $response = ['status' => 0, 'msg' => '', 'code' => 200];
        $actividades = [];
        if($request->has("id_usuario")){
            if($request->has('id')){
                if(!is_numeric($request->id)) return response()->json(['status' => 0, 'msg' => 'Bad Paramethers', 'code' => 400]);
                $query= Actividad::where('id',$request->id)->where('id_usuario', $request->id_usuario)->first();
                if($query){
                    $seleasigna=seLeAsigna::where('id_actividad',$request->id)->get();
                    $trabajadores=[]; 
    
                    foreach($seleasigna as $asignado){
                        $trabajador=Trabajador::where('nif',$asignado->nif_trabajador)->first();
                        $trabajadores[]=$trabajador;
                    }
    
                    $seUtilizaPara=seUtilizaPara::where('id_actividad',$request->id)->get();
                    $utensilios=[]; 
    
                    $campo=Campo::where('id',$query->id_campo)->first();
    
                    foreach($seUtilizaPara as $utilizado){
                        $utensilio=Maquinaria::where('id',$utilizado->id_maquinaria)->first();
                        $utensilios[]=$utensilio;
                    }
                    $actividades[]=[
                        'id'=>$request->id, 
                        'titulo'=>$query->titulo, 
                        'descripcion'=>$query->descripcion, 
                        'fecha_inicio'=>$query->fecha_inicio,
                        'fecha_final'=>$query->fecha_final, 
                        'campo'=>$campo,
                        'trabajadores'=>$trabajadores,
                        'maquinaria'=>$utensilios,
                        'id_usuario'=>$query->id_usuario,

                    ];
                }
            }else{
                $queryActividades=Actividad::where('id_usuario', $request->id_usuario)->get(); 
                foreach($queryActividades as $resultActividad){
                    $seleasigna=seLeAsigna::where('id_actividad',$resultActividad->id)->get();
                    $trabajadores=[]; 
    
                    foreach($seleasigna as $asignado){
                        $trabajador=Trabajador::where('nif',$asignado->nif_trabajador)->first();
                        $trabajadores[]=$trabajador;
                    }
    
                    $seUtilizaPara=seUtilizaPara::where('id_actividad',$resultActividad->id)->get();
                    $utensilios=[]; 
    
                    $campo=Campo::where('id',$resultActividad->id_campo)->first();
                    foreach($seUtilizaPara as $utilizado){
                        $utensilio=Maquinaria::where('id',$utilizado->id_maquinaria)->first();
                        $utensilios[]=$utensilio;
                    }
                    $actividades[]=[
                        'id'=>$resultActividad->id, 
                        'titulo'=>$resultActividad->titulo, 
                        'descripcion'=>$resultActividad->descripcion, 
                        'fecha_inicio'=>$resultActividad->fecha_inicio,
                        'fecha_final'=>$resultActividad->fecha_final, 
                        'campo'=>$campo,
                        'trabajadores'=>$trabajadores,
                        'maquinaria'=>$utensilios,
                        'id_usuario'=>$resultActividad->id_usuario,

                    ];
                }
            }
        }else{return response()->json(['status' => 0, 'msg' => 'Bad Parameters', 'code' => 400]); }
        

        if ($actividades)   return response()->json($actividades);
        return response()->json($response);
    }

    public function añadirActividad(Request $request) {
        $response = ['status' => 0, 'msg' => 'Error al agregar una actividad'];
        try {
            if (
                $request->has('titulo') &&
                $request->has('descripcion') &&
                $request->has('fecha_inicio') &&
                $request->has('id_campo') &&
                $request->has('id_usuario') &&
                $request->has('fecha_final')
            ) {
                DB::table('Actividad')->insert([
                    'titulo' => $request->titulo,
                    'descripcion' => $request->descripcion,
                    'fecha_inicio' => $request->fecha_inicio,
                    'id_campo' => $request->id_campo,
                    'fecha_final' => $request->fecha_final,
                    'id_usuario' => $request->id_usuario,

                ]);
                if($request->has('nifs_trabajadores')){
                    $ultimaActividad = Actividad::orderBy('id', 'desc')->first();                    
                    foreach($request->nifs_trabajadores as $nif){
                        DB::table('SeLeAsigna')->insert([
                            'id_actividad'=>$ultimaActividad->id,
                            'nif_trabajador'=>$nif
                        ]);
                    }
                }
    
                if ($request->has('id_maquinarias')) {
                    $ultimaActividad = Actividad::orderBy('id', 'desc')->first();
                    foreach ($request->id_maquinarias as $id) {
                        DB::table('SeUtilizaPara')->insert([ 
                            'id_actividad' => $ultimaActividad->id,
                            'id_maquinaria' => $id
                        ]);
                    }
                }

                $response['status'] = 1;
                $response['msg'] = 'Actividad agregada correctamente';
                $response['code'] = 200;
            }
        } catch (\Exception $e) {
            $response['msg'] = $e->getMessage();
        }
    
        return response()->json($response);
    }
    
    public function borrarActividad(Request $request) {
        $response = ['status' => 0, 'msg' => 'Error al eliminar actividad'];
        try {
            if ($request->has('id')) {
                $exist = Actividad::where('id', $request->id)->first();
                if ($exist) {
                    DB::beginTransaction();
                    DB::table('SeUtilizaPara')->where('id_actividad', $request->id)->delete(); 
                    DB::table('SeLeAsigna')->where('id_actividad', $request->id)->delete();
                    DB::table('Actividad')->where('id', $request->id)->delete();
                    DB::commit();
                } else {
                    return response()->json(['status' => 0, 'msg' => 'La actividad no existe.']);
                }
    
                $response['status'] = 1;
                $response['msg'] = 'Actividad eliminada correctamente.';
                $response['code'] = 200;
            }
        } catch (\Exception $e) {
            $response['msg'] = $e->getMessage();
        }
    
        return response()->json($response);
    }
    

}
