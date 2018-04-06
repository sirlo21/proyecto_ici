<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Ici
 * @package App\Models
 * @version February 28, 2018, 8:01 pm UTC
 *
 * @property string mes
 * @property string ano
 * @property string establecimiento_id
 * @property string nombre_Establecimiento
 */
class Ici extends Model
{
    use SoftDeletes;

    public $table = 'icis';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'mes',
        'ano',
        'establecimiento_id',
        'nombre_Establecimiento'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'mes' => 'string',
        'ano' => 'string',
        'establecimiento_id' => 'string',
        'nombre_Establecimiento' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
/*
    public function establecimiento(){

        return $this->belongsTo('App\Models\Establecimiento','establecimiento_id');
    }
*/
    public function establecimientos(){

        return $this->belongsToMany('App\Models\Establecimiento');
    }

    public function farmacias(){

        return $this->belongsToMany('App\Models\Farmacia');
    }

    public function anos(){

        return $this->belongsTo('App\Models\Year','year_id');
    }

    public function meses(){

        return $this->belongsTo('App\Models\Mes','mes_id');
    }
    
    public function petitorios(){
        return $this->belongsToMany('App\Models\Petitorio');
    }
    
    
}
