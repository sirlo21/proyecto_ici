<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Departamento
 * @package App\Models
 * @version February 24, 2018, 8:19 am UTC
 *
 * @property string nombre_dpto
 */
class Departamento extends Model
{
    use SoftDeletes;

    public $table = 'departamentos';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'nombre_dpto'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre_dpto' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
