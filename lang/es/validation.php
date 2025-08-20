<?php

return [

    'required' => 'El campo :attribute es obligatorio.',
    'email'    => 'El campo :attribute debe ser una dirección de correo válida.',
    'min'      => [
        'string'  => 'El campo :attribute debe contener al menos :min caracteres.',
        'numeric' => 'El campo :attribute debe ser al menos :min.',
    ],
    'max'      => [
        'string'  => 'El campo :attribute no debe superar :max caracteres.',
        'numeric' => 'El campo :attribute no debe ser mayor a :max.',
    ],

    // Aquí defines cómo quieres que se muestren los nombres de los atributos
    'attributes' => [
        'nombre'      => 'Nombre',
        'descripcion' => 'Descripción',
        'precio'      => 'Precio',
        'email'       => 'Correo electrónico',
        'password'    => 'Contraseña',
    ],

];