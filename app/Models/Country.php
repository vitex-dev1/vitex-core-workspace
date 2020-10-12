<?php

namespace App\Models;

use App\Models\AppModel;

/**
 * Class Country
 * @package App
 * @version December 28, 2018, 3:58 am UTC
 */
class Country extends AppModel
{

    public $table = 'countries';
    
    public $timestamps = false;



    public $fillable = [
        'created_at',
        'updated_at',
        'active',
        'name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'active' => 'boolean',
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
