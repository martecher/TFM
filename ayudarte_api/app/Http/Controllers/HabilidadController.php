<?php

namespace App\Http\Controllers;
// Activamos uso de caché.
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\Habilidad;
use App\Models\CategoriaHabilidad;
class HabilidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    //version paginada
    $habilidades=Cache::remember('cachehabilidades',15/60,function()
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
            return Habilidad::simplePaginate(10);  // Paginamos cada 10 elementos.

        });
        // Con la paginación lo haremos de la siguiente forma:
        // Devolviendo también la URL a l
        return response()->json(['status'=>'ok', 'siguiente'=>$habilidades->nextPageUrl(),'anterior'=>$habilidades->previousPageUrl(),'data'=>$habilidades->items()],200);

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

        $habilidad = new Habilidad;
        //Declaramos el nombre con el nombre enviado en el request
        $habilidad->descripcion = $request->descripcion;
        $habilidad->horasEstipuladas = $request->horasEstipuladas;
        $habilidad->categoria_Habilidad_id = $request->categoria_Habilidad_id;

        //Guardamos el cambio en nuestro modelo
        $habilidad->save();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $habilidad=Habilidad::find($id);
         if (! $habilidad)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra una Habilidad con ese código.'])],404);
        }



        $data = (object)[];
        $data->descripcion = $habilidad->descripcion;
        $data->horasEstipuladas = $habilidad->horasEstipuladas;
        $categoriaHabilidad = CategoriaHabilidad::find($habilidad->categoria_Habilidad_id);
        $data->categoria = $categoriaHabilidad;
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
       $habilidad=Habilidad::find($id);
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
        $habilidad = new Habilidad;
        $habilidad = Habilidad::findOrFail($id);
        $habilidad->update($request->all());
        return $habilidad;
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

    public function categoriaId( $id)
    {
        
        $habilidades=Habilidad::where('categoria_Habilidad_id', $id)->get();
        if (! $habilidades)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'HabilidadController.categoriaId: No se encuentran actihabilidadesvidades a listar.'])],404);
        }
        $data = (object)[];
        $data->habilidades = $habilidades;
        return response()->json(['status'=>'ok','data'=>$habilidades],200);


    }
}
