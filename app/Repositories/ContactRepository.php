<?php

namespace App\Repositories;

use App\Models\Contact;

class ContactRepository extends AppBaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'created_at',
        'updated_at',
        'name',
        'email',
        'phone',
        'content'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Contact::class;
    }
}
