<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory; // * Importa la herramienta para crear datos de prueba (factories)
use Illuminate\Database\Eloquent\Model; // * Importa la clase base de Eloquent que conecta este archivo con la base de datos


class Survey extends Model
{
    use HasFactory; // * Activa el uso de fábricas de datos para pruebas dentro del modelo

    //Lista de columnas de la base de datos que permitimos rellenar de golpe de forma masiva desde un formulario (ej: usando Survey::create($request->all()))
    protected $fillable = [
        'title',
        'description',
        'type',
        'max_selections',
        'is_active',
        'is_real_time_enabled',
        'is_anonymous',
        'allowed_roles',
    ];

    // Convierte automáticamente los formatos de la base de datos a formatos nativos de PHP cuando los lees, para que trabajes con ellos de forma cómoda.
    protected $casts = [
        'is_active' => 'boolean',
        'is_real_time_enabled' => 'boolean',
        'is_anonymous' => 'boolean',
        'max_selections' => 'integer',
        'allowed_roles' => 'array',
    ];

    public function options()
    {
        // Indica que una encuesta  es dueña de muchas opciones
        return $this->hasMany(Option::class);
    }


    public function hasCategories(): bool
    {

        // Revisamos si el tipo de la encuesta es 'single_cat' O 'multiple_cat'.
        if ($this->type === 'single_cat' || $this->type === 'multiple_cat') {
            return true;
        } else {
            return false;
        }
    }


    public function isMultiple(): bool
    {

        // ? Revisamos si el tipo de la encuesta es 'multiple' O 'multiple_cat'. si se cumple cualquiera de los dos, significa que el usuario puede elegir más de una respuesta y devolvemos true.
        if ($this->type === 'multiple' || $this->type === 'multiple_cat') {
            return true;
        } else {
            return false;
        }
    }
}
