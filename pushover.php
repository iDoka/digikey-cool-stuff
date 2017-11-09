<?php

 /**
   * A PHP parser/watcher/notifier of DigiKey stock status for P/N list
   * @package pushover
   * @author  Dmitry Murzinov (kakstattakim@gmail.com)
   * @link :  https://github.com/iDoka/digikey-cool-stuff
   * @version 1.0
   */

function pushover($message){

  $userkey="o4d3rskldmgvbsjknfw3jy7zkxun";
  $apikey="sfatxii5ogkjkl3yzqtmm63wvgnd";
  $device="iphone-idoka";

  $title="DigiKey";

  curl_setopt_array($ch = curl_init(), array(
    'CURLOPT_URL' => "https://api.pushover.net/1/messages.json",
    'CURLOPT_POSTFIELDS' => array(
      "token"   => $apikey,
      "user"    => $userkey,
      "device"  => $device,
      "title"   => $title,
      "message" => $message,
    ),
    'CURLOPT_SAFE_UPLOAD' => true,
    'CURLOPT_RETURNTRANSFER' => true,
  ));

  curl_exec($ch);
  curl_close($ch);

  return 0;
}

?>
