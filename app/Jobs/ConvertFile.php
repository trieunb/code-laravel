<?php

namespace App\Jobs;

use App\Events\ConvertedFile;
use App\Helper\ZamzarApi;
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
    public function __construct(ZamzarApi $convert ,$data, $nameZipFile)
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
        if (array_key_exists('erros', $this->data))
            return;
        $data = $this->convert->getJobAfterConvert($this->data);
        if ( !is_array($data)) return response()->json(['status_code' => 401, 'status' => false, 'message' => 'Not credentials']);

        if ($data['status'] != 'successful' && !array_key_exists('erros', $this->data)) {
            //$this->release(1);
            app('Illuminate\Contracts\Bus\Dispatcher')->dispatch(new ConvertFile($this->convert, $this->data, $this->nameZipFile));
        }else {
            $fileHtml = $this->convert->downloadFiles($data, $this->nameZipFile);
        }
        
    }
}
