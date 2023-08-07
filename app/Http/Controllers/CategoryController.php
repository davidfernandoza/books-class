<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\Category\CategoryRequest;


class CategoryController extends Controller
{
	public function index()
	{
		return view('category.index');
	}

	public function getAll()
	{
		$categories = Category::get();
		return response()->json(['categories' => $categories], 200);
	}

	public function store(CategoryRequest $request)
	{
		$category = new Category($request->all());
		$category->save();
		return response()->json([], 200);
	}

	public function show(Category $category)
	{
		return response()->json(['category' => $category], 200);
	}

	public function update(CategoryRequest $request, Category $category)
	{
		$category->update($request->all());
		return response()->json([], 204);
	}

	public function destroy(Category $category)
	{
		$category->delete();
		return response()->json([], 204);
	}
}
