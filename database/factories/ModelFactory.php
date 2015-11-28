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
		'meta' => json_encode(['a', 'b', 'c', 'd', 'e']),
		'parent_id' => null,
		'path' => $faker->name
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
		'sub_title' => $faker->name,
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