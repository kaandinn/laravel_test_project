<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Enums\TicketStatusEnum;
use App\Enums\UserRoleEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TicketRequest extends FormRequest
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

        $rules = [
            'user_id' => 'integer|exists:users,id',
        ];

        if (Gate::check('is_admin_permission') and Request::isMethod('put')) {
             $rules = [
                'status' => Rule::in(TicketStatusEnum::$statuses),
                'user_id' => 'integer|exists:users,id',
            ];
        } else {
             $rules = [
                'title' => 'required|max:255',
                'description' => 'required',
                'user_id' => 'integer|exists:users,id',
            ];
        }

        return $rules;
    }
}
