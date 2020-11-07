<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //return Usuario::all();
   $usuarios=Cache::remember('cacheusuarios',15/60,function()
        {
            // Para la paginación en Laravel se usa "Paginator"
            // En lugar de devolver
            // return Fabricante::all();
            // devolveremos return Fabricante::paginate();
            //
            // Este método paginate() está orientado a interfaces gráficas.
            // Paginator tiene un método llamado render() que permite construir
            // los enlaces a página siguiente, anterior, etc..
            // Para la API RESTFUL usaremos un método más sencillo llamado simplePaginate() que
            // aporta la misma funcionalidad
            return Usuario::simplePaginate(10);  // Paginamos cada 10 elementos.

        });
        // Con la paginación lo haremos de la siguiente forma:
        // Devolviendo también la URL a l
        return response()->json(['status'=>'ok', 'siguiente'=>$usuarios->nextPageUrl(),'anterior'=>$usuarios->previousPageUrl(),'data'=>$usuarios->items()],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return "Se muestra formulario para crear un usuarios.";
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
        //aqui para guardar
        //Instanciamos la clase Usuario
        $usuario = new Usuario;
        //Declaramos el nombre con el nombre enviado en el request
        //$usuario->descripcion = $request->descripcion;
        $usuario->nombre = $request->nombre;
        $usuario->nombre = $request->nombre;
        $usuario->apellido1 = $request->apellido1;
        $usuario->apellido2 = $request->apellido2;
        $usuario->fechaNacimiento = $request->fechaNacimiento;
        $usuario->exento = $request->exento;
        $usuario->bolsaHora = $request->bolsaHora;
        $usuario->reputacion = $request->reputacion;
        $usuario->administrador = $request->administrador;
        $usuario->email = $request->email;
        $usuario->password = $request->password;
        //Guardamos el cambio en nuestro modelo

        $usuario->save();
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
 //       return Usuario::where('usuario_id', $id)->get();
        $usuario=Usuario::find($id);
        if (! $usuario)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un usuario con ese código.'])],404);
        }

        $data = (object)[];
        $data->usuario = $usuario;
        $data->habilidades = $usuario->habilidades()->get();

      //  return response()->json(['status'=>'ok','data'=>$data],200);


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
        return "Se muestra formulario para editar Usuario con id: $id";

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
        return "Se edita un usuarios.";

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
        return "Se borra un usuarios.";

    }
}
