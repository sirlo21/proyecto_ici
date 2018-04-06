<?php

namespace App\Repositories;

use App\Models\Farmacia;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class FarmaciaRepository
 * @package App\Repositories
 * @version March 23, 2018, 11:56 am UTC
 *
 * @method Farmacia findWithoutFail($id, $columns = ['*'])
 * @method Farmacia find($id, $columns = ['*'])
 * @method Farmacia first($columns = ['*'])
*/
class FarmaciaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'descripcion',
        'establecimiento_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Farmacia::class;
    }
}
