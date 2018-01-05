<?php
  
  function checkCaptcha(){
    if(isset($_POST['g-recaptcha-response'])){
      return false;
    }
    
    $recaptcha_secret = "XYZ123456"
    $proxy_url = "tcp://proxy.domain.ch:8080"

    $post_data = http_build_query(
      array(
      'secret' => $recaptcha_secret,
      'response' => $_POST['g-recaptcha-response'],
      'remoteip' => $_SERVER['REMOTE_ADDR']
      )
    );

    $opts = array('http' =>
      array(
      'method'  => 'POST',
      'request_fulluri' => true,
      'proxy' => $proxy_url,
      'header'  => 'Content-type: application/x-www-form-urlencoded',
      'content' => $post_data
      )
    );

    $context  = stream_context_create($opts);
    $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
    $result = json_decode($response);
    
    return ($result->success && $result->success===true);
  }
?>
