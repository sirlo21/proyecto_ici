<?php

namespace App\Repositories;

use App\Models\ici;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class iciRepository
 * @package App\Repositories
 * @version February 28, 2018, 5:23 pm UTC
 *
 * @method ici findWithoutFail($id, $columns = ['*'])
 * @method ici find($id, $columns = ['*'])
 * @method ici first($columns = ['*'])
*/
class iciRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'mes',
        'ano',
        'establecimiento_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ici::class;
    }
}
