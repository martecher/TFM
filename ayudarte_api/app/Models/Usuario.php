<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Habilidad;

class Usuario extends Authenticatable
{
   protected $table='usuarios';
   use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'usuario_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
		'apellido1',
        'apellido2',
        'fechaNacimiento',
        'exento',
        'bolsaHora',
        'reputacion',
        'administrador',
        'email',
        'password',
        'numeroVotaciones',
        'numVotos5',
        'numVotos4',
        'numVotos3',
        'numVotos2',
        'numVotos1'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at'
    ];



    /**
     * The attributes that should be cast to native types.
     *
     *  @ var array

    protected $casts = [
        'email_verified_at' => 'datetime',
     ];
      */
    public function actividadesRealizadas()
    {
        return $this->hasMany('App\Models\ActividadesRealizadas', 'usuarioRealiza_id');
    }

    public function actividadesSolicitadas()
    {
        return $this->hasMany('App\Models\ActividadesRealizadas', 'usuarioSolicita_id');
    }

    public function habilidades()
    {
		return $this->belongsToMany('App\Models\Habilidad', 'habilidad_usuario', 'usuario_id', 'habilidad_id');
    }
}
