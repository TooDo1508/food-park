<?php

namespace Database\Seeders;

use App\Models\SectionTitle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WhyChooseUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        SectionTitle::insert([
            [
                'key' => 'why_choose_us_top_title',
                'value' => 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Totam doloremque obcaecati deleniti quidem hic incidunt ullam tempore voluptas laboriosam in! Provident itaque quod cumque aut reprehenderit nesciunt quibusdam tempore sit.'
            ],
            [
                'key' => 'why_choose_us_main_title',
                'value' => 'Why Choose Us',
            ],
            [
                'key' => 'why_choose_us_sub_title',
                'value' => 'Objectively pontificate quality models before intuitive information. Dramatically recaptiualize multifunctional materials.',
            ],
        ]);
    }
}
