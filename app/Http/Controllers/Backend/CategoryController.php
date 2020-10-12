<?php

namespace App\Http\Controllers\Backend;

use App\CategoryTranslation;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Flash;
use Illuminate\Support\Facades\Lang;
use Prettus\Repository\Criteria\RequestCriteria;

class CategoryController extends Controller
{
    private $categoryRepo;
    private $messageLang;
    private $prefix;

    /**
     * Display a listing of the resource.
     *
     * @param CategoryRepository $categoryRepo
     */

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
        $this->messageLang = 'messages.category';
        $this->prefix = 'admin.categories';

        parent::__construct();
    }

    public function index(Request $request)
    {
        // Order by order column ascending
//        $request->merge([
//            'orderBy' => 'name',
//            'sortedBy' => 'asc',
//        ]);
//        $this->categoryRepo->pushCriteria(new RequestCriteria($request));
        $categories = $this->categoryRepo->paginate();

        return view($this->prefix . '.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = $this->categoryRepo->makeModel();

        return view($this->prefix . '.create', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->categoryRepo->create($request->except('_token'));

        Flash::success(Lang::get('messages.category.created_successfully'));

        return redirect()->route($this->prefix . '.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = $this->categoryRepo->findWithoutFail($id);
        if (empty($category)) {
            Flash::error(trans($this->messageLang.'.not_found'));

            return redirect()->route($this->prefix . '.index');
        }

        return view($this->prefix . '.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->except(['_token', '_method']);
        $this->categoryRepo->update($data, $id);

        Flash::success(Lang::get('messages.category.updated_successfully'));

        return redirect()->route($this->prefix . '.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = $this->categoryRepo->findWithoutFail($id);

        if (empty($category)) {
            Flash::error(trans($this->messageLang.'.not_found'));

            return redirect()->route($this->prefix . '.index');
        }

        $this->categoryRepo->delete($id);
        Flash::success(trans($this->messageLang.'.deleted_successfully'));

        return redirect()->route($this->prefix . '.index');
    }
}
