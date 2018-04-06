<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Establecimiento
 * @package App\Models
 * @version February 24, 2018, 8:10 am UTC
 *
 * @property integer codigo
 * @property string nombre_establecimiento
 * @property integer region_id
 * @property integer nivel_id
 * @property integer categoria_id
 * @property integer tipo_establecimiento_id
 * @property integer tipo_internamiento_id
 * @property integer departamento_id
 * @property integer provincia_id
 * @property integer distrito_id
 * @property integer disa_id
 * @property string norte
 * @property string este
 */
class Establecimiento extends Model
{
    use SoftDeletes;

    public $table = 'establecimientos';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'codigo',
        'nombre_establecimiento',
        'region_id',
        'nivel_id',
        'categoria_id',
        'tipo_establecimiento_id',
        'tipo_internamiento_id',
        'departamento_id',
        'provincia_id',
        'distrito_id',
        'disa_id',
        'norte',
        'este'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'codigo' => 'integer',
        'nombre_establecimiento' => 'string',
        'region_id' => 'integer',
        'nivel_id' => 'integer',
        'categoria_id' => 'integer',
        'tipo_establecimiento_id' => 'integer',
        'tipo_internamiento_id' => 'integer',
        'departamento_id' => 'integer',
        'provincia_id' => 'integer',
        'distrito_id' => 'integer',
        'disa_id' => 'integer',
        'norte' => 'string',
        'este' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
