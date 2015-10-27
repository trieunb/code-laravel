<?php

namespace App\Jobs;

use App\Helper\ConvertDocxToHtml;
use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConvertFile extends Job implements SelfHandling, ShouldQueue
{
    private $data;
    private $nameZipFile;
    private $convert;

    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ConvertDocxToHtml $convert ,$data, $nameZipFile)
    {
        $this->data = $data;
        $this->nameZipFile = $nameZipFile;
        $this->convert = $convert;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->convert->getJobAfterConvert($this->data);
        if ($data['status'] != 'successful') {
            $this->release(3);
        }else return $this->convert->downloadFiles($data, $this->nameZipFile);
        
    }
}
