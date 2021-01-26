<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActividadesRealizadas;
use App\Models\Usuario;
use App\Models\Categoria;

use Illuminate\Support\Facades\Cache;

class ActividadesRealizadasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    $actividadesRealizadas=Cache::remember('actividadesRealizadas',15/60,function()
        {

            // Este método paginate() está orientado a interfaces gráficas.
            // Paginator tiene un método llamado render() que permite construir
            // los enlaces a página siguiente, anterior, etc..
            // Para la API RESTFUL usaremos un método más sencillo llamado simplePaginate() que
            // aporta la misma funcionalidad
            return ActividadesRealizadas::simplePaginate(10);  // Paginamos cada 10 elementos.

        });
        // Con la paginación lo haremos de la siguiente forma:
        // Devolviendo también la URL a l
        return response()->json(['status'=>'ok', 'siguiente'=>$actividadesRealizadas->nextPageUrl(),'anterior'=>$actividadesRealizadas->previousPageUrl(),'data'=>$actividadesRealizadas->items()],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $actividad=ActividadesRealizadas::find($id);
         if (! $actividad)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'ActividadesRealizadasController.show: No se encuentra una actividad con ese código.'])],404);
        }

        $data = (object)[];
        $data->observacion = $actividad->observacion;
        $data->horasReales = $actividad->horasReales;
        $data->valoracion = $actividad->valoracion;
        $data->usuarioSolicita = $actividad->usuarioSolicita()->get();
        $data->usuarioRealizada = $actividad->usuarioRealiza()->get();
        $data->habilidad = $actividad->habilidad()->get();
        return response()->json(['status'=>'ok','data'=>$data],200);

    }

    public function noAsignadas($valor)
    {
       
       if($valor==0){
            $actividades=ActividadesRealizadas::whereNull('usuarioRealiza_id')->with('usuarioSolicita')->get();
       }
       else {
            $actividades=ActividadesRealizadas::whereNotNull('usuarioRealiza_id')->with(['usuarioSolicita', 'usuarioRealiza'])->get();
       }

        if (! $actividades)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'ActividadesRealizadasController.noAsignadas: No se encuentran actividades sin listar.'])],404);
        }

        $data = (object)[];
        $data->actividades = $actividades;
        return response()->json(['status'=>'ok','data'=>$actividades],200);

    }

    public function finalizadas($valor)
    {
       $actividades=(object)[];
       if($valor==0){
            $actividades=ActividadesRealizadas::whereNull('finalizada')->orWhere('finalizada',0)->get();
       }
       else {
            $actividades=ActividadesRealizadas::whereNotNull('finalizada')->where('finalizada',1)->get();
       }

        if (! $actividades)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'ActividadesRealizadasController.noAsignadas: No se encuentran actividades sin listar.'])],404);
        }

        $data = (object)[];
        $data->actividades = $actividades;
        return response()->json(['status'=>'ok','data'=>$data],200);

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $actividad = new ActividadesRealizadas;
        $actividad = ActividadesRealizadas::findOrFail($id);
        $actividad->update($request->all());
        return $actividad;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
