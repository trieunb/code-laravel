<?php
namespace App\Helper;

class ZamzarApi
{
	private $sourceFilePath;
	private $targetFormat;

	public function __construct($sourceFilePath, $targetFormat)
	{
		$this->sourceFilePath = $sourceFilePath;
		$this->targetFormat = $targetFormat;
	}

	public function startingConvert()
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

	public function getJobAfterConvert(array $job)
	{

		if (!isset($job['sandbox']) || !$job['sandbox']) {
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

	public function downloadFiles(array $job, $nameZipFile)
	{
		$localFilename = $nameZipFile;	
		if ($job['status'] !== 'successful') {
			return false;
		}
		$fileId = '';

		foreach ($job['target_files'] as $target_file) {
			if (strpos($target_file['name'], '.zip')) {
				$fileId = $target_file['id'];
				break;
			}
			if ( strpos($target_file['name'], '.html'))
				$filenameHtml = $target_file['name'];
		}

		if ($fileId == '') {
			return false;
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
			$zip = new \ZipArchive();

			$res = $zip->open($nameZipFile);
			if ($res === TRUE) {
				$zip->extractTo(public_path('convert'));
				$zip->close();
				$html = new \Htmldom(public_path('convert/'.$filenameHtml));

			  	foreach ($html->find('img') as $element) {
			  		$element->src = asset('convert/'.$element->src);
			  	}

			  	foreach ($html->find('div') as $element) {
			  		if (isset($element->style)) {
			  			if (strpos($element->style, ';')) {
			  				$style = explode(';', $element->style);
				  			if (strpos($style[0], 'top') > -1) {
				  				$style[0] .= 'px';
				  			}
				  			if (strpos($style[1], 'left') > -1) {
				  				$style[1] .= 'px';
				  			}
				  			$element->style = $style[0].';'.$style[1];
			  			} else $element->style .= 'px';
			  		}
			  	}

			  	$fileBlade = public_path('convert/'.$filenameHtml);
			  	$html->save($fileBlade);
			  	return $fileBlade;
			} 

			return false;
		}
	}
}