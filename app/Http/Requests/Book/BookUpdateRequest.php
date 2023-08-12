<?php

namespace App\Http\Requests\Book;


use App\Http\Requests\Book\BookStoreRequest;

class BookUpdateRequest extends BookStoreRequest
{
	public function rules()
	{
		$this->rules['file'] = ['nullable', 'image'];
		return $this->rules;
	}
}
