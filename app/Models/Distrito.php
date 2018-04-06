<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Distrito
 * @package App\Models
 * @version February 7, 2018, 1:18 am UTC
 *
 * @property string nombre_dist
 * @property integer provincia_id
 */
class Distrito extends Model
{
    use SoftDeletes;

    public $table = 'distritos';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'nombre_dist',
        'provincia_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre_dist' => 'string',
        'provincia_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function provincia(){

        return $this->belongsTo('App\Models\Provincia','provincia_id');
    }
    public function departamento(){

        return $this->belongsTo('App\Models\Departamento','departamento_id');
    }
    
}
