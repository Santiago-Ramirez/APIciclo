<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receta;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;


class recetaController extends Controller
{
    public function create(Request $request)
    {

        $validacion = Validator::make($request->all(),[
            'nombre'=>'required|max:30',
            'duracion' =>'required|max:40',
            'preparacion' =>'required',
            'chef' =>'required',
        ]);
        if($validacion->fails()){
            return response()->json([
                "Error"=>$validacion->errors()
            ],400);
        }
        
        
            $receta = new Receta();
            $receta->nombre =$request->nombre;
            $receta->duracion =$request->duracion;
            $receta->preparacion =$request->preparacion;
            $receta->chef =$request->chef;
            $receta->save;
        
        if ($receta ->save()){
            return response()->json([
                "status"=>201,
                "mgs"=>" Se ha guardado exitosamente",
                "error"=>null,
                "data" =>$receta
            ]);
        }
            
        }
        public function info(Request $request)
        {
        $receta= DB::table('recetas')->get()->all();
        return response()->json([
            "table" => "recetas",
            $receta
        ]);
        }
        public function update(Request $request, $id)
        {
        $validacion = Validator::make($request->all(),[
            'nombre'=>'required|max:30',
            'duracion' =>'required|max:40',
            'preparacion' =>'required',
            'chef' =>'required',
        ]);
        if($validacion->fails()){
        return response()->json([
            "Error"=>$validacion->errors()
        ],400);
        }
        
        
        $receta = Receta::find($id);
        $receta->nombre =$request->nombre;
        $receta->duracion =$request->duracion;
        $receta->preparacion =$request->preparacion;
        $receta->chef =$request->chef;
        $receta-> save;
        
        if ($receta ->save()){
        return response()->json([
            "status"=>201,
            "mgs"=>" Se ha guardado exitosamente",
            "error"=>null,
            "data" =>$receta
        ]);
        }
        
        }
}
