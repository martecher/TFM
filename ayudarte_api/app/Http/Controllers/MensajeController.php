<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mensaje;

class MensajeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
		// Primero comprobaremos si estamos recibiendo todos los campos.
		if (!$request->input('texto') || !$request->input('usuarioEnvia_id') || !$request->input('usuarioReacibe_id')  || !$request->input('tarea_id'))
		{
			// Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
			// En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
			return response()->json(['errors'=>array(['code'=>422,'message'=>' MensajeController.store(): Faltan datos necesarios para el proceso de alta de mensaje.'])],422);
		}

		// Insertamos una fila en Fabricante con create pasándole todos los datos recibidos.
		// En $request->all() tendremos todos los campos del formulario recibidos.
		$mensaje=Mensaje::create($request->all());
        $data = (object)[];
        $data->mensaje =$mensaje;
        // Más información sobre respuestas en http://jsonapi.org/format/
        // Devolvemos el código HTTP 201 Created – [Creada] Respuesta a un POST que resulta en una creación. Debería ser combinado con un encabezado Location, apuntando a la ubicación del nuevo recurso.
        //return response()->json(['data'=>$nuevoFabricante], 201)->header('Location',  url('/api/v1/').'/fabricantes/'.$nuevoFabricante->id);
        return response()->json(['status'=>'ok','data'=>$data],201);
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
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
    public function mensajesDeLaTarea($id)
    {
        $mensajes=Mensaje::where('tarea_id', $id)->with('usuarioEnvia', 'usuarioRecibe')->orderBy('orden', 'asc')->get();
        if (! $mensajes)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'MensajeController.mensajesDeLaTarea: No se encuentran mensajes sin listar.'])],404);
        }

        for($i = 0, $size = count($mensajes); $i < $size; ++$i) {
            $mensaje=  $mensajes[$i];
            $mensaje->leido=1;
            $mensaje->update();
        }

        $mensajes=Mensaje::where('tarea_id', $id)->with('usuarioEnvia', 'usuarioRecibe')->orderBy('orden', 'asc')->get();
        return response()->json(['status'=>'ok','data'=>$mensajes],200);
    }

    public function marcarleidos($id)
    {
        $mensajes=Mensaje::where('tarea_id', $id);
        if (! $mensajes)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'MensajeController.mensajesDeLaTarea: No se encuentran mensajes sin listar.'])],404);
        }
        $data = (object)[];
        $data->mensajes = $mensajes;
        return response()->json(['status'=>'ok','data'=>$mensajes],200);
    }

    
}
