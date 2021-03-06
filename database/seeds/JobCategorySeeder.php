<?php

use Illuminate\Database\Seeder;
use App\Models\JobCategory;

class JobCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        JobCategory::truncate();

        $data = [
            'Administrative/Clerical',
            'Advertising/Promotion/PR',
            'Agriculture/Forestry',
            'Airlines/Tourism',
            'Architecture/Interior Design',
            'Arts/Design',
            'Auditing',
            'Auto/Automotive',
            'Banking',
            'Doctors',
            'IT - Software',
            'Sales',
            'QA/QC',
            'Telecommunications',
            'Printing',
            'Marketing',
            'Insurance',
            'IT - Hardware/Networking',
            'Consulting',
            'Internet/Online Media',
            'Other'
        ];
        $tmp = 1;
        foreach ($data as $key => $value) {
            $cat = new JobCategory;
            $cat->name = $value;
            if ($key != 0 && $key % 5 != 0) {
                $cat->parent_id = $tmp;
            } else {
                $tmp = $key != 1 ? $key + 1 : $key;
            }
            $cat->save();
        }
    }
}
