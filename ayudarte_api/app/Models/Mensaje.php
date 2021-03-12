<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    use HasFactory;
    protected $table='mensajes';
    protected $fillable = [
        'texto',
		'leido',
		'usuarioEnvia_id',
        'usuarioReacibe_id',
        'orden',
		'tarea_id'
    ];
  // Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
    protected $hidden = ['created_at','updated_at'];

     
    public function usuarioEnvia()
    {
        // 1 actividad tiene un usuario solicita
         return $this->belongsTo('App\Models\Usuario', 'usuarioEnvia_id', 'usuario_id');
    }

    public function usuarioRecibe()
    {
        // 1 actividad tiene un usuario solicita
         return $this->belongsTo('App\Models\Usuario', 'usuarioReacibe_id', 'usuario_id');
    }

    public function tarea()
    {
        // 1 actividad tiene un usuario solicita
         return $this->belongsTo('App\Models\ActividadesRealizadas', 'tarea_id', 'usuario_id');
    }
}
