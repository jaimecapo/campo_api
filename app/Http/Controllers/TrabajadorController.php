<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\seLeAsigna;
use App\Models\Trabajador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrabajadorController extends Controller
{
    public function trabajadores(Request $request)
    {
        $response = ['status' => 0, 'msg' => '', 'code' => 200];
        $trabajadores = [];
        if ($request->has("id_usuario")) {
            if ($request->has('nif')) {
                $entry = Trabajador::where('nif', $request->nif)->where('id_usuario', $request->id_usuario)->first();

                if ($entry) {
                    $activities = [];
                    $queryActivities = seLeAsigna::where('nif_trabajador', $entry['nif'])->get();

                    foreach ($queryActivities as $seAsigna) {
                        $activity = Actividad::where('id', $seAsigna['id_actividad'])->first();

                        $activities[] = [
                            'id' => $activity->id,
                            'titulo' => $activity->titulo,
                            'descripcion' => $activity->descripcion,
                            'fecha_inicio' => $activity->fecha_inicio,
                            'fecha_final' => $activity->fecha_final,
                            'id_campo' => $activity->id_campo,
                        ];
                    }

                    $trabajadores[] = [
                        'nif' => $entry['nif'],
                        'nombre' => $entry['nombre'],
                        'apellidos' => $entry['apellidos'],
                        'correo' => $entry['correo'],
                        'telefono' => $entry['telefono'],
                        'puesto' => $entry['puesto'],
                        'id_usuario' => $entry['id_usuario'],
                        'actividades' => $activities
                    ];
                }
            } else {


                $entrys = Trabajador::where("id_usuario", $request->id_usuario)->get();

                foreach ($entrys as $entry) {
                    $activities = [];
                    $queryActivities = seLeAsigna::where('nif_trabajador', $entry['nif'])->get();

                    foreach ($queryActivities as $seAsigna) {
                        $activity = Actividad::where('id', $seAsigna['id_actividad'])->first();
                        $activities[] = [
                            'id' => $activity->id,
                            'titulo' => $activity->titulo,
                            'descripcion' => $activity->descripcion,
                            'fecha_inicio' => $activity->fecha_inicio,
                            'fecha_final' => $activity->fecha_final,
                            'id_campo' => $activity->id_campo,
                        ];
                    }

                    $trabajadores[] = [
                        'nif' => $entry['nif'],
                        'nombre' => $entry['nombre'],
                        'apellidos' => $entry['apellidos'],
                        'correo' => $entry['correo'],
                        'telefono' => $entry['telefono'],
                        'puesto' => $entry['puesto'],
                        'id_usuario' => $entry['id_usuario'],

                        'actividades' => $activities
                    ];
                }
            }
        }else{return response()->json(['status' => 0, 'msg' => 'Bad Parameters', 'code' => 400]);}

        if ($trabajadores) {
            return response()->json($trabajadores);
        }

        return response()->json($response);
    }

    public function añadirTrabajador(Request $request)
    {
        $response = ['status' => 0, 'msg' => 'Error al agregar un trabajador'];
        try {
            if ($request->has('id_usuario')  && $request->has('nif')  && $request->has('nombre') && $request->has('apellidos') && $request->has('correo') && $request->has('telefono') && $request->has('puesto')) {
                $exist = Trabajador::where('nif', $request->nif)->first();
                if ($exist) return response()->json($response);

                DB::table('Trabajador')->insert([
                    'nif' => $request->nif,
                    'nombre' => $request->nombre,
                    'apellidos' => $request->apellidos,
                    'correo' => $request->correo,
                    'telefono' => $request->telefono ? $request->telefono : "",
                    'puesto' => $request->puesto,
                    "id_usuario"=>$request->id_usuario
                ]);
                $response['status'] = 1;
                $response['msg'] = 'Trabajador agregado correctamente';
                $response['code'] = 200;
            }
        } catch (\Exception $e) {
            $response['msg'] = $e->getMessage();
        }

        return response()->json($response);
    }

    public function borrarTrabajador(Request $request)
    {
        $response = ['status' => 0, 'msg' => 'Error al eliminar trabajador.'];
        try {
            if ($request->has('nif')) {
                $exist = Trabajador::where('nif', $request->nif)->first();
                if ($exist) {
                    DB::beginTransaction();
                    DB::table('SeLeAsigna')->where('nif_trabajador', $request->nif)->delete();

                    DB::table('Trabajador')->where('nif', $request->nif)->delete();
                    DB::commit();
                } else return response()->json(['status' => 0, 'msg' => 'El trabajador no existe.']);


                $response['status'] = 1;
                $response['msg'] = 'Trabajador eliminado correctamente.';
                $response['code'] = 200;
            }
        } catch (\Exception $e) {
            $response['msg'] = $e->getMessage();
        }

        return response()->json($response);
    }
}
