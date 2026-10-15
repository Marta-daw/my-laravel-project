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
            'titulo' => 'required|string|max:255',
            'paginas' => 'required|integer|min:1',
            'sinopsis' => 'string',
            'idioma_id' => 'required|exists:idiomas,id',
            'leido' => 'required|enum:si, no, en_proceso|default:no',
            'puntuacion' => 'nullable|integer|between:0,5',
            'portada' => 'nullable|string',

            'autor_id' => ['required', 'array'],
            'autor_id.*' => ['integer', 'distinct', 'exists:autors,id'],

            'categoria_id' => ['required', 'array'],
            'categoria_id.*' => ['integer', 'distinct', 'exists:categorias,id'],
        ];
    }
}