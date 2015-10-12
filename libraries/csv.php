<?php
class Csv
{
    public $data;
    function setData($data) {
        $this->data = $data;
    }

	function convertCsvToDb($filename){	
		$file = $filename;
		$data = file_get_contents($file);
		$data = mb_convert_encoding($data,'UTF-8','sjis-win');
		$temp = tmpfile();
		fwrite($temp,$data);
		rewind($temp);
		while(($data = fgetcsv($temp,0,',')) !== FALSE){
			$csv[]=$data;
		}
		fclose($temp);
		return $csv;
	}

	function uploadCsv($file = 'csvfile'){
		$message = '';
		if(is_uploaded_file($_FILES[$file]['tmp_name'])){
			if(move_uploaded_file($_FILES[$file]['tmp_name'], 'uploaded_csv/' . $_FILES[$file]['name'])){
				$message =  'ファイルをアップロードしました';
				//var_dump($_FILES);
			}else{
				throw new Exception('ファイルをアップロードできませんでした');
			}
		}else{
			throw new Exception('ファイルが選択されていません');
		}
		return array(
			'uploaded_file_name' => 'uploaded_csv/' . $_FILES[$file]['name'],
			'message' => $message,
		);
	}

    function getCsv($filename,$is_ms = false) {
        $fp = fopen($filename, "w");
        if (true === $is_ms) {
            stream_filter_register("msLineEnding", "ms_line_ending_filter");
            stream_filter_append($fp, "msLineEnding");
        }
        foreach ($this->data as $line) {
            if (true === $is_ms) {
                mb_convert_variables('cp932', 'utf-8', $line);
            }
            fputcsv($fp, $line);
        }
        fclose($fp);
    }

    function getCsvMs($filename) {
        $this->getCsv($filename,true);
    }
}

class ms_line_ending_filter extends php_user_filter
{
    function filter($in, $out, &$consumed, $closing) {
        while ($bucket = stream_bucket_make_writeable($in)) {
            $bucket->data = preg_replace("/\n$/", "", $bucket->data);
            $bucket->data = preg_replace("/\r$/", "", $bucket->data);
            $bucket->data = $bucket->data . "\r\n";
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }
        return PSFS_PASS_ON;
    }
}