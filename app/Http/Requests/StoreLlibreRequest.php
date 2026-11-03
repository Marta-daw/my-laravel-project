<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLlibreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'paginas' => ['required', 'integer', 'min:1'],
            'sinopsis' => ['nullable', 'string'],
            'idioma_id' => ['required', 'integer', 'exists:idiomas,id'],
            'leido' => ['required', 'in:si,no,en_proceso'],
            'puntuacion' => ['nullable', 'integer', 'between:0,5'],
            'portada' => ['nullable', 'string', 'max:500'],

            'autor_id' => ['required', 'array', 'min:1'],
            'autor_id.*' => ['integer', 'distinct', 'exists:autors,id'],

            'categoria_id' => ['nullable', 'array'],
            'categoria_id.*' => ['integer', 'distinct', 'exists:categorias,id'],
        ];
    }
}