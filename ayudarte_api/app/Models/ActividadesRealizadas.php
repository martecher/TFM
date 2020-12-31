<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActividadesRealizadas extends Model
{
	protected $table='actividadesRealizadas';
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'observacion',
		'horasReales',
		'valoracion',
		'usuarioSolicita_id',
        'usuarioRealiza_id',
		'habilidad_id',
        'puntuacionSolicita',
        'finalizada'
    ];
      // Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
    protected $hidden = ['created_at','updated_at'];

    public function usuarioSolicita()
    {
        // 1 actividad tiene un usuario solicita
         return $this->belongsTo('App\Models\Usuario', 'usuarioSolicita_id', 'usuario_id');
    }
        public function usuarioRealiza()
    {
        // 1 actividad tiene un usuario que realiza
         return $this->belongsTo('App\Models\Usuario', 'usuarioRealiza_id', 'usuario_id');
    }
    public function habilidad()
    {
        // 1 actividad tiene una habilidad
         return $this->belongsTo('App\Models\Habilidad','habilidad_id');

    }
}
