<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Question::create([
            'question' => 'Is your menstrual cycle regular (varies by no more than 7 days)',
        ]);
        Question::create([
            'question' => 'Do you experience discomfort due to one of the following?',
        ]);
        Question::create([
            'question' => 'Do you have any reproductive health disorder (endometriosis, PCOS, fibroids, etc)?',
        ]);
        Question::create([
            'question' => 'Is there any thing you want to improve about your sleep?',
        ]);
        Question::create([
            'question' => 'Are there aspects of your mental health you would like to address?',
        ]);
        Question::create([
            'question' => 'Do you suffer from fibroids?',
        ]);
    }
}
