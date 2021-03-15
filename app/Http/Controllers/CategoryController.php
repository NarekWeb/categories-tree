<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    private $childes = [];

    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index()
    {
        $mainCategories = Category::where('parent_id', 0)->get();
        $categories = Category::where('parent_id', '<>', 0)->get();

        return view('categories.index')->with([
            'mainCategories' => $mainCategories,
            'categories'     => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $categories = Category::all();

        return view('categories.create')->with([
            'categories' => $categories,
            'parentId'   => $request->exists('parent_id') ? $request->input('parent_id') : null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(CategoryCreateRequest $request)
    {
        Category::create([
            'name'      => $request->input('name'),
            'parent_id' => empty($request->input('parent_id')) ? 0 : $request->input('parent_id'),
        ]);

        return redirect()->route('categories')->with([
            'success' => 'Category was created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Category $category
     * @return Factory|View
     */
    public function edit(Category $category)
    {
        $categories = Category::whereNotIn('id', $this->childes($category))->where('id', '<>', $category->id)->get();

        return view('categories.edit')->with([
            'categories' => $categories,
            'category'   => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Category                 $category
     * @return Response
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $category->update([
            'parent_id' => $request->exists('new_parent_id') ? $request->input('new_parent_id') : $category->parent_id,
            'name'      => $request->exists('name') ? $request->input('name') : $category->name,
        ]);

        return response()->json([
            'success' => 'Category was changed successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return Response
     */
    public function destroy(Category $category)
    {
        //
    }

    /**
     * @param $category
     * @return array
     */
    public function childes($category)
    {
        $this->childes = array_merge($this->childes,$category->subcategories()->pluck('id')->toArray()) ;
        if (!$category->subcategories->count()){
            return $this->childes;
        }

        if ($category->subcategories->count()){
            foreach ($category->subcategories as $subcategory){
                $this->childes($subcategory);
            }
        }

        return $this->childes;
    }
}
