<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Nivel;
use App\Models\TipoDispositivoMedico;
use App\Models\TipoUso;
use App\Models\UnidadMedida;


/**
 * Class petitorio
 * @package App\Models
 * @version February 3, 2018, 3:02 am UTC
 *
 * @property integer id_tipo_dispositivo
 * @property string codigo
 * @property string principio_activo
 * @property string concentracion
 * @property string form_farm
 * @property string presentacion
 * @property integer unidad_medida
 * @property integer id_nivel
 * @property integer id_tipo_uso
 * @property string descripcion
 */
class Petitorio extends Model
{
    use SoftDeletes;

    public $table = 'petitorios';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'tipo_dispositivo_medicos_id',
        'codigo_petitorio',
        'principio_activo',
        'concentracion',
        'form_farm',
        'presentacion',
        'unidad_medida_id',
        'nivel_id',
        'tipo_uso_id',
        'descripcion',
        'precio',

    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'tipo_dispositivo_id' => 'integer',
        'codigo' => 'string',
        'principio_activo' => 'string',
        'concentracion' => 'string',
        'form_farm' => 'string',
        'presentacion' => 'string',
        'unidad_medida_id' => 'integer',
        'nivel_id' => 'integer',
        'tipo_uso_id' => 'integer',
        'descripcion' => 'string',
        'precio' => 'float',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    


  
    public function pet_nivel(){

        return $this->belongsTo('App\Models\Nivel','nivel_id');
    }
    
    public function pet_unidad_medida(){

        return $this->belongsTo('App\Models\UnidadMedida','unidad_medida_id');
    }

    public function pet_tipo_uso(){

        return $this->belongsTo('App\Models\TipoUso','tipo_uso_id');
    }
    
    public function pet_tipo_dispositivo(){

        return $this->belongsTo('App\Models\TipoDispositivoMedico','tipo_dispositivo_medico_id');
    }

    public function establecimientos(){

        return $this->belongsToMany('App\Models\Establecimiento');
    }
    
    
}
