<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Farmacia
 * @package App\Models
 * @version March 23, 2018, 11:56 am UTC
 *
 * @property string descripcion
 * @property integer establecimiento_id
 */
class Farmacia extends Model
{
    use SoftDeletes;

    public $table = 'farmacias';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'descripcion',
        'establecimiento_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'descripcion' => 'string',
        'establecimiento_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function petitorios(){
        return $this->belongsToMany('App\Models\Petitorio');
    }

    public function establecimientos(){
        return $this->belongsTo('App\Models\Establecimiento','establecimiento_id');
    }
    
}
