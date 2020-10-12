<?php

namespace App\Models;

use App\Models\AppModel;

/**
 * Class Contact
 * @package App
 * @version January 3, 2019, 4:48 pm +07
 */
class Contact extends AppModel
{

    public $table = 'contacts';
    
    public $timestamps = false;

    public $fillable = [
        'created_at',
        'updated_at',
        'name',
        'email',
        'phone',
        'content'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'content' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
