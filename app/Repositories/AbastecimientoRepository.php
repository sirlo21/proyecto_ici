<?php

namespace App\Repositories;

use App\Models\Abastecimiento;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class AbastecimientoRepository
 * @package App\Repositories
 * @version February 26, 2018, 11:48 am UTC
 *
 * @method Abastecimiento findWithoutFail($id, $columns = ['*'])
 * @method Abastecimiento find($id, $columns = ['*'])
 * @method Abastecimiento first($columns = ['*'])
*/
class AbastecimientoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'anomes',
        'cod_establecimiento',
        'petitorio_id',
        'descripcion',
        'precio',
        'cpma',
        'stock_inicial',
        'unidad_ingreso',
        'valor_ingreso',
        'unidad_consumo',
        'valor_ingreso',
        'unidad_consumo',
        'valor_consumo',
        'transferencia',
        'merma',
        'total_salidas',
        'stock_final',
        'fecha_vencimiento',
        'nivel_stock',
        'automatico_requerimiento',
        'ajuste_requerimiento',
        'unidades_sobrestock'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Abastecimiento::class;
    }
}
