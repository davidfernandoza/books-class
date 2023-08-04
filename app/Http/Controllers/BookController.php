<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\Book\BookRequest;


class BookController extends Controller
{
	public function home()
	{
		$books = Book::get();
		return view('index', compact('books'));
	}

	public function index()
	{
		$books = Book::with('author', 'category')->get();
		$categories = Category::get();
		$authors = Author::get();
		return view('book.index', compact('books', 'categories', 'authors'));
	}

	public function store(BookRequest $request)
	{
		$book = new Book($request->all());
		$book->save();
		return response()->json([], 201);
	}


	public function update(BookRequest $request, Book $book)
	{
		$book->update($request->all());
		return response()->json([], 204);
	}

	public function destroy(Book $book)
	{
		$book->delete();
		return response()->json([], 204);
	}
}
