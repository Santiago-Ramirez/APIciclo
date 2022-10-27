<?php

namespace App\Http\Controllers;

use App\Models\Ingrediente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ingrController extends Controller
{
    public function create(Request $request)
    {

        $validacion = Validator::make($request->all(),[
            'nombre'=>'required|max:30',
            'tipo' =>'required|max:40',
            'cantidad' =>'required|min:1',
            
        ]);
        if($validacion->fails()){
            return response()->json([
                "Error"=>$validacion->errors()
            ],400);
        }
        
        
            $ingrediente = new Ingrediente();
            $ingrediente->nombre =$request->nombre;
            $ingrediente->tipo =$request->tipo;
            $ingrediente->cantidad =$request->cantidad;
            $ingrediente->save;
        
        if ($ingrediente ->save()){
            return response()->json([
                "status"=>201,
                "mgs"=>" Se ha guardado exitosamente",
                "error"=>null,
                "data" =>$ingrediente
            ]);
        }
            
        }
        public function info(Request $request)
        {
        $receta= DB::table('ingredientes')->get()->all();
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
        
        
        $ingrediente = Ingrediente::find($id);
        $ingrediente->nombre =$request->nombre;
        $ingrediente->tipo =$request->tipo;
        $ingrediente->cantidad =$request->cantidad;
        $ingrediente-> save;
        
        if ($ingrediente ->save()){
        return response()->json([
            "status"=>201,
            "mgs"=>" Se ha guardado exitosamente",
            "error"=>null,
            "data" =>$ingrediente
        ]);
        }
        
        }
}
