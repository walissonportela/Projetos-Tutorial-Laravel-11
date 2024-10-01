<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $this->formatCpfInput();

        $userId = $this->route('user');

        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . ($userId ? $userId->id: null),
            'cpf' => 'required|unique:users,cpf,' . ($userId ? $userId->id: null),
            'password' => 'required|min:6',
        ];
    }

    public function messages(): array
    {

        return[
            'name.required' => 'Campo nome é obrigatório!',
            'email.required' => 'Campo e-mail é obrigatóriol',
            'email.email' => 'Necessário enviar e-mail valido!',
            'email.unique' => '0 e-mail já está cadastrado!',

            'cpf.required' => 'Campo cpf é obrigatório!', 
            'cpf.unique' => 'o cpf já está cadastrado!',

            'password.required' => 'Campo senha é obrigatório!',
            'password.min' => 'Senha com no minimo :min caracteres!'
        ];
    }

    protected function formatCpfInput(){

        $cpf = $this->input('cpf');
        $this->merge([
            'cpf' => preg_replace('/[\D]/', '', $cpf),
        ]);
    }
}