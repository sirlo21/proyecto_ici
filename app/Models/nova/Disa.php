<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Disa
 * @package App\Models
 * @version February 24, 2018, 8:18 am UTC
 *
 * @property integer codigo
 * @property string descripcion
 */
class Disa extends Model
{
    use SoftDeletes;

    public $table = 'disas';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'codigo',
        'descripcion'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'codigo' => 'integer',
        'descripcion' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
