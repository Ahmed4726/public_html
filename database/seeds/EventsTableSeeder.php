<?php

// namespace Database\Seeds;

use App\Event;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EventsTableSeeder extends Seeder
{
    public function run()
    {
        Event::insert([
            [
                'id' => 1,
                'name' => 'News',
                'max_size' => 500,
                'width' => 600,
                'height' => 280,
                'created_at' => Carbon::create('2020', '03', '08', '09', '02', '38'),
                'updated_at' => Carbon::create('2022', '01', '04', '21', '14', '01'),
            ],
            [
                'id' => 2,
                'name' => 'Events',
                'max_size' => 500,
                'width' => 500,
                'height' => 500,
                'created_at' => Carbon::create('2020', '03', '08', '09', '02', '50'),
                'updated_at' => Carbon::create('2020', '03', '08', '09', '02', '50'),
            ],
            [
                'id' => 3,
                'name' => 'Tenders',
                'max_size' => 500,
                'width' => 500,
                'height' => 500,
                'created_at' => Carbon::create('2020', '03', '08', '09', '03', '03'),
                'updated_at' => Carbon::create('2020', '03', '08', '09', '03', '03'),
            ],
            [
                'id' => 4,
                'name' => 'Notices',
                'max_size' => 500,
                'width' => 500,
                'height' => 500,
                'created_at' => Carbon::create('2020', '03', '08', '09', '03', '13'),
                'updated_at' => Carbon::create('2020', '03', '08', '09', '03', '13'),
            ],
            [
                'id' => 5,
                'name' => 'Job Circulars',
                'max_size' => 500,
                'width' => 500,
                'height' => 500,
                'created_at' => Carbon::create('2020', '03', '08', '09', '03', '33'),
                'updated_at' => Carbon::create('2020', '03', '08', '09', '03', '33'),
            ],
            [
                'id' => 6,
                'name' => 'Press Releases',
                'max_size' => 500,
                'width' => 600,
                'height' => 280,
                'created_at' => Carbon::create('2020', '03', '08', '09', '03', '44'),
                'updated_at' => Carbon::create('2020', '05', '15', '02', '42', '59'),
            ],
            [
                'id' => 7,
                'name' => 'Result',
                'max_size' => 500,
                'width' => 500,
                'height' => 500,
                'created_at' => Carbon::create('2020', '03', '08', '09', '03', '53'),
                'updated_at' => Carbon::create('2020', '03', '08', '09', '03', '53'),
            ],
            [
                'id' => 8,
                'name' => 'Students Stipend',
                'max_size' => 500,
                'width' => 500,
                'height' => 500,
                'created_at' => Carbon::create('2021', '06', '30', '21', '36', '15'),
                'updated_at' => Carbon::create('2021', '06', '30', '21', '36', '15'),
            ],
            [
                'id' => 9,
                'name' => 'APA Related',
                'max_size' => 500,
                'width' => 500,
                'height' => 500,
                'created_at' => Carbon::create('2023', '04', '04', '22', '08', '46'),
                'updated_at' => Carbon::create('2023', '04', '04', '22', '08', '46'),
            ],
            [
                'id' => 10,
                'name' => 'Office Order',
                'max_size' => 500,
                'width' => 500,
                'height' => 500,
                'created_at' => Carbon::create('2023', '04', '10', '18', '36', '35'),
                'updated_at' => Carbon::create('2023', '04', '10', '18', '36', '35'),
            ],
            [
                'id' => 11,
                'name' => 'MISC',
                'max_size' => 500,
                'width' => 500,
                'height' => 500,
                'created_at' => Carbon::create('2024', '07', '04', '02', '01', '27'),
                'updated_at' => Carbon::create('2024', '07', '04', '02', '01', '27'),
            ],
        ]);
    }
}
