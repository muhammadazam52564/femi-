<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Answer;
class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
// Q#1
        Answer::create([
            'option'        => 'a',
            'statement'     => 'regular',
            'question_id'   => 1
        ]);
        Answer::create([
            'option'        => 'b',
            'statement'     => 'irregular',
            'question_id'   => 1
        ]);
        Answer::create([
            'option'        => 'c',
            'statement'     => 'light',
            'question_id'   => 1
        ]);
// Q#2

        Answer::create([
            'option'        => 'a',
            'statement'     => ' painful menstrual cycle',
            'question_id'   => 2
        ]);

        Answer::create([
            'option'        => 'b',
            'statement'     => 'PMS Symptoms',
            'question_id'   => 2
        ]);

        Answer::create([
            'option'        => 'c',
            'statement'     => 'unusual discharge',
            'question_id'   => 2
        ]);

        Answer::create([
            'option'        => 'd',
            'statement'     => 'heavy menstrual flow',
            'question_id'   => 2
        ]);

        Answer::create([
            'option'        => 'e',
            'statement'     => 'mood swings',
            'question_id'   => 2
        ]);

        Answer::create([
            'option'        => 'f',
            'statement'     => 'other',
            'question_id'   => 2
        ]);

        Answer::create([
            'option'        => 'g',
            'statement'     => 'no, nothing bothers me',
            'question_id'   => 2
        ]);
        Answer::create([
            'option'        => 'h',
            'statement'     => 'all of the above',
            'question_id'   => 2
        ]);
// Q#3
        Answer::create([
            'option'        => 'a',
            'statement'     => 'yes',
            'question_id'   => 3
        ]);

        Answer::create([
            'option'        => 'b',
            'statement'     => 'no',
            'question_id'   => 3
        ]);

        Answer::create([
            'option'        => 'c',
            'statement'     => 'No, but I used to',
            'question_id'   => 3
        ]);

        Answer::create([
            'option'        => 'd',
            'statement'     => 'I don\'t know',
            'question_id'   => 3
        ]);

// Q#4
        Answer::create([
            'option'        => 'a',
            'statement'     => 'No, I sleep well',
            'question_id'   => 4
        ]);
        Answer::create([
            'option'        => 'b',
            'statement'     => 'Difficulty Falling sleep',
            'question_id'   => 4
        ]);
        Answer::create([
            'option'        => 'c',
            'statement'     => 'Waking up tired',
            'question_id'   => 4
        ]);
        Answer::create([
            'option'        => 'd',
            'statement'     => 'Waking up during the night',
            'question_id'   => 4
        ]);
        Answer::create([
            'option'        => 'e',
            'statement'     => 'lack of sleep schedule',
            'question_id'   => 4
        ]);
        Answer::create([
            'option'        => 'f',
            'statement'     => 'Insomnia',
            'question_id'   => 4
        ]);

// Q#5
        Answer::create([
            'option'        => 'a',
            'statement'     => 'Nothing I am totally fine',
            'question_id'   => 5
        ]);
        Answer::create([
            'option'        => 'b',
            'statement'     => 'Stress',
            'question_id'   => 5
        ]);
        Answer::create([
            'option'        => 'c',
            'statement'     => 'Mood Fluctuations',
            'question_id'   => 5
        ]);
        Answer::create([
            'option'        => 'd',
            'statement'     => 'Anxiety',
            'question_id'   => 5
        ]);
        Answer::create([
            'option'        => 'e',
            'statement'     => 'Depressed mood',
            'question_id'   => 5
        ]);
        Answer::create([
            'option'        => 'f',
            'statement'     => 'Low energy',
            'question_id'   => 5
        ]);
        Answer::create([
            'option'        => 'g',
            'statement'     => 'Poor self-image',
            'question_id'   => 5
        ]);
        Answer::create([
            'option'        => 'h',
            'statement'     => 'Other',
            'question_id'   => 5
        ]);

        // Q#6
        Answer::create([
            'option'        => 'a',
            'statement'     => 'Yes',
            'question_id'   => 6
        ]);
        Answer::create([
            'option'        => 'b',
            'statement'     => 'No',
            'question_id'   => 6
        ]);
    }
}
