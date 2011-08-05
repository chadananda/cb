<?php


class cb_market {

  function download_market_xml($target_file) {
    $target_url = 'http://www.clickbank.com/feeds/marketplace_feed_v1.xml.zip';
    $target_file_zip =  dirname($target_file).'marketplace_feed_v1.xml.zip';
    // check to see if target file is from before 5:00pm PT yesterday, if so, download new file
    // else return false
    if (file_exists($target_file_zip)) unlink($target_file_zip);
    if (!file_exists($target_file) || (date("m-d-y", filemtime($target_file)) != date("m-d-y"))) {
      if (file_exists($target_file)) unlink($target_file);
      self::download_file($target_file_zip, $url);
      if (file_exists($target_file_zip)) {
        self::unzip_file($target_file_zip, $target_file);
        return file_exists($target_file);
      }
    }
  }



  // requires ZZIPlib library
  //
  function unzip_file($src, $dest) {
    $zip = new ZipArchive;
    if ($zip->open($src) === TRUE) {
      $zip->extractTo($dest);
      $zip->close();
      return TRUE;
    }
  }



  function download_file($target, $url) {
    set_time_limit(0);
    //ini_set('display_errors',true);//Just in case we get some errors, let us know....
    $fp = fopen ($target, 'w+');//This is the file where we save the information
    $ch = curl_init($url);//Here is the file we are downloading
    curl_setopt($ch, CURLOPT_TIMEOUT, 50);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
  }
}