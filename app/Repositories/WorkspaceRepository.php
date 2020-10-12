<?php

namespace App\Repositories;

use App\Models\Workspace;
use Illuminate\Support\Facades\Lang;

class WorkspaceRepository extends AppBaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'name',
        'active',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Workspace::class;
    }

    /**
     * Get Shipment fulfillment options
     *
     * @return array
     */
    public function getFulfillmentOptions()
    {
        return Lang::get('workspace.fulfillment_options');
    }

}
