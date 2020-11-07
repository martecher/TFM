<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habilidad extends Model
{
	protected $table='habilidades';
    use HasFactory;
    protected $primaryKey = 'habilidad_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'descripcion',
		  'horasEstipuladas',
		  'categoria_Habilidad_id'
    ];
  // Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
    protected $hidden = ['created_at','updated_at'];

    // Relación de habilidad con categoria:
    public function categoria()
    {
        // 1 habilidad a un categoria.
        // $this hace referencia al objeto que tengamos en ese momento de habilidad.
        return $this->belongsTo('App\CategoriaHabilidad');
    }
    public function usuarios()
    {
        return $this->belongsToMany('App\Models\Usuario', 'habilidad_usuario', 'habilidad_id', 'usuario_id');
    }

}
