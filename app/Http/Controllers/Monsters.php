<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Monstruo;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Monsters extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function insertar(Request $request)
    {

        $validator = validator::make(
            $request->all(),
            [
                'tipo' => 'required|max:50',
                'especie' => 'required|max:50',
                'nombre' => 'required|max:50',
                'descripcion' => 'required|max:300',
                'debilidades' => 'required|max:100',
                'habitats' => 'required|max:300',
                'tamaño' => 'required|max:20',
                'parientes' => 'max:300'
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

        $Monster = new Monstruo();
        $Monster->tipo = $request->tipo;
        $Monster->especie = $request->especie;
        $Monster->nombre = $request->nombre;
        $Monster->descripcion = $request->descripcion;
        $Monster->debilidades = $request->debilidades;
        $Monster->habitats = $request->habitats;
        $Monster->tamaño = $request->tamaño;
        $Monster->parientes = $request->parientes;
        $Monster = Http::post('http://192.168.123.128:8000/api/monstruos/insertar', [
            'tipo' => $request->tipo,
            'especie' => $request->especie,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'debilidades' => $request->debilidades,
            'habitats' => $request->habitats,
            'tamaño' => $request->tamaño,
            'parientes' => $request->parientes
        ]);
        if ($Monster->save()) {
            Log::channel('slackinfo')->info('El monstruo se inserto correctamente!', [$Monster->nombre]);
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
                'tipo' => 'required|max:50',
                'especie' => 'required|max:50',
                'nombre' => 'required|max:50',
                'descripcion' => 'required|max:300',
                'debilidades' => 'required|max:100',
                'habitats' => 'required|max:300',
                'tamaño' => 'required|max:20',
                'parientes' => 'max:300'
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
        $Monster = Monstruo::find($id);
        $Monster->tipo = $request->tipo;
        $Monster->especie = $request->especie;
        $Monster->nombre = $request->nombre;
        $Monster->descripcion = $request->descripcion;
        $Monster->debilidades = $request->debilidades;
        $Monster->habitats = $request->habitats;
        $Monster->tamaño = $request->tamaño;
        $Monster->parientes = $request->parientes;

        if ($Monster->save()) {
            Log::channel('slackinfo')->info('El monstruo se modifico correctamente!', [$Monster->nombre]);
            return response()->json([
                "status" => 200,
                "message" => "El Mounstruo " . $Monster->nombre . " fue modificado exitosamente",
                "error" => [],
                "data" => $validator->validate()
            ], 200);
        }
    }

    public function eliminar(int $id)
    {
        $Monster = Monstruo::find($id);
        $Monster->delete();
        return response()->json([
            "status" => 200,
            "message" => "El Mounstruo " . $Monster->nombre . " fue eliminado exitosamente",
            "error" => [],
            "data" => []
        ], 200);
        Log::channel('slackinfo')->info('El monstruo se elimino correctamente!', [$Monster->nombre]);
    }


    public function monstruos(int $id)
    {

        $ids = Monstruo::find($id);
        if ($id == true) {
            Log::channel('slackinfo')->info('Se realizo una consulta');
            $Monstruos = Monstruo::where('monstruos.id', '=', $id)->get();

            return response()->json([
                $ids->nombre,
                $Monstruos
            ]);
        } else if ($id == 0) {
            Log::channel('slackinfo')->info('Se realizo una consulta');
            $Monstruos = Monstruo::get();

            return response()->json([
                $Monstruos
            ]);
        }
    }
}
