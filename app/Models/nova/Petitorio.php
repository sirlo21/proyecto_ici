<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Petitorio
 * @package App\Models
 * @version February 24, 2018, 4:40 pm UTC
 *
 * @property integer codigo
 * @property string principio_activo
 * @property string descripcion
 * @property float precio
 * @property string concentracion
 * @property string form_farm
 * @property string presentacion
 * @property integer tipo_dispositivo_medicos_id
 * @property integer unidad_medida_id
 * @property integer nivel_id
 * @property integer tipo_uso_id
 */
class Petitorio extends Model
{
    use SoftDeletes;

    public $table = 'petitorios';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'codigo',
        'principio_activo',
        'descripcion',
        'precio',
        'concentracion',
        'form_farm',
        'presentacion',
        'tipo_dispositivo_medicos_id',
        'unidad_medida_id',
        'nivel_id',
        'tipo_uso_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'codigo' => 'integer',
        'principio_activo' => 'string',
        'descripcion' => 'string',
        'precio' => 'float',
        'concentracion' => 'string',
        'form_farm' => 'string',
        'presentacion' => 'string',
        'tipo_dispositivo_medicos_id' => 'integer',
        'unidad_medida_id' => 'integer',
        'nivel_id' => 'integer',
        'tipo_uso_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
