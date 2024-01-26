<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    function usuarios(Request $request)
    {
        $response = ['status' => 0, 'msg' => '', 'code' => 200];
        $usuarios = [];

        if ($request->has('id')) {
            if (is_numeric($request->id)) {
                $entry = Usuario::where('id', $request->id)->first();
                if ($entry) $usuarios[] = [
                    'id' => $entry['id'],
                    'nombre' => $entry['nombre'],
                    'apellidos' => $entry['apellidos'],
                    'correo' => $entry['correo'],
                    'telefono' => $entry['telefono'],
                ];
            } else return response()->json(['status' => 0, 'msg' => 'Invalid parameters', 'code' => 400]);
        } elseif ($request->has('email')) {
            $entry = Usuario::where('correo', $request->email)->first();
            if ($entry)
                $usuarios[] = [
                    'id' => $entry['id'],
                    'nombre' => $entry['nombre'],
                    'apellidos' => $entry['apellidos'],
                    'correo' => $entry['correo'],
                    'telefono' => $entry['telefono'],
                ];
        } else {
            $entrys = Usuario::all();
            foreach ($entrys as $entry) {
                $usuario = [
                    'id' => $entry['id'],
                    'nombre' => $entry['nombre'],
                    'apellidos' => $entry['apellidos'],
                    'correo' => $entry['correo'],
                    'telefono' => $entry['telefono'],
                ];
                $usuarios[] = $usuario;
            }
        }

        if ($usuarios) return response()->json($usuarios);
        else return response()->json($response);
    }
    function checkUsuario(Request $request)
    {
        $response = ['status' => 0, 'msg' => 'Invalid parameters', 'code' => 400];

        if ($request->has('correo') && $request->has('contraseña')) {
            $user = Usuario::where('correo', $request->correo)->first();
            if ($user) {
                if (Hash::check( $request->contraseña, $user['contraseña'])) {
                    return response()->json(['verify' => true, 'msg' => 'All right', 'user'=>['id'=>$user->id, 'nombre'=>$user->nombre, 'apellidos'=>$user->apellidos, 'correo'=>$user->correo,]]);
                } else return response()->json(['verify' => false, 'msg' => 'Incorrect Password']);
            } else return response()->json(['verify' => false, 'msg' => 'Email not found']);
        } else return response()->json($response);
    }

    function añadirUsuario(Request $request)
    {
        $response = ['status' => 0, 'msg' => 'Error al agregar usuario'];
        try {
            if($request->has('nombre') && $request->has('apellidos') && $request->has('correo') && $request->has('contraseña') && $request->has('telefono')){
                $exist=Usuario::where('correo',$request->correo)->first();
                if($exist) return response()->json($response);
                
                DB::table('Usuario')->insert([
                    'nombre' => $request->nombre,
                    'apellidos' => $request->apellidos,
                    'correo' => $request->correo,
                    'telefono' => $request->telefono? $request->telefono:"",
                    'contraseña' => Hash::make($request->contraseña)
                ]);
                $response['status'] = 1;
                $response['msg'] = 'Usuario agregado correctamente';
                $response['code'] = 200;
            }

        } catch (\Exception $e) {
            $response['msg'] = $e->getMessage();
        }

        return response()->json($response);
    }

    function borrarUsuario(Request $request){
        $response = ['status' => 0, 'msg' => 'Error al eliminar usuario.'];
        try {
            if($request->has('correo')){
                $exist=Usuario::where('correo',$request->correo)->first();
                if($exist){
                    DB::beginTransaction(); 
                    DB::table('Usuario')->where('correo',$request->correo)->delete();
                    DB::commit();
                } else return response()->json(['status' => 0, 'msg' => 'El usuario no existe.']);
                

                $response['status'] = 1;
                $response['msg'] = 'Usuario eliminado correctamente.';
                $response['code'] = 200;
            }

        } catch (\Exception $e) {
            $response['msg'] = $e->getMessage();
        }

        return response()->json($response);
    }
}
