<?php

use Freemius\SDK\Freemius;

require_once 'vendor/autoload.php';

define( 'FS__API_SCOPE', 'developer' );
define( 'FS__API_DEV_ID', 17789 );
define( 'FS__API_PUBLIC_KEY', 'pk_e9f68da8dc036c0085723313b9e2d' );
define( 'FS__API_SECRET_KEY', 'sk_6SWIE]0xiZ6RHc]QaQ;)A(hpf1-*x' );
// https://guardiv.test

$freemius = new Freemius(
  FS__API_SCOPE, 
  FS__API_DEV_ID, 
  FS__API_PUBLIC_KEY, 
  FS__API_SECRET_KEY,
  true
);


echo $freemius->test();