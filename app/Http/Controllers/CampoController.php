<?php

namespace App\Http\Controllers;

use App\Models\Campo;
use App\Models\Campos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CampoController extends Controller
{
    public function campos(Request $request)
{
    $response = ['status' => 0, 'msg' => '', 'code' => 200];
    $campos = [];

    if ($request->has('id_usuario')) {
        if ($request->has('id')) {
            if (!is_numeric($request->id)) {
                return response()->json(['status' => 0, 'msg' => 'Bad Parameters', 'code' => 400]);
            }
            $query = Campo::where('id', $request->id)->where('id_usuario', $request->id_usuario)->first();
            if ($query) {
                $campos[] = [
                    'id' => $query->id,
                    'nombre' => $query->nombre,
                    'provincia' => $query->provincia,
                    'municipio' => $query->municipio,
                    'agregado' => $query->agregado,
                    'zona' => $query->zona,
                    'poligono' => $query->poligono,
                    'parcela' => $query->parcela,
                    'recinto' => $query->recinto,
                    'id_usuario' => $query->id_usuario,
                ];
            }
        } else {
            $query = Campo::where('id_usuario', $request->id_usuario)->get();
            foreach ($query as $campo) {
                $campos[] = [
                    'id' => $campo->id,
                    'nombre' => $campo->nombre,
                    'provincia' => $campo->provincia,
                    'municipio' => $campo->municipio,
                    'agregado' => $campo->agregado,
                    'zona' => $campo->zona,
                    'poligono' => $campo->poligono,
                    'parcela' => $campo->parcela,
                    'recinto' => $campo->recinto,
                    'id_usuario' => $campo->id_usuario,

                ];
            }
        }

        if ($campos) {
            return response()->json($campos);
        } else {
            return response()->json($response);
        }
    } else {
        return response()->json(['status' => 0, 'msg' => 'Bad Parameters', 'code' => 400]);
    }
}


    public function añadirCampo(Request $request){
        $response = ['status' => 0, 'msg' => 'Error al agregar un campo'];

        try {
            if (
                $request->has('nombre') &&
                $request->has('provincia') &&
                $request->has('municipio') &&
                $request->has('agregado') &&
                $request->has('zona') &&
                $request->has('poligono') &&
                $request->has('parcela') &&
                $request->has('id_usuario') &&
                $request->has('recinto')
            ) {
                $exist = Campo::where('nombre', $request->nombre)->first();

                if ($exist)  return response()->json($response);

                DB::table('Campo')->insert([
                    'nombre' => $request->nombre,
                    'provincia' => $request->provincia,
                    'municipio' => $request->municipio,
                    'agregado' => $request->agregado,
                    'zona' => $request->zona,
                    'poligono' => $request->poligono,
                    'parcela' => $request->parcela,
                    'recinto' => $request->recinto,
                    'id_usuario' => $request->id_usuario,
                ]);

                $response['status'] = 1;
                $response['msg'] = 'Campo agregado correctamente';
                $response['code'] = 200;
            }
        } catch (\Exception $e) {
            $response['msg'] = $e->getMessage();
        }

        return response()->json($response);
    }

    public function borrarCampo(Request $request){
        $response = ['status' => 0, 'msg' => 'Error al eliminar el campo.'];
        try {
            if ($request->has('id')) {
                $exist = Campo::where('id', $request->id)->first();
                if ($exist) {
                    DB::beginTransaction();
                    DB::table('Campo')->where('id', $request->id)->delete();
                    DB::commit();
                } else return response()->json(['status' => 0, 'msg' => 'El campo no existe.']);


                $response['status'] = 1;
                $response['msg'] = 'Campo eliminada correctamente.';
                $response['code'] = 200;
            }
        } catch (\Exception $e) {
            $response['msg'] = $e->getMessage();
        }

        return response()->json($response);
    }
}
