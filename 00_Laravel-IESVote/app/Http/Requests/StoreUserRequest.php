<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta petición.
     */
    public function authorize(): bool
    {
        // Cambiado a true para que permita procesar el formulario
        return true;
    }

    /**
     * Reglas de validación que se aplicarán a la petición.
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'string', 'regex:/^\d{8}[A-Z]$/', 'unique:users,dni'],
            'categories' => ['required', 'array'],
            'categories.*' => ['exists:categories,id'], // Valida que cada ID exista en la tabla categories
        ];
    }

    /**
     * Mensajes de error personalizados en castellano.
     */
    public function messages(): array
    {
        return [
            'username.required' => 'El nombre de usuario es obligatorio.',
            'username.max' => 'El nombre de usuario no puede superar los 255 caracteres.',
            'dni.required' => 'El campo DNI/NIE es totalmente obligatorio.',
            'dni.regex' => 'El formato del DNI no es correcto (deben ser 8 números y una letra mayúscula, ej: 12345678X).',
            'dni.unique' => 'Este DNI ya se encuentra registrado en el censo electoral.',
            'categories.required' => 'Es obligatorio asignar al menos una categoría (colectivo) al usuario.',
            'categories.*.exists' => 'Una de las categorías seleccionadas no es válida en el sistema.',
        ];
    }
}
