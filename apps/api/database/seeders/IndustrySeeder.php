<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    public function run(): void
    {
        $industries = [
            ['slug' => 'food-beverage', 'name_en' => 'Food & Beverage', 'name_fa' => 'غذا و نوشیدنی'],
            ['slug' => 'pharmaceutical', 'name_en' => 'Pharmaceutical', 'name_fa' => 'دارویی'],
            ['slug' => 'medical', 'name_en' => 'Medical', 'name_fa' => 'پزشکی'],
            ['slug' => 'manufacturing', 'name_en' => 'Manufacturing', 'name_fa' => 'تولید و صنعت'],
            ['slug' => 'petrochemical', 'name_en' => 'Petrochemical', 'name_fa' => 'پتروشیمی'],
            ['slug' => 'agriculture', 'name_en' => 'Agriculture', 'name_fa' => 'کشاورزی'],
            ['slug' => 'automation', 'name_en' => 'Automation', 'name_fa' => 'اتوماسیون'],
            ['slug' => 'ventilation', 'name_en' => 'Ventilation', 'name_fa' => 'تهویه'],
            ['slug' => 'packaging', 'name_en' => 'Packaging', 'name_fa' => 'بسته‌بندی'],
            ['slug' => 'automotive', 'name_en' => 'Automotive', 'name_fa' => 'خودرو'],
        ];

        foreach ($industries as $industry) {
            Industry::updateOrCreate(['slug' => $industry['slug']], $industry);
        }
    }
}
