<?php
namespace App\Helper;

class ConvertDocxToHtml
{
	private $sourceFilePath;
	private $targetFormat;

	public function __construct($sourceFilePath, $targetFormat)
	{
		$this->sourceFilePath = $sourceFilePath;
		$this->targetFormat = $targetFormat;
	}

	protected function convert()
	{
	  	$sourceFile = curl_file_create($this->sourceFilePath);
		$postData = [
		  "source_file" => $sourceFile,
		  "target_format" => $this->targetFormat
		];
		$ch = curl_init(); // Init curl
		curl_setopt($ch, CURLOPT_URL, config('third-party.zamzar.url').'jobs'); // API endpoint
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false); // Enable the @ prefix for uploading files
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response as a string
		curl_setopt($ch, CURLOPT_USERPWD, config('third-party.zamzar.api') . ":"); // Set the API key as the basic auth username
		$body = curl_exec($ch);
		curl_close($ch);

		return json_decode($body, true);
	}

	protected function getJobAfterConvert()
	{
		$job = $this->convert();
		var_dump($job);
		if ( !$job['sandbox']) {
			return false;
		}

		$ch = curl_init(); // Init curl
		curl_setopt($ch, CURLOPT_URL, config('third-party.zamzar.url').'jobs/'.$job['id']); // API endpoint
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response as a string
		curl_setopt($ch, CURLOPT_USERPWD, config('third-party.zamzar.api') . ":"); // Set the API key as the basic auth username
		$body = curl_exec($ch);
		curl_close($ch);

		return json_decode($body, true);
	}

	public function downloadFiles($nameZipFile)
	{
		$job = $this->getJobAfterConvert();

		$localFilename = $nameZipFile;

		if ($job['status'] === 'failed') {
			return false;
		}
		$fileId = '';

		foreach ($job['target_files'] as $target_file) {
			if (strpos($target_file['name'], '.zip')) {
				$fileId = $target_file['id'];
				echo "co".$fileId;
				break;
			}else echo $target_file['name'];
		}

		if ($fileId == '') {
			var_dump($job);
			throw new \Exception('Not found zip file');
		}

		$ch = curl_init(); // Init curl
		curl_setopt($ch, CURLOPT_URL, config('third-party.zamzar.url').'files/'.$fileId.'/content'); // API endpoint
		curl_setopt($ch, CURLOPT_USERPWD, config('third-party.zamzar.api') . ":"); // Set the API key as the basic auth username
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

		$fh = fopen($localFilename, "wb");
		curl_setopt($ch, CURLOPT_FILE, $fh);

		$body = curl_exec($ch);
		curl_close($ch);

		if($body) {
			$zip = new ZipArchive;

			$res = $zip->open(public_path($nameZipFile));
			if ($res === TRUE) {
				$zip->extractTo(public_path());
				$zip->close();
			  	return true;
			} 

			throw new \Exception('Cannot unzip file');
		}
	}
}