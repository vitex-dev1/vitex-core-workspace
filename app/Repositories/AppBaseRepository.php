<?php

namespace App\Repositories;

use InfyOm\Generator\Common\BaseRepository;

/**
 * Class AppBaseRepository
 * @package App\Repositories
 */
class AppBaseRepository extends BaseRepository
{

    /** @var bool $error */
    public $error = false;
    /** @var array $errorMessages */
    public $errorMessages = [];

    /**
     * Record model
     * @var $record
     */
    public $record;

    /**
     * Override from parent to fix duplicate fire event when save 2 times
     *
     * @param array $attributes
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function create(array $attributes)
    {
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $model = \Prettus\Repository\Eloquent\BaseRepository::create($attributes);
        $this->skipPresenter($temporarySkipPresenter);

        return $this->parserResult($model);
    }

    /**
     * Override from parent to fix duplicate fire event when save 2 times
     *
     * @param array $attributes
     * @param int $id
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(array $attributes, $id)
    {
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $model = \Prettus\Repository\Eloquent\BaseRepository::update($attributes, $id);
        $this->skipPresenter($temporarySkipPresenter);

        return $this->parserResult($model);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        // TODO: Implement model() method.
    }

    /**
     * Get Page in paging request
     *
     * @param \Illuminate\Http\Request $request
     * @return int
     */
    public function getPagingPage(\Illuminate\Http\Request $request)
    {
        $page = (int)$request->get('page', 1);

        return $page;
    }

    /**
     * Get Limit in paging request
     *
     * @param \Illuminate\Http\Request $request
     * @param int $default
     * @return int
     */
    public function getPagingLimit(\Illuminate\Http\Request $request, $default = 20)
    {
        // $limit = limit or per_page
        $perPage = (int)$request->get('per_page');
        $limit = (int)$request->get('limit', $perPage);
        $limit = (empty($limit)) ? $default : $limit;

        return $limit;
    }

    /**
     * Parse custom filter and search
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Request
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function parserCustomFilter(\Illuminate\Http\Request $request)
    {
        $arrRequests = $request->all();
        $search = '';
        $searchFields = '';
        $searchFieldName = null;
        /** @var \App\Models\AppModel $model */
        $model = $this->makeModel();

        if (\Schema::hasColumn($model->getTable(), 'name')) {
            $searchFieldName = 'name';
        } else if (\Schema::hasColumn($model->getTable(), 'title')) {
            $searchFieldName = 'title';
        } else {
            // Default is ID column, prevent error when missing field
            $searchFieldName = $model->getKeyName();
        }

        foreach ($arrRequests as $field => $value) {
            if (!$request->has($field)) {
                // When invalid field input
                continue;
            }

            if ($field == 'keyword') {
                $search = $value;
                $searchFields = "{$searchFieldName}:like";
            } else {
                if (\Schema::hasColumn($model->getTable(), $field)) {
                    // If not found the field
                    continue;
                }

                $search .= ";{$field}:{$value}";
                $searchFields .= ";{$field}:=";
            }
        }

        // Push filter and search to request
        $request->merge([
            'searchJoin' => 'and',
            'search' => $search,
            'searchFields' => $searchFields,
        ]);

        return $request;
    }

}
