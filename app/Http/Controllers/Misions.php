<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Mision;
use Illuminate\Support\Facades\Log;

class Misions extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function insertar(Request $request)
    {
        $validator = validator::make(
            $request->all(),
            [
                'nombre' => 'required|max:30',
                'cuota' => 'required|integer',
                'recompensa' => 'required|integer',
                'rango' => 'required|max:10',
                'descripcion' => 'required|max:400',
                'mapa' => 'required|integer',
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
        $Mision = new Mision();
        $Mision->nombre = $request->nombre;
        $Mision->cuota = $request->cuota;
        $Mision->recompensa = $request->recompensa;
        $Mision->rango = $request->rango;
        $Mision->descripcion = $request->descripcion;
        $Mision->mapa = $request->mapa;

        if ($Mision->save()) {
            Log::channel('slackinfo')->info('La mision se inserto correctamente!', [$Mision->nombre]);
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
                'nombre' => 'required|max:30',
                'cuota' => 'required|integer',
                'recompensa' => 'required|integer',
                'rango' => 'required|max:10',
                'descripcion' => 'required|max:400',
                'mapa' => 'required|integer',
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
        $Mision = Mision::find($id);
        $Mision->nombre = $request->nombre;
        $Mision->cuota = $request->cuota;
        $Mision->recompensa = $request->recompensa;
        $Mision->rango = $request->rango;
        $Mision->descripcion = $request->descripcion;
        $Mision->mapa = $request->mapa;

        if ($Mision->save()) {
            Log::channel('slackinfo')->info('La mision se modifico correctamente!', [$Mision->nombre]);
            return response()->json([
                "status" => 200,
                "message" => "La Mision " . $Mision->nombre . " fue eliminado exitosamente",
                "error" => [],
                "data" => $validator->validate()
            ], 200);
        }
    }

    public function eliminar(int $id)
    {
        if ($id == true) {
            Log::channel('slackinfo')->info('La mision se elimino correctamente!');
            $Mision = Mision::find($id);
            $Mision->delete();
            return response()->json([
                "status" => 200,
                "message" => "La mision $id fue eliminado exitosamente",
                "error" => [],
                "data" => []
            ], 200);
        }
    }


    public function misiones(int $id)
    {
        $Misiones = Mision::select('misions.nombre as Mision', 'cuota', 'recompensa', 'rango', 'misions.descripcion', 'mapas.nombre as Mapa')
            ->leftjoin('mapas', 'mapas.id', '=', 'misions.mapa')->where('misions.id', '=', $id)->get();

        $Monstruos = Mision::select('monstruos.nombre as Monstruo', 'especie', 'tipo', 'monstruos.descripcion')
            ->join('mision_monstruos', 'misions.id', '=', 'mision_monstruos.mision')
            ->join('monstruos', 'monstruos.id', '=', 'mision_monstruos.monstruo')
            ->where('mision_monstruos.mision', '=', $id)
            ->get();
        return response()->json(
            [
                "Datos de Mision" => $Misiones,

                "Monstruos durante la mision" => $Monstruos
            ]
        );
    }

    public function rango(string $rango)
    {
        $Misiones = Mision::select('misions.nombre as Mision', 'cuota', 'recompensa', 'rango', 'misions.descripcion', 'mapas.nombre as Mapa')->leftjoin('mapas', 'mapas.id', '=', 'misions.mapa')->where('misions.rango', '=', $rango)->get();
        return response()->json(
            [
                "Datos de Mision" => [$Misiones]
            ]
        );
    }

    public function cuota(string $relacion, int $cuota)
    {
        $Misiones = Mision::select('misions.nombre as Mision', 'cuota', 'recompensa', 'rango', 'misions.descripcion', 'mapas.nombre as Mapa')->leftjoin('mapas', 'mapas.id', '=', 'misions.mapa')->where('misions.cuota', $relacion, $cuota)->get();
        return response()->json(
            [
                "Datos de Mision" => [$Misiones]
            ]
        );
    }

    public function mapa(string $mapa)
    {
        $Misiones = Mision::select('misions.nombre as Mision', 'cuota', 'recompensa', 'rango', 'misions.descripcion', 'mapas.nombre as Mapa')->leftjoin('mapas', 'mapas.id', '=', 'misions.mapa')->where('mapas.nombre', '=', $mapa)->get();
        return response()->json(
            [
                "Datos de Mision" => [$Misiones]
            ]
        );
    }
}
