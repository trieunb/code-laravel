<?php

namespace App;
use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Model;

class JobTest extends Model
{

    protected $table = 'job_test';
    protected $casts = ['skill' => 'json'];
    protected $fillable = ['job_title', 'location', 'job_type', 'job_description', 'skill'];
    protected $mappingProperties = [
    	'job_title' => [
    		'type' => 'string',
    		'analyzer' => 'standard'
    	],
    	'location' => [
    		'type' => 'geo_point'
    	],
    	'job_type' => [
    		'type' => 'string',
    		'analyzer' => 'standard'
    	],
    	'job_description' => [
    		'type' => 'string',
    		'analyzer' => 'standard'
    	],
    	'skill' => [
    		'type' => 'nested'
    	],
    	'education' => [
    		'type' => 'string',
    		'analyzer' => 'stop',
    		'stopword' => [',']
    	],
    	'infomation' => [
    		'type' => 'string',
    		'analyzer' => 'standard'
    	]
    ];
}
