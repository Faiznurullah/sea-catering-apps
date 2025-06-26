<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExperienceUser;
use App\Models\User;

class ExperienceUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $testimonials = [
            [
                'name' => 'Anita Sari',
                'review' => 'SEA Catering has transformed my busy workweeks. Healthy, delicious meals without the hassle of cooking!',
                'star' => 5,
            ],
            [
                'name' => 'Budi Wijaya',
                'review' => 'The variety of meals keeps things interesting, and the nutritional information helps me stay on track with my fitness goals.',
                'star' => 5,
            ],
            [
                'name' => 'Diana Putri',
                'review' => 'Great service and the meals are always fresh. I appreciate the flexibility to change my subscription when needed.',
                'star' => 4,
            ],
            [
                'name' => 'Eko Prasetyo',
                'review' => 'Pelayanan sangat memuaskan! Makanannya selalu segar dan sesuai dengan ekspektasi. Delivery juga selalu tepat waktu.',
                'star' => 5,
            ],
            [
                'name' => 'Fitri Handayani',
                'review' => 'Menu diet plan sangat membantu program penurunan berat badan saya. Rasanya enak dan porsinya pas.',
                'star' => 5,
            ],
            [
                'name' => 'Gilang Ramadhan',
                'review' => 'Protein plan cocok banget untuk saya yang aktif olahraga. Kualitas bahan makanan premium dan fresh.',
                'star' => 4,
            ],
            [
                'name' => 'Hani Kusuma',
                'review' => 'Royal plan memang istimewa! Cita rasa yang unik dan presentasi yang menarik. Worth every penny!',
                'star' => 5,
            ],
            [
                'name' => 'Indra Kurniawan',
                'review' => 'Sistem pause subscription sangat membantu ketika sedang traveling. Customer service juga responsif.',
                'star' => 4,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            ExperienceUser::create($testimonial);
        }
    }
}
