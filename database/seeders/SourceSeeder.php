<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sources = config('news.sources');
        foreach ($sources as $source) {
            $name = Arr::get($source, 'name');
            if ($name) {
                Source::firstOrCreate([
                    'name' => $name,
                ], $source);
            }
        }
    }
}
