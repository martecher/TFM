<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


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
        $usuario->apellido1 = $request->apellido1;
        $usuario->apellido2 = $request->apellido2;
 //       $date = date('d/m/Y', strtotime($request->fechaNacimiento));
        $date = date('Y-m-d', strtotime($request->fechaNacimiento));

        $usuario->fechaNacimiento = $date;
        $usuario->exento = $request->exento;
        $usuario->bolsaHora = $request->bolsaHora;
        $usuario->reputacion = $request->reputacion;
        $usuario->administrador = $request->administrador;
        $usuario->email = $request->email;
        $usuario->password = Hash::make($request->password);

//Hash::check($request->password, $user->password, []))
        //Guardamos el cambio en nuestro modelo

        $usuario->save();
        $usuario->createToken('usuario');
 
        return response()->json(['status'=>'ok','data'=>$usuario],200);

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
        $data->activiadesRealizadas = $usuario->actividadesRealizadas()->get();
        $data->activiadesSolicitadas = $usuario->actividadesSolicitadas()->get();

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
        $usuario = new Usuario;
        $usuario = Usuario::findOrFail($id);
        $usuario->update($request->all());

        $usuario->password = Hash::make($request->password);
        $usuario->save();
        return $usuario;
    }

    public function asignarDesasignarHabilidad(Request $request, $idUsuario)
    {
        $usuario = new Usuario;
        $usuario = Usuario::findOrFail($idUsuario);
        //si la accion es igual a 1 asigno la habilidad
         //si la accion es <> a 1 desasigno la habilidad
         //, $idHabilidad, $accion
           
        if($request->accion  =='1'){
            $usuario->habilidades()->attach($request->idHabilidad);
        }else{
            $usuario->habilidades()->detach($request->idHabilidad);
        }
        $usuario->save();
        return response()->json(['status'=>'ok','data'=>$usuario],200);
    }

    public function updateNoPass(Request $request, $id)
    {
        $usuario = new Usuario;
        $usuario = Usuario::findOrFail($id);
        $usuario->update($request->all());
        return response()->json(['status'=>'ok','data'=>$usuario],200);
    }

    public function actualizarPassword(Request $request, $id)
    {
        $usuario = new Usuario;
        $usuario = Usuario::findOrFail($id);
        //comprobamos si la contraseña actual coincide con la introducida
        //= Hash::make($request->password);
        $passActual = $usuario->password;
        $passActualRecibida = Hash::make($request->passActual);
        if( $passActual == $passActualRecibida  ){
            $mensaje = "La contraseña introducida no coincide con la actual";
            return response()->json(['status'=>'error','data'=>$mensaje],401);
        }else{
            $usuario->password = Hash::make($request->passNueva);
            $usuario->save();
            $mensaje = "Contraseña actualizada correctamente";
            return response()->json(['status'=>'ok','data'=>$mensaje],200);
        }
    }
    
    public function actualizarBolsa(Request $request,$idUsuario)
    {
        $usuario = new Usuario;
        $usuario = Usuario::findOrFail($idUsuario);
        if(  $request->signo=='+') {
            $usuario->bolsaHora = (float)$usuario->bolsaHora + (float)$request->horas; 
        }else{
            $usuario->bolsaHora = (float)$usuario->bolsaHora - (float)$request->horas;
        }
        $usuario->update($request->all());
        return response()->json(['status'=>'ok','data'=>$usuario],200);
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

    public function ranking()
    {
        $usuarios=Usuario::Where('reputacion','>=', 0)->where('nombre','!=' ,'Sistema')->with('habilidades')->orderBy('reputacion', 'desc')->get();
        if (! $usuarios)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'UsuariosController.ranking: No se encuentran usuarios para listar.'])],404);
        }
        $data = (object)[];
        $data->usuarios = $usuarios;
        return response()->json(['status'=>'ok','data'=>$usuarios],200);

    }
}
