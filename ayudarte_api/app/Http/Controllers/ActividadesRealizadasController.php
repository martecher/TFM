<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActividadesRealizadas;
use App\Models\Usuario;
use App\Models\Categoria;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Mail;
use App\Mail\MensajeAsignacionTarea;
use App\Mail\MensajeFinalizacionTarea;

use App\Models\TareaMensaje;
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
         
        $tarea = new ActividadesRealizadas;
       
        $tarea->observacion = $request->observacion;
        $tarea->usuarioSolicita_id = $request->usuarioSolicita_id;
        $tarea->habilidad_id = $request->habilidad_id;
        $tarea->finalizada = 0;
        $tarea->valoracion = 0;
        $tarea->save();
     }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         //OJO  QUE NO SE SI ESTE METODO BUSCA BIEN Y CARGA LOS OBJETOS RELACIONADOS
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
        $data->usuarioRealiza = $actividad->usuarioRealiza()->get();
        $data->habilidad = $actividad->habilidad()->get();
        $data->puntuacionSolicita = $actividad->puntuacionSolicita;
        $data->finalizada = $actividad->finalizada;
        return response()->json(['status'=>'ok','data'=>$data],200);
    }

    public function asignadas($valor)
    {
       
       if($valor==0){
            $actividades=ActividadesRealizadas::whereNull('finalizada')->orWhere('finalizada',0)->whereNull('usuarioRealiza_id')->with('usuarioSolicita', 'habilidad','mensajesTarea')->get();
       }
       else {
            $actividades=ActividadesRealizadas::whereNull('finalizada')->orWhere('finalizada',0)->whereNotNull('usuarioRealiza_id')->with(['usuarioSolicita', 'usuarioRealiza', 'habilidad','mensajesTarea'])->get();
       }

        if (! $actividades)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'ActividadesRealizadasController.asignadas: No se encuentran actividades sin listar.'])],404);
        }

        $data = (object)[];
        $data->actividades = $actividades;
        return response()->json(['status'=>'ok','data'=>$actividades],200);

    }

    public function asignadasUsuario($id)
    {

        $actividades=ActividadesRealizadas::whereNull('finalizada')->orWhere('finalizada',0)->where('usuarioRealiza_id',$id)->with('usuarioSolicita', 'habilidad','usuarioRealiza','mensajesTarea')->get();
      

        if (! $actividades)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'ActividadesRealizadasController.asignadasUsuario: No se encuentran actividades sin listar.'])],404);
        }

        $data = (object)[];
        $data->actividades = $actividades;
        return response()->json(['status'=>'ok','data'=>$actividades],200);

    }

    public function solicitadasUsuario($id)
    {

        $actividades=ActividadesRealizadas::whereNull('finalizada')->orWhere('finalizada',0)->where('usuarioSolicita_id',$id)->with('usuarioSolicita', 'habilidad','usuarioRealiza','mensajesTarea')->get();
      

        if (! $actividades)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'ActividadesRealizadasController.solicitadasUsuario: No se encuentran actividades sin listar.'])],404);
        }

        $data = (object)[];
        $data->actividades = $actividades;
        return response()->json(['status'=>'ok','data'=>$actividades],200);

    }

    public function finalizadas($valor)
    {

       if($valor==0){
            $actividades=ActividadesRealizadas::whereNull('finalizada')->orWhere('finalizada',0)->with('usuarioSolicita', 'habilidad','usuarioRealiza','mensajesTarea')->get();
       }
       else {
            $actividades=ActividadesRealizadas::whereNotNull('finalizada')->orWhere('finalizada',1)->with('usuarioSolicita', 'habilidad','usuarioRealiza','mensajesTarea')->get();
       }

        if (! $actividades)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'ActividadesRealizadasController.finalizadas: No se encuentran actividades sin listar.'])],404);
        }

        $data = (object)[];
        $data->actividades = $actividades;
        return response()->json(['status'=>'ok','data'=>$data],200);

    }


    public function enRealizacionUsuario($id)
    {
        $actividades=ActividadesRealizadas::where('finalizada',0)->where('usuarioRealiza_id', $id)->with('usuarioSolicita', 'habilidad','usuarioRealiza','mensajesTarea')->get();
        if (! $actividades)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'ActividadesRealizadasController.enRealizacionUsuario: No se encuentran actividades sin listar.'])],404);
        }
        $data = (object)[];
        $data->actividades = $actividades;
        return response()->json(['status'=>'ok','data'=>$actividades],200);
    }


    public function enSolicitudUsuario($id)
    {   
        $actividades=ActividadesRealizadas::where('finalizada',0)->where('usuarioSolicita_id', $id)->whereNotNull('usuarioRealiza_id')->with('usuarioSolicita', 'habilidad','usuarioRealiza','mensajesTarea')->get();
        if (! $actividades)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'ActividadesRealizadasController.enSolicitudUsuario: No se encuentran actividades sin listar.'])],404);
        }
        $data = (object)[];
        $data->actividades = $actividades;
        return response()->json(['status'=>'ok','data'=>$actividades],200);
    }


    public function enTerminadasUsuario($id)
    {    //falta por añadir el filtro finalizada
        $actividades=ActividadesRealizadas::where('usuarioSolicita_id', $id)->where('finalizada',1)->orWhere('usuarioRealiza_id', $id)->where('finalizada',1)->with('usuarioSolicita', 'habilidad','usuarioRealiza','mensajesTarea')->get();
        if (! $actividades)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'ActividadesRealizadasController.enTerminadasUsuario: No se encuentran actividades sin listar.'])],404);
        }
        $data = (object)[];
        $data->actividades = $actividades;
        return response()->json(['status'=>'ok','data'=>$actividades],200);
    }

    public function solicitadasPorUsuario($id)
    {    
        $actividades=ActividadesRealizadas::where('usuarioSolicita_id', $id)->with('usuarioSolicita', 'habilidad','usuarioRealiza','mensajesTarea')->get();
        if (! $actividades)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'ActividadesRealizadasController.solicitadasPorUsuario: No se encuentran actividades sin listar.'])],404);
        }
        $data = (object)[];
        $data->actividades = $actividades;
        return response()->json(['status'=>'ok','data'=>$actividades],200);
    }

    public function realizadasPorUsuario($id)
    {
        $actividades=ActividadesRealizadas::where('usuarioRealiza_id', $id)->with('usuarioSolicita', 'habilidad','usuarioRealiza','mensajesTarea')->get();
        if (! $actividades)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'ActividadesRealizadasController.realizadasPorUsuario: No se encuentran actividades sin listar.'])],404);
        }
        $data = (object)[];
        $data->actividades = $actividades;
        return response()->json(['status'=>'ok','data'=>$actividades],200);
    }


    public function asignarTarea($id,$usuarioId)
    {
        $actividad = new ActividadesRealizadas;
        $actividad = ActividadesRealizadas::findOrFail($id);

        $actividad->usuarioRealiza_id= $usuarioId;
        $actividad->save();
        // Aqui habria que acceder al usuario solicita  y a su email
        // para mandar email
        $tareaMensaje= new TareaMensaje;
        $tareaMensaje->descripcion =  $actividad->observacion;
        $tareaMensaje->habilidad =  $actividad->habilidad->descripcion;
        $tareaMensaje->usuarioRealiza =  $actividad->usuarioRealiza->nombre . ' '.$actividad->usuarioRealiza->apellido1 . ' '.$actividad->usuarioRealiza->apellido2 ;
        
        Mail::to($actividad->usuarioSolicita->email)->queue(new MensajeAsignacionTarea($tareaMensaje));
        return $actividad;
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
        if($actividad->finalizada==1){
            // aqui se podria comprobar si uno de los campos ha sido finalizada a 1
            // entonces mandar el mail de finalizada
            $tareaMensaje= new TareaMensaje;
            $tareaMensaje->descripcion =  $actividad->observacion;
            $tareaMensaje->habilidad =  $actividad->habilidad->descripcion;
            $tareaMensaje->usuarioRealiza =  $actividad->usuarioRealiza->nombre . ' '.$actividad->usuarioRealiza->apellido1 . ' '.$actividad->usuarioRealiza->apellido2 ;
            
            Mail::to($actividad->usuarioSolicita->email)->queue(new MensajeFinalizacionTarea ($tareaMensaje));
    

        }
     
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
