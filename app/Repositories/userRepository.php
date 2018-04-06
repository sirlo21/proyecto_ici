<?php

namespace App\Repositories;

use App\Models\user;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class userRepository
 * @package App\Repositories
 * @version February 24, 2018, 7:30 am UTC
 *
 * @method user findWithoutFail($id, $columns = ['*'])
 * @method user find($id, $columns = ['*'])
 * @method user first($columns = ['*'])
*/
class userRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'email',
        'password'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return user::class;
    }
}
