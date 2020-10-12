<?php

namespace App\Repositories;

use App\Models\Category;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class CategoryRepository.
 *
 * @package namespace App\Repositories;
 */
class CategoryRepository extends AppBaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Category::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
