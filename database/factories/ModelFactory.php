<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'firstname' => $faker->firstname,
        'lastname' => $faker->lastname,
        'email' => $faker->email,
        'dob' => $faker->dateTime(),
        'gender' => rand(0,1),
        'avatar' => $faker->imageUrl(200, 200),
        'address' => $faker->address,
        'soft_skill' => config('soft-skill.question'),
        'mobile_phone' => $faker->phoneNumber,
        'home_phone' => $faker->phoneNumber,
        'city' => $faker->city,
        'state' => $faker->streetAddress,
        'country' => $faker->country,
        'password' => bcrypt('123456'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\Category::class, function(Faker\Generator $faker) {
    return [
        'user_id' => rand(1, 10),
        'name' => $faker->name,
        'slug' => $faker->name,
        'parent_id' => null
    ];
});

$factory->define(App\Models\Role::class, function(Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'slug' => $faker->name
    ];
});

$factory->define(App\Models\UserEducation::class, function(Faker\Generator $faker) {
    return [
        'user_id' => rand(1, 10),
        'school_name' => $faker->company,
        'title' => $faker->name,
        'start' => $faker->name,
        'end' => $faker->name,
        'degree' => $faker->text,
        'result' => $faker->text
    ];
});

$factory->define(App\Models\UserSkill::class, function(Faker\Generator $faker) {
    return [
        'user_id' => rand(1, 10),
        'skill_name' => $faker->name,
        'skill_test' => $faker->name,
        'skill_test_point' => $faker->randomDigit(),
        'experience' => $faker->name
    ];
});

$factory->define(App\Models\UserWorkHistory::class, function(Faker\Generator $faker) {
    return [
        'user_id' => rand(1, 10),
        'company' => $faker->company,
        'start' => $faker->name,
        'end' => $faker->name,
        'job_title' => $faker->name,
        'job_description' => $faker->text
    ];
});

$factory->define(App\Models\Objective::class, function(Faker\Generator $faker) {
    return [
        'user_id' => rand(1, 10),
        'title' => $faker->name,
        'content' => $faker->text
    ];
});

$factory->define(App\Models\Reference::class, function(Faker\Generator $faker) {
    return [
        'user_id' => rand(1, 10),
        'reference' => $faker->name,
        'content' => $faker->text
    ];
});

$factory->define(App\Models\Question::class, function(Faker\Generator $faker) {
    return [
        'content' => $faker->name
    ];
});

$factory->define(App\Models\JobCompany::class, function(Faker\Generator $faker) {
    return [
        'name' => $faker->company,
        'country' => $faker->countryCode,
        'address' => $faker->address,
        'logo' => $faker->imageUrl(100, 100),
        'website' => $faker->url,
        'email' => $faker->email,
        'description' => $faker->text,
        'overview' => $faker->text,
        'benefits' => $faker->text,
        'registration_no' => $faker->sentence($nbWords = 6),
        'industry' => $faker->text,
        'company_size' => $faker->sentence($nbWords = 6),
        'why_join_us' => $faker->text
    ];
});

$factory->define(App\Models\Job::class, function(Faker\Generator $faker) {
    $job_titles = ['Node.JS / Javascript Developer', 'Front-end Developer Up to $900', 'C/C++ Agile Developer',
        '02 Mobile Developers (iOS,Android)', 'Senior Fontend Wordpress Developer', 'Tuyển gấp Senior PHP Developer tại Hà Nội',
        'Full-stack PHP engineer -EC/OnlineMarketing System', 'Senior C# .NET Developer', 'Experienced Web Designer',
        'Experienced Web Designer', 'iOS Developers (Mobile Apps, Objective C)', 'Software Bridge Engineer (Japanese N2)',
        'C/C++ Programmer', 'Product Tech Leader (PHP)', '02 Senior Xamarin Programmer', '15 Senior iOs Developers - Salary up to $800',
        'Senior Software Testing Engineer', 'Team Leader of Software Engineer (Java, .NET)'
    ];
    $k = array_rand($job_titles);
    $expExpectations = [
        'Min 3 Years',
        '1 Year',
        'Min 5 Years',
        '2 Years',
        '3 Years'
    ];
    return [
        'job_cat_id' => rand(1, 21),
        'company_id' => rand(1, 20),
        'title' => $job_titles[$k],
        'country' => $faker->countryCode,
        'location' => $faker->address,
        'experience' => $expExpectations[array_rand($expExpectations)],
        'description' => $faker->text,
        'min_salary' => $faker->numberBetween($min = 100, $max = 9000),
        'responsibilities' => $faker->text,
        'requirements' => $faker->text
    ];
});
