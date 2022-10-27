<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\Chef;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Routing\Controller as BaseController;
use GuzzleHttp\Client;


class chefController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function create(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            [
                'nombre' => 'required|max:25',
                'ap_paterno' => 'required|max:30',
                'ap_materno' => 'required|max:30',
                'nacionalidad' => 'required', Rule::in(['Mexicana', 'Italiana']),
                'edad' => 'required'
            ]
        );
        
        if ($validacion->fails()) {
            return response()->json([
                "Error" => $validacion->errors()
            ], 400);
        }

     
        if ($request->fullUrl() == 'http://127.1.1.2:8000/api/chef') 
            {
                $bandera = 1;
                
                if($bandera == 1)
                {
                    $chef = new Chef();
                    $chef->nombre = $request->nombre;
                    $chef->ap_paterno = $request->ap_paterno;
                    $chef->ap_materno = $request->ap_materno;
                    $chef->nacionalidad = $request->nacionalidad;
                    $chef->edad = $request->edad;
                    $chef->save();
                    $bandera++;
                }
                     if($bandera!=1)
                        {
                            Http::post('http://127.1.1.2:8000/api/chef', [
                            'nombre' => $request->nombre,
                            'ap_paterno' => $request->ap_materno,
                            'ap_materno' => $request->ap_paterno,
                            'nacionalidad' => $request->nacionalidad,
                            'edad' => $request->edad,
                            ]); 
                        }
            }

        
  

    //     if($response->successful()){
    //         $chef = new Chef();
    //         $chef->nombre = $request->nombre;
    //         $chef->ap_paterno = $request->ap_paterno;
    //         $chef->ap_materno = $request->ap_materno;
    //         $chef->nacionalidad = $request->nacionalidad;
    //         $chef->edad = $request->edad;
    //         $chef->save();
    //         if ($chef->save()) {
    //             return response()->json([
    //                 "status" => 201,
    //                 "mgs" => "Se inserto correctamente",
    //                 "error" => [],
    //                 "data" => $chef
    //             ]);
    //         }
    //         else{
    //             return response()->json([
    //             "Status" => "400",
    //             "Error" => "Inserccion fallida"
    //             ], 400);
    //         }
    //     } 
    //     else{
    //         return response()->json([
    //         "Status" => "400",
    //         "Error" => "Error de api2"
    //         ], 400);
    //     }
         
    }

    


    public function info(Request $request)
    {
        $chef = DB::table('chefs')->get()->all();
        return response()->json([
            "table" => "chefs",
            $chef
        ]);
    }



    public function update(Request $request, $id)
    {
        $validacion = Validator::make($request->all(), [
            'nombre' => 'required|max:25',
            'ap_paterno' => 'required|max:30',
            'ap_materno' => 'required|max:30',
            'nacionalidad' => 'required', Rule::in(['Mexicana', 'Italiana']),
            'edad' => 'required'
        ]);
        if ($validacion->fails()) {
            return response()->json([
                "Error" => $validacion->errors()
            ], 400);
        }


        $chef = Chef::find($id);
        $chef->nombre = $request->nombre;
        $chef->ap_paterno = $request->ap_paterno;
        $chef->ap_materno = $request->ap_materno;
        $chef->nacionalidad = $request->nacionalidad;
        $chef->edad = $request->edad;
        $chef->save;

        $chef = Http::put('http://192.168.127.80:8000/api/chef/update/1', [
            'nombre' => $chef->nombre,
            'ap_paterno' => $chef->ap_paterno,
            'ap_materno' => $chef->ap_materno,
            'nacionalidad' => $chef->nacionalidad,
            'edad' => $chef->edad
        ]);

        if ($chef->save()) {
            return response()->json([
                "status" => 201,
                "mgs" => " Se ha guardado exitosamente",
                "error" => null,
                "data" => $chef
            ]);
        }
    }
}
