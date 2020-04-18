<?php


/* Author: Przemysław Mysiak                                    */
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

    if(!$this->key) die('Please, change your API key.');

    \Tinify\setKey($this->key);

    $files = $this->getImagesList();

    if(!$files) die('Folder with images is empty');

    foreach ($files as $image) {
      $image = $this->folder . '/' . $image;
        $source = \Tinify\fromFile($image);

      if ($this->proxy) {
        \Tinify\setProxy($this->proxy);
      }

      $source->toFile($image);

      echo $image . ' :: Done' . "\n";
    }
  }

  public function getImagesList() {
    return preg_grep('~\.(jpg|jpeg|png)$~', scandir($this->folder));
  }

}

$compress = new PANDA();
?>
