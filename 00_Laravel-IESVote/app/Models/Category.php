<?php

namespace App\Models;
// [IMPORTANT]: Eloquent lo que hace es basicamente pasar codigo PHP a consultas SQL para que puedas trabajar con la base de datos de forma sencilla y sin escribir SQL directamente. --- IGNORE ---
use Illuminate\Database\Eloquent\Model; // * Importa la clase base de Eloquent para poder definir nuestro modelo de categoría

class Category extends Model
{
    /**
     * Define la relación de "Muchos a Muchos" entre las Categorías y los Usuarios.
     * * Una categoría puede tener asignados muchos usuarios.
     */
    public function users()
    {
        // ? Como es una relación cruzada de "muchos a muchos", Laravel necesita una tercera tabla intermedia (tabla pivote) llamada 'category_user' para poder unir los IDs de ambos.
        return $this->belongsToMany(User::class, 'category_user');
    }
}
