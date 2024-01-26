<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Maquinaria;
use App\Models\seUtilizaPara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaquinariaController extends Controller
{
    public function maquinas(Request $request)
    {
        $response = ['status' => 0, 'msg' => '', 'code' => 200];
        $maquinas = [];

        if ($request->has('id_usuario')) {
            $idUsuario = $request->id_usuario;

            if ($request->has('id')) {
                if (!is_numeric($request->id)) {
                    return response()->json(['status' => 0, 'msg' => 'Bad parameters', 'code' => 400]);
                }

                $query = Maquinaria::where('id', $request->id)
                    ->where('id_usuario', $idUsuario)
                    ->first();

                if ($query) {
                    $activities = [];
                    $queryActivities = SeUtilizaPara::where('id_maquinaria', $query['id'])->get();

                    foreach ($queryActivities as $seUtiliza) {
                        $activity = Actividad::where('id', $seUtiliza['id_actividad'])->first();
                        $activities[] = [
                            'id' => $activity->id,
                            'titulo' => $activity->titulo,
                            'descripcion' => $activity->descripcion,
                            'fecha_inicio' => $activity->fecha_inicio,
                            'fecha_final' => $activity->fecha_final,
                            'id_campo' => $activity->id_campo,
                        ];
                    }

                    $maquinas[] = [
                        'id' => $query['id'],
                        'nombre' => $query['nombre'],
                        'marca' => $query['marca'],
                        'modelo' => $query['modelo'],
                        'matricula' => $query['matricula'],
                        'activa' => $query['activa'],
                        'alquilada' => $query['alquilada'],
                        'tipo' => $query['tipo'],
                        'adquisicion' => $query['adquisicion'],
                        'ultima_revision' => $query['ultima_revision'],
                        'capacidad' => $query['capacidad'],
                        'id_usuario' => $query['id_usuario'],
                        'seUtilizaPara' => $activities,
                    ];
                }
            } else {
                $entrys = Maquinaria::where("id_usuario", $idUsuario)->get();

                foreach ($entrys as $maquina) {
                    $activities = [];
                    $queryActivities = SeUtilizaPara::where('id_maquinaria', $maquina['id'])->get();

                    foreach ($queryActivities as $seUtiliza) {
                        $activity = Actividad::where('id', $seUtiliza['id_actividad'])->first();
                        $activities[] = [
                            'id' => $activity->id,
                            'titulo' => $activity->titulo,
                            'descripcion' => $activity->descripcion,
                            'fecha_inicio' => $activity->fecha_inicio,
                            'fecha_final' => $activity->fecha_final,
                            'id_campo' => $activity->id_campo,
                        ];
                    }

                    $maquinas[] = [
                        'id' => $maquina['id'],
                        'nombre' => $maquina['nombre'],
                        'marca' => $maquina['marca'],
                        'modelo' => $maquina['modelo'],
                        'matricula' => $maquina['matricula'],
                        'activa' => $maquina['activa'],
                        'alquilada' => $maquina['alquilada'],
                        'tipo' => $maquina['tipo'],
                        'adquisicion' => $maquina['adquisicion'],
                        'ultima_revision' => $maquina['ultima_revision'],
                        'capacidad' => $maquina['capacidad'],
                        'id_usuario' => $maquina['id_usuario'],
                        'seUtilizaPara' => $activities,
                    ];
                }
            }
        } else {
            return response()->json(['status' => 0, 'msg' => 'Bad Parameters', 'code' => 400]);
        }

        if ($maquinas) {
            return response()->json($maquinas);
        } else {
            return response()->json($response);
        }
    }


    public function añadirMaquina(Request $request){
        $response = ['status' => 0, 'msg' => 'Error al agregar una maquina'];
        try {
            if (
            $request->has('nombre') &&
            $request->has('marca') &&
            $request->has('modelo') &&
            $request->has('matricula') &&
            $request->has('activa') &&
            $request->has('alquilada') &&
            $request->has('tipo') &&
            $request->has('adquisicion') &&
            $request->has('ultima_revision') &&
            $request->has('id_usuario') &&
            $request->has('capacidad')) {
                $exist = Maquinaria::where('matricula', $request->matricula)->first();
                if ($exist) return response()->json($response);

                DB::table('Maquinaria')->insert([
                    'nombre' => $request->nombre,
                    'marca' => $request->marca,
                    'modelo' => $request->modelo,
                    'matricula' => $request->matricula,
                    'activa' => $request->activa,
                    'alquilada' => $request->alquilada,
                    'tipo' => $request->tipo,
                    'adquisicion' => $request->adquisicion,
                    'ultima_revision' => $request->ultima_revision,
                    'capacidad' => $request->capacidad,
                    'id_usuario' => $request->id_usuario,

                ]);
                $response['status'] = 1;
                $response['msg'] = 'Maquina agregada correctamente';
                $response['code'] = 200;
            }
        } catch (\Exception $e) {
            $response['msg'] = $e->getMessage();
        }

        return response()->json($response);
    }

    public function borrarMaquina(Request $request){
        $response = ['status' => 0, 'msg' => 'Error al eliminar maquina.'];
        try {
            if ($request->has('id')) {
                $exist = Maquinaria::where('id', $request->id)->first();
                if ($exist) {
                    DB::beginTransaction();
                    DB::table('SeUtilizaPara')->where('id_maquinaria', $request->id)->delete(); 
                    DB::table('Maquinaria')->where('id', $request->id)->delete();
                    DB::commit();
                } else return response()->json(['status' => 0, 'msg' => 'La maquina no existe.']);


                $response['status'] = 1;
                $response['msg'] = 'Maquina eliminada correctamente.';
                $response['code'] = 200;
            }
        } catch (\Exception $e) {
            $response['msg'] = $e->getMessage();
        }

        return response()->json($response);
    }

    public function editarMaquina(Request $request){
        $response = ['status' => 0, 'msg' => 'Failed'];

        try {
            DB::table('Maquinaria')
                ->where('id', $request->id)
                ->update([
                    'nombre' => $request->nombre,
                    'marca' => $request->marca,
                    'modelo' => $request->modelo,
                    'matricula' => $request->matricula,
                    'activa' => $request->activa,
                    'alquilada' => $request->alquilada,
                    'tipo' => $request->tipo,
                    'adquisicion' => $request->adquisicion,
                    'ultima_revision' => $request->ultima_revision,
                    'capacidad' => $request->capacidad,
                    "id_usuario"=>$request->id_usuario,
                ]);

            $response['status'] = 1;
            $response['msg'] = 'Ok';
            $response['code'] = 200;
        } catch (\Exception $e) {
            $response['msg'] = $e->getMessage();
        }

        return response()->json($response);
    }
}
