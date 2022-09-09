<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CommentRequest extends FormRequest
{
    protected function prepareForValidation(){

        if (Request::isMethod('post')){
            $this->merge([

                'user_id' => Auth::id(),
            ]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'text' => 'required',
            'ticket_id' => 'required|integer',
            'user_id' => 'integer|exists:users,id',
        ];
    }
}
