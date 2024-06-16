<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $categories = [
            'Arts & Music',
            'Biographies',
            'Business',
            'Comics',
            'Computers & tech',
            'Cooking',
            'Education',
            'Entertainment',
            'Health & Fitness',
            'History',
            'Hobbies & Craft',
            'Home & Garden',
            'Kids',
            'Literature & Fiction',
            'Horror',
            'Medical',
            'Mystery',
            'Parenting',
            'Religion & Spirituality',
            'Romance',
            'Sci-Fi & Fantasy',
            'Science',
            'Self-Help',
            'Teen',
            'Social Sciences',
            'Sports',
            'Thriller',
            'Travel',
            'Young Adult',
            'True Crime',
            'Others'
        ];
        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
            ]);
        }
        
    }
}
