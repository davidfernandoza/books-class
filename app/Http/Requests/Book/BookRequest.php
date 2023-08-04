<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{

	public function authorize()
	{
		return true;
	}


	public function rules()
	{
		$rules = [
			'title' => ['required', 'string'],
			'description' => ['required', 'string'],
			'stock' => ['required', 'numeric'],
			'author_id' => ['required', 'numeric'],
			'category_id' => ['required', 'numeric'],
		];

		return $rules;
	}

	public function messages()
	{
		return [
			'name.required' => 'El nombre es requerido',
			'name.string' => 'El nombre debe de ser valido',
		];
	}
}
