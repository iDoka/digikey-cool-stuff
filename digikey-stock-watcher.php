<?php

 /**
   * A PHP parser/watcher/notifier of DoggyKey stock status for P/N list
   * @package digikey-stock-watcher
   * @author  Dmitry Murzinov (kakstattakim@gmail.com)
   * @link :  https://github.com/iDoka/digikey-cool-stuff
   * @version 1.0
   */

$file = "partnumber.list";

include_once('simplehtmldom/simple_html_dom.php');

define('__ROOT__', dirname(__FILE__));
require_once(__ROOT__.'/pushover.php');

ini_set('auto_detect_line_endings',TRUE);
ini_set('mbstring.internal_encoding','UTF-8');

$url_array = array();
$url_array = file($file);
//$url_array = file_get_contents($file);

// print_r($url_array);
//var_dump($url_array);

foreach ($url_array  as $url) {

  $url = trim($url," ");
  $url = trim($url,"\n");
  $url = trim($url,"\r");
  //echo $url.PHP_EOL;
  //var_dump($url);

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_REFERER, "https://www.digikey.com/");
    //curl_setopt($curl, CURLOPT_VERBOSE, true); // debug
  //curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookie.txt');  // save cookie
  //curl_setopt($curl, CURLOPT_COOKIEFILE, 'cookie.txt'); // read cookie
  curl_setopt($curl, CURLOPT_FAILONERROR, 1);
  curl_setopt($curl, CURLOPT_TIMEOUT, 3);
  //curl_setopt($curl, CURLOPT_POST, 1); // do not use!
  curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0');
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($curl, CURLOPT_HEADER, 0);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $str = curl_exec($curl);
  curl_close($curl);


  $html = new simple_html_dom(); // Create a DOM object
  $html->load($str); // Load HTML from a string
  //$html = file_get_html($url);

  foreach($html->find('h1[itemprop=model]') as $k)
    $pn = strval(trim($k->plaintext," "));
    //$pn = mb_convert_encoding(trim($k->plaintext," "), "UTF-8");

  foreach($html->find('span#dkQty') as $k)
    $qty = intval(trim($k->plaintext," \xC2\xA0"));
  if (!isset($qty)) $qty = 0;

  foreach($html->find('span[itemprop=price]') as $k)
    $price = floatval(trim($k->plaintext," \xC2\xA0"));

  $html->clear();
  unset($html);

  //echo $pn."\t".$qty."\t".$price.PHP_EOL;
  if ($qty > 0) {
    $qty = "\e[7m".$qty."\e[0m";
    $pn  = "\e[1m".$pn."\e[0m";
    $msg = $pn." is available now (".$qty." pcs) at $".$price;

    /////////////////// Debug print:
    echo $msg.PHP_EOL;

    /////////////////// PushOver notification:
    //pushover($msg);

    /////////////////// to mail:
    //mail("me<i@idoka.ru>","$pn",$msg,"From: DigiKey<digikey@idoka.ru>","\r\n");
  }

  unset($qty);
  unset($pn);
  unset($price);

  usleep(100000); //100 ms
}

?>
