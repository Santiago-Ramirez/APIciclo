<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Mapa;
use Illuminate\Support\Facades\Log;


class Mapas extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function insertar(Request $request)
    {
        $validator = validator::make(
            $request->all(),
            [
                'totalZonas' => 'required|integer',
                'nombre' => 'required|max:50',
                'descripcion' => 'required|max:400'
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
        $Zone = new Mapa();
        $Zone->totalZonas = $request->totalZonas;
        $Zone->nombre = $request->nombre;
        $Zone->descripcion = $request->descripcion;

        if ($Zone->save()) {
            Log::channel('slackinfo')->info('El mapa se inserto correctamente!', [$Zone->nombre]);
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
                'totalZonas' => 'required|integer',
                'nombre' => 'required|max:50',
                'descripcion' => 'required|max:400'
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
        $Zone = Mapa::find($id);
        $Zone->totalZonas = $request->totalZonas;
        $Zone->nombre = $request->nombre;
        $Zone->descripcion = $request->descripcion;

        if ($Zone->save()) {
            Log::channel('slackinfo')->info('El mapa se modifico correctamente!', [$Zone->nombre]);
            return response()->json([
                "status" => 200,
                "message" => "La ubicacion " . $Zone->nombre . " fue modificada exitosamente",
                "error" => [],
                "data" => $validator->validate()
            ], 200);
        }
    }

    public function eliminar(int $id)
    {
        if ($id == true) {
            Log::channel('slackinfo')->info('El mapa se modifico correctamente!');
            $Zone = Mapa::find($id);
            $Zone->delete();
            return response()->json([
                "status" => 200,
                "message" => "El Mapa " . $Zone->nombre . " fue eliminado exitosamente",
                "error" => [],
                "data" => []
            ], 200);
        }
    }
    public function mapas(int $mapa)
    {
        $Zone = Mapa::find($mapa);
        $Mapas = Mapa::join('misions', 'misions.mapa', '=', 'mapas.id')
            ->where('mapas.id', '=', $mapa)
            ->get();
        return response()->json([
            "Misiones del mapa " . $Zone->nombre . "",
            $Mapas
        ]);
    }

    public function objetos(int $mapa)
    {
        $Zone = Mapa::find($mapa);
        $Objetos = Mapa::select(
            'objetos.nombre',
            'objetos.descripcion',
            'rareza',
            'valor as Valor_venta',
            'limiteBolsa'
        )->join('mapa_objetos', 'mapa_objetos.mapa', '=', 'mapas.id')
            ->join('objetos', 'objetos.id', 'mapa_objetos.objeto')
            ->where('mapa_objetos.mapa', '=', $mapa)
            ->get();
        return response()->json([
            "Objetos del mapa " . $Zone->nombre . "",
            $Objetos
        ]);
    }

    public function monstruos(int $mapa)
    {
        $Zone = Mapa::find($mapa);
        $Monstruos = Mapa::select(
            'monstruos.nombre',
            'monstruos.descripcion',
            'monstruos.especie',
            'monstruos.tipo',
        )
            ->join('misions', 'misions.mapa', '=', 'mapas.id')
            ->join('mision_monstruos', 'misions.id', 'mision_monstruos.mision')
            ->join('monstruos', 'monstruos.id', 'mision_monstruos.monstruo')
            ->where('mapas.id', '=', $mapa)
            ->get();

        $objetos = Mapa::join('monstruos', 'monstruo_objetos.monstruo', '=', 'monstruos.id')
            ->join()
            ->get();


        return response()->json([
            "Monstruos que habitan en: " . $Zone->nombre . "",
            $Monstruos
        ]);
    }
}
