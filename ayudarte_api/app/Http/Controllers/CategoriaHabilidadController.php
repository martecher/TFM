<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoriaHabilidad;
use App\Models\Habilidad;
// Activamos uso de caché.
use Illuminate\Support\Facades\Cache;
class CategoriaHabilidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    //version paginada
    $categoriasHabilidades=Cache::remember('cachecategoriasHabilidades',15/60,function()
        {

            // Este método paginate() está orientado a interfaces gráficas.
            // Paginator tiene un método llamado render() que permite construir
            // los enlaces a página siguiente, anterior, etc..
            // Para la API RESTFUL usaremos un método más sencillo llamado simplePaginate() que
            // aporta la misma funcionalidad
            return CategoriaHabilidad::simplePaginate(10);  // Paginamos cada 10 elementos.

        });
        // Con la paginación lo haremos de la siguiente forma:
        // Devolviendo también la URL a l
        return response()->json(['status'=>'ok', 'siguiente'=>$categoriasHabilidades->nextPageUrl(),'anterior'=>$categoriasHabilidades->previousPageUrl(),'data'=>$categoriasHabilidades->items()],200);

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
        //aqui para guardar
        //Instanciamos la clase CategoriaHabilidad
        $categoriaHabilidad = new CategoriaHabilidad;
        //Declaramos el nombre con el nombre enviado en el request
        $categoriaHabilidad->descripcion = $request->descripcion;
        //Guardamos el cambio en nuestro modelo
        $categoriaHabilidad->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

       $categoria=CategoriaHabilidad::find($id);
         if (! $categoria)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra una categoria con ese código.'])],404);
        }

//si quiero devolver tb las habilidades de esa categoria
//lo que tengo que hacer es ir construyento la variable data
// en vez de meterla directamente en el response
    $data = (object)[];
    $data->descripcion = $categoria->descripcion;
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
        //aqui para actualizar
        //Instanciamos la clase CategoriaHabilidad
        $categoriaHabilidad = new CategoriaHabilidad;
        $categoriaHabilidad = CategoriaHabilidad::findOrFail($id);
        $categoriaHabilidad->update($request->all());
        return $categoriaHabilidad;
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
        CategoriaHabilidad::find($id)->delete();
        return 204;
    }
}
