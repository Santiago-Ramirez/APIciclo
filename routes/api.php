<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Monsters;
use App\Http\Controllers\Misions;
use App\Http\Controllers\Mapas;
use App\Http\Controllers\Objetos;
use App\Http\Controllers\MisionMounstros;
use App\Http\Controllers\MapasObjetos;
use App\Http\Controllers\MonstruoObjetos;
use App\Http\Controllers\chefController;
use App\Http\Controllers\ingrController;
use App\Http\Controllers\recetaController;

use App\Models\Mapa;
use App\Models\Mision;
use App\Models\Objeto;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can reg ister API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('monstruos')->group(function () {
    Route::post('/insertar', [Monsters::class, "insertar"]);
    Route::put("/modificar/{id}", [Monsters::class, "modificar"]);
    Route::delete("/eliminar/{id}", [Monsters::class, "eliminar"]);
    Route::get("/{id}", [Monsters::class, "monstruos"]);
});

//Misiones
Route::prefix('misiones')->group(function () {
    Route::post('/insertar', [Misions::class, "insertar"]);
    Route::put("/modificar/{id}", [Misions::class, "modificar"]);
    Route::delete("/eliminar/{id}", [Misions::class, "eliminar"]);
    //Consultas
    Route::get("/{id}", [Misions::class, "misiones"]);
    Route::get("/rango/{rango}", [Misions::class, "rango"]);
    Route::get("/cuota/{relacion}/{cuota}", [Misions::class, "cuota"]);
    Route::get("/mapa/{mapa}", [Misions::class, "mapa"]);
});

Route::prefix('mapas')->group(function () {
    Route::post('/insertar', [Mapas::class, "insertar"]);
    Route::put("/modificar/{id}", [Mapas::class, "modificar"]);
    Route::delete("/eliminar/{id}", [Mapas::class, "eliminar"]);
    //Consultas
    Route::get("/{mapa}", [Mapas::class, "mapas"]);
    Route::get("/objetos/{mapa}", [Mapas::class, "objetos"]);
    Route::get("/monstruos/{mapa}", [Mapas::class, "monstruos"]);
});

Route::prefix('objetos')->group(function () {
    Route::post('/insertar', [Objetos::class, "insertar"]);
    Route::put("/modificar/{id}", [Objetos::class, "modificar"]);
    Route::delete("/eliminar/{id}", [Objetos::class, "eliminar"]);
    //Consultas
    Route::get("/valor/{relacion}/{costo}", [Objetos::class, "valor"]);
    Route::get("/rareza/{rareza}", [Objetos::class, "rareza"]);
    Route::get("/{id}", [Objetos::class, "monstruos"]);
});

//Tablas resultantes de una relacion muchos a muchos
Route::prefix('misionmonstruo')->group(function () {
    Route::post('/insertar', [MisionMounstros::class, "insertar"]);
    Route::put("/modificar/{id}", [MisionMounstros::class, "modificar"]);
    Route::delete("/eliminar/{id}", [MisionMounstros::class, "eliminar"]);
});

Route::prefix('/mapaobjeto')->group(function () {
    Route::post('/insertar', [MapasObjetos::class, 'insertar']);
    Route::put('/modificar/{id}', [MapasObjetos::class, 'modificar']);
    Route::delete('/eliminar/{id}', [MapasObjetos::class, 'eliminar']);
});

Route::prefix('/monstruoobjeto')->group(function () {
    Route::post('/insertar', [MonstruoObjetos::class, 'insertar']);
    Route::put('/modificar/{id}', [MonstruoObjetos::class, 'modificar']);
    Route::delete('/eliminar/{id}', [MonstruoObjetos::class, 'eliminar']);
});


//RESTAURANTE
Route::post("/chef",[chefController::class,"create"]);
Route::get("/chef/info",[chefController::class,"info"]);
Route::get("/chef/info2",[chefController::class,"info2"]);

Route::put("/chef/update/{id}",[chefController::class,"update"]);

Route::post("/receta",[recetaController::class,"create"]);
Route::get("/receta/info",[recetaController::class,"info"]);
Route::put("/receta/update/{id}",[recetaController::class,"update"]);

Route::post("/ingreidente",[ingrController::class,"create"]);
Route::get("/ingreidente/info",[ingrController::class,"info"]);
Route::put("/ingreidente/update/{id}",[ingrController::class,"update"]);
