<?php


/* Author: Przemysław Mysiak & Internet Users                   */
/* Author URL: https://mysiak.net                               */
/* License: Do what you want ;) Enjoy                           */

/* Simple PHP Class to compress PNG/JPG images with Tinify      */

/* How to use?                                                  */
/* Please, change $key and $folder with images                  */
/* to compress. Get your API KEY ===============>               */
/* https://tinypng.com/developers                               */
/* Do not forget to download Tinify Library here:               */
/* https://tinypng.com/developers/reference/php#installation    */

/* Why PANDA? We <3 TINYPNG. We <3 PANDA                        */

class PANDA {

  protected $key          = '';
  protected $folder       = '';
  protected $proxy        = false; // if you want to use proxy, set it to user:pass@ip:port or just ip:port

  public function __construct() {

    require_once('lib/Tinify/Exception.php');
    require_once('lib/Tinify/ResultMeta.php');
    require_once('lib/Tinify/Result.php');
    require_once('lib/Tinify/Source.php');
    require_once('lib/Tinify/Client.php');
    require_once('lib/Tinify.php');

    try {
      $this->runCompression();
    } catch (Exception $e) {
      echo 'Caught exception: ' . $e->getMessage() . "\n";
    }

  }

  public function runCompression() {

    $files = $this->getImagesList();
    if(!$this->key) die('Please, change your API key.');
    if(!$files) die('Folder with images is empty');

    \Tinify\setKey($this->key);

    foreach ($files as $image) {
      $image_src = $this->folder . '/' . $image['filename'];
      $source = \Tinify\fromFile($image_src);

      if ($this->proxy) {
        \Tinify\setProxy($this->proxy);
      }

      $source->toFile($image_src);
      $percent = round(100-(($this->bytesConverter(filesize($image_src)) / $image['filesize'])*100),2);

      echo $image['filename'] . ' :: Done ' . '(' . $image['filesize'] . ' => ' . $this->bytesConverter(filesize($image_src)) . ' ['. $percent . '% saved]' . ')' .  "\n";
    }
  }

  public function getImagesList() {
    $list = preg_grep('~\.(jpg|jpeg|png)$~', scandir($this->folder));
    $array = array();
    $i = 0;

    foreach ($list as $image) {
      $array[$i]['filename'] = $image;
      $array[$i]['filesize'] = $this->bytesConverter(filesize($this->folder . '/' . $image));

      $i++;
    }
    return $array;
  }
  public function bytesConverter($bytes, $precision = 2) {
    $unit = ["B", "KB", "MB", "GB"];
    $exp = floor(log($bytes, 1024)) | 0;
    return round($bytes / (pow(1024, $exp)), $precision) . ' ' . $unit[$exp];
  }

}

$compress = new PANDA();
?>
