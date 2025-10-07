<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaginaFactory extends Factory
{
    public function definition()
    {
        return [
            'titulo' => $this->faker->sentence(3),
            'slug' => $this->faker->slug(),
            'descricao' => $this->faker->paragraph(),
            'estado' => 'ativo',
            'meta_keywords' => $this->faker->words(5, true),
        ];
    }
}
