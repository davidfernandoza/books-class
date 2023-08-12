<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use App\Http\Trait\UploadFiles;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Book\BookStoreRequest;
use App\Http\Requests\Book\BookUpdateRequest;


class BookController extends Controller
{
	use UploadFiles;

	public function home()
	{
		$books = Book::with('author', 'category', 'file')->get();
		return view('index', compact('books'));
	}

	public function index()
	{
		$books = Book::with('author', 'category', 'file')->get();
		$categories = Category::get();
		$authors = Author::get();
		return view('book.index', compact('books', 'categories', 'authors'));
	}

	public function store(BookStoreRequest $request)
	{
		try {
			DB::beginTransaction();
			$book = new Book($request->all());
			$book->save();
			$this->uploadFile($book, $request);
			DB::commit();
			return response()->json([], 201);
		} catch (\Throwable $th) {
			DB::rollback();
			throw $th;
		}
	}

	public function update(BookUpdateRequest $request, Book $book)
	{
		try {
			DB::beginTransaction();
			$book->update($request->all());
			$this->uploadFile($book, $request);
			DB::commit();
			return response()->json([], 204);
		} catch (\Throwable $th) {
			DB::rollback();
			throw $th;
		}
	}

	public function destroy(Book $book)
	{
		try {
			DB::beginTransaction();
			$this->deleteFile($book);
			$book->delete();
			DB::commit();
			return response()->json([], 204);
		} catch (\Throwable $th) {
			DB::rollback();
			throw $th;
		}
	}
}
