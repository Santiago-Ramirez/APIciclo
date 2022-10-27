<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Objeto;
use Illuminate\Support\Facades\Log;

class Objetos extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
    public function insertar(Request $request)
    {
        $validator = validator::make(
            $request->all(),
            [
                'rareza' => 'required|integer',
                'limiteBolsa' => 'required|integer',
                'valor' => 'required|integer',
                'nombre' => 'required|max:50',
                'descripcion' => 'required|max:300'
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                "status" => 400,
                "message" => "Error en la validación!",
                "error" => $validator->errors(),
                "data" => []
            ], 400);
        }
        $Objeto = new Objeto();
        $Objeto->rareza = $request->rareza;
        $Objeto->limiteBolsa = $request->limiteBolsa;
        $Objeto->valor = $request->valor;
        $Objeto->nombre = $request->nombre;
        $Objeto->descripcion = $request->descripcion;

        if ($Objeto->save()) {
            Log::channel('slackinfo')->info('El objeto se inserto correctamente!', [$Objeto->nombre]);
            return response()->json([
                "status" => 200,
                "message" => "Los datos fueron validados correctamente!",
                "error" => [],
                "data" => $validator->validate()
            ], 200);
        }
    }

    public function modificar(Request $request, int $id)
    {
        $validator = validator::make(
            $request->all(),
            [
                'rareza' => 'required|integer',
                'limiteBolsa' => 'required|max:50',
                'valor' => 'required|integer',
                'nombre' => 'required|max:50',
                'descripcion' => 'required|max:300'
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                "status" => 400,
                "message" => "Los datos son incorrectos!",
                "error" => $validator->errors(),
                "data" => []
            ], 400);
        }
        $Objeto = Objeto::find($id);
        $Objeto->rareza = $request->rareza;
        $Objeto->limiteBolsa = $request->limiteBolsa;
        $Objeto->valor = $request->valor;
        $Objeto->nombre = $request->nombre;
        $Objeto->descripcion = $request->descripcion;

        if ($Objeto->save()) {
            Log::channel('slackinfo')->info('El objeto se modifico correctamente!', [$Objeto->nombre]);
            return response()->json([
                "status" => 200,
                "message" => "La ubicacion " . $Objeto->nombre . " fue modificada exitosamente",
                "error" => [],
                "data" => $validator->validate()
            ], 200);
        }
    }

    public function eliminar(int $id)
    {
        if ($id == true) {
            Log::channel('slackinfo')->info('El objeto se elimino correctamente!');
            $Objeto = Objeto::find($id);
            $Objeto->delete();
            return response()->json([
                "status" => 200,
                "message" => "El objeto " . $Objeto->nombre . " fue eliminado exitosamente",
                "error" => [],
                "data" => []
            ], 200);
        }
    }




    public function valor(string $relacion, int $costo)
    {
        if ($relacion == '>') {
            $Objeto = Objeto::select('objetos.id','nombre', 'descripcion', 'rareza', 'valor', 'limiteBolsa')->where('valor', $relacion, $costo)->get();
            return response()->json(["Los objetos con un costo Mayor a " . $costo => $Objeto]);
        }
        if ($relacion == '<') {
            $Objeto = Objeto::select('nombre', 'descripcion', 'rareza', 'valor', 'limiteBolsa')->where('valor', $relacion, $costo)->get();
            return response()->json(["Los objetos con un costo Menor a " . $costo => $Objeto]);
        }
    }

    public function rareza(int $rareza)
    {

        $Objeto = Objeto::select('objetos.id','nombre', 'descripcion', 'rareza', 'valor', 'limiteBolsa')->where('rareza', '=', $rareza)->get();
        return response()->json(["Objetos con una rareza de " . $rareza => $Objeto]);
    }

    public function monstruos(int $id)
    {
        $ids = Objeto::find($id);
        $Monstruos = Objeto::select('objetos.id','objetos.nombre', 'objetos.descripcion', 'objetos.valor', 'objetos.rareza', 'objetos.limiteBolsa')
            ->join('monstruo_objetos', 'objetos.id', '=', 'monstruo_objetos.objeto')->join('monstruos', 'monstruo_objetos.monstruo', '=', 'monstruos.id')
            ->where('monstruo_objetos.monstruo', '=', $id)
            ->get();

        return response()->json([
            $ids->nombre,
            $Monstruos
        ]);
    }
}
