<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            '20th Century',
            '21st Century',
            '40k',
            'Academic',
            'Action',
            'Adult',
            'Adult Fiction',
            'Adventure',
            'Agriculture',
            'Ancient',
            'Anthologies',
            'Anti Racist',
            'Apocalyptic',
            'Arabic',
            'Art',
            'Artificial Intelligence',
            'Audiobook',
            'Autobiography',
            'Biography',
            'Biography Memoir',
            'Biology',
            'Book Club',
            'British Literature',
            'Buddhism',
            'Buisness',
            'Chess',
            'Childrens',
            'China',
            'Classics',
            'Climate Change',
            'Comedy',
            'Comics',
            'Computer Science',
            'Contemporary',
            'Cookbooks',
            'Crafts',
            'Criticism',
            'Cultural',
            'Culture',
            'Currency',
            'Dark',
            'Death',
            'Design',
            'Disability',
            'dotbook',
            'Dragons',
            'Drama',
            'Eastern Philosophy',
            'Ecology',
            'Economics',
            'Education',
            'Engineering',
            'Entrepreneurship',
            'Environment',
            'Epic',
            'European Literature',
            'Fairies',
            'Family',
            'Fantasy',
            'Feminism',
            'Fiction',
            'Film',
            'Fitness',
            'Food',
            'Food and Drink',
            'French',
            'German Literature',
            'Germany',
            'Ghosts',
            'Gothic',
            'Health',
            'History',
            'Horror',
            'Humor',
            'Inspirational',
            'Ireland',
            'Islam',
            'Italian Literature',
            'Italy',
            'Journalism',
            'Kids',
            'Language',
            'Leadership',
            'Literary Criticism',
            'Literary Fiction',
            'Literature',
            'Magical Realism',
            'Manga',
            'Manhwa',
            'Mathematics',
            'Mental Health',
            'Microhistory',
            'Money',
            'Music',
            'Mystery',
            'New Adult',
            'Nobel Prize',
            'Nonfiction',
            'Northern Africa',
            'Novels',
            'Nutrition',
            'Paranormal',
            'Parenting',
            'Personal Finance',
            'Philosophy',
            'Plays',
            'Poetry',
            'Political Science',
            'Politics',
            'Post Apocalyptic',
            'Productivity',
            'Psychology',
            'Race',
            'Realistic Fiction',
            'Reference',
            'Relationships',
            'Retellings',
            'Reverse Harem',
            'Roman',
            'Romance',
            'Russian Literature',
            'Science',
            'Science Fiction',
            'Science Fiction Fantasy',
            'Scotland',
            'Self Help',
            'Sequential Art',
            'Shapeshifters',
            'Short Stories',
            'Social Justice',
            'Social Movements',
            'Society',
            'Sociology',
            'Space',
            'Speculative Fiction',
            'Spirituality',
            'Sports',
            'Sports Romance',
            'Stationery',
            'Steampunk',
            'Suspense',
            'Sustainability',
            'Sweden',
            'Teaching',
            'Technology',
            'Theology',
            'Theory',
            'Thriller',
            'Transport',
            'Tunisia',
            'Turkish',
            'Turkish Literature',
            'Uncategorized',
            'Unfinished',
            'War',
            'Western Africa',
            'Womens',
            'Womens Fiction',
            'World War I',
            'World War II',
            'Writing',
            'Young Adult'
        ];
        foreach ($tags as $tag) {
            
            Tag::create([
                'name' => $tag,
            ]);
        }
    }
}
