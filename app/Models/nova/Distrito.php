<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Distrito
 * @package App\Models
 * @version February 24, 2018, 8:23 am UTC
 *
 * @property string nombre_dist
 * @property integer provincia_id
 * @property integer departamento_id
 */
class Distrito extends Model
{
    use SoftDeletes;

    public $table = 'distritos';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'nombre_dist',
        'provincia_id',
        'departamento_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre_dist' => 'string',
        'provincia_id' => 'integer',
        'departamento_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
