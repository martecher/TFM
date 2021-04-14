<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaHabilidad extends Model
{
	protected $table='categorias_habilidades';

    use HasFactory;
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'descripcion'
    ];
  // Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
    protected $hidden = ['created_at','updated_at'];

    public function habilidades()
    {
        // 1 categoria tiene muchas habilidades.
        return $this->hasMany('App\Models\Habilidad');
    }
}
