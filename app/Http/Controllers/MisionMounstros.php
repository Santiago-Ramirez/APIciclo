<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\MisionMonstruo;

class MisionMounstros extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function insertar(Request $request)
    {
        $validator = validator::make(
            $request->all(),
            [
                'mision' => 'required|integer',
                'monstruo' => 'required|integer'
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
        $MisMon = new MisionMonstruo();
        $MisMon->mision = $request->mision;
        $MisMon->monstruo = $request->monstruo;

        if ($MisMon->save()) {
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
                'mision' => 'required|integer',
                'monstruo' => 'required|integer'
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
        $MisMon = MisionMonstruo::find($id);
        $MisMon->mision = $request->mision;
        $MisMon->monstruo = $request->monstruo;

        if ($MisMon->save()) {
            return response()->json([
                "status" => 200,
                "message" => "La relacion $id fue modificado exitosamente",
                "error" => [],
                "data" => $validator->validate()
            ], 200);
        }
    }

    public function eliminar(int $id)
    {
        $MisMon = MisionMonstruo::find($id);
        $MisMon->delete();
        return response()->json([
            "status" => 200,
            "message" => "La relacion $id fue eliminada exitosamente",
            "error" => [],
            "data" => []
        ], 200);
    }
}
