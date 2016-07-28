<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserRequest extends Request
{
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'age' => 'required|integer',
        ];
    }
    
    /**
     * 数据验证失败后，重写
     * @see \Illuminate\Foundation\Http\FormRequest::response()
     */
    public function response()
    {
        
    }
}
