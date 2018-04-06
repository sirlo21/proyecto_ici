<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Abastecimiento
 * @package App\Models
 * @version February 26, 2018, 11:48 am UTC
 *
 * @property string anomes
 * @property string cod_establecimiento
 * @property integer petitorio_id
 * @property string descripcion
 * @property double precio
 * @property integer cpma
 * @property integer stock_inicial
 * @property integer unidad_ingreso
 * @property integer valor_ingreso
 * @property integer unidad_consumo
 * @property integer valor_ingreso
 * @property integer unidad_consumo
 * @property integer valor_consumo
 * @property integer transferencia
 * @property integer merma
 * @property integer total_salidas
 * @property integer stock_final
 * @property string fecha_vencimiento
 * @property integer nivel_stock
 * @property integer automatico_requerimiento
 * @property integer ajuste_requerimiento
 * @property integer unidades_sobrestock
 */
class Abastecimiento extends Model
{
    use SoftDeletes;

    public $table = 'abastecimientos';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'ici_id'=>'integer',
        'anomes' => 'string',
        'establecimiento_id' => 'integer',
        'cod_establecimiento' => 'integer',
        'nombre_establecimiento' => 'string',
        'tipo_dispositivo_id' => 'integer',
        'petitorio_id' => 'integer',
        'cod_petitorio' => 'integer',
        'descripcion' => 'string',
        'precio' => 'double',
        'cpma' => 'integer',
        'stock_inicial' => 'integer',
        'almacen_central' => 'integer',
        'ingreso_proveedor' => 'integer',
        'ingreso_transferencia' => 'integer',
        'unidad_ingreso' => 'integer',
        'valor_ingreso' => 'integer',
        'unidad_consumo' => 'integer',
        'valor_consumo' => 'integer',
        'salida_transferencia' => 'integer',
        'merma' => 'integer',
        'total_salidas' => 'integer',
        'stock_final' => 'integer',
        'fecha_vencimiento' => 'string',
        'disponibilidad' => 'integer',
        'unidades_sobrestock' => 'integer',
        'valor_sobrestock' => 'integer'
              
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'ici_id'=>'integer',
        'anomes' => 'string',
        'establecimiento_id' => 'integer',
        'cod_establecimiento' => 'integer',
        'nombre_establecimiento' => 'string',
        'tipo_dispositivo_id' => 'integer',
        'petitorio_id' => 'integer',
        'cod_petitorio' => 'integer',
        'descripcion' => 'string',
        'precio' => 'double',
        'cpma' => 'integer',
        'stock_inicial' => 'integer',
        'almacen_central' => 'integer',
        'ingreso_proveedor' => 'integer',
        'ingreso_transferencia' => 'integer',
        'unidad_ingreso' => 'integer',
        'valor_ingreso' => 'integer',
        'unidad_consumo' => 'integer',
        'valor_consumo' => 'integer',
        'salida_transferencia' => 'integer',
        'merma' => 'integer',
        'total_salidas' => 'integer',
        'stock_final' => 'integer',
        'fecha_vencimiento' => 'string',
        'disponibilidad' => 'integer',
        'unidades_sobrestock' => 'integer',
        'valor_sobrestock' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function tipo_dispositivo(){

        return $this->belongsTo('App\Models\TipoDispositivoMedico','tipo_dispositivo_medico_id');
    }
}
