<?php
/**
 * Front to the yoonest application.
 *
 * @package yoonest sample
 */

 
use Symfony\Component\Dotenv\Dotenv;


/***********************************************/
/*        HELPER                               */
/***********************************************/

/**
 * Not display error
 */
error_reporting(0);
@ini_set( 'display_errors', 0 );


/**
 * PATH DIR
 */
if ( !defined('ABSPATH') )
  define( 'ABSPATH', dirname(__FILE__) . '/' );

/**
 * Autoloader
 */
require_once ABSPATH.'vendor/autoload.php';


/**
 * Load config
 */
if( file_exists( ABSPATH . '.env') ) {
  $dotenv = new Dotenv();
  $dotenv->load(ABSPATH . '.env');
}

/**
 * Get var in $_REQUEST, $_SERVER, etc ...
 */
function get_var( &$var, $default = null){
  return isset( $var ) ? $var : $default;
}

/**
 * Mode DEBUG
 */
if ( get_var($_ENV["DEBUG"], 'false') === 'true' ) {
  error_reporting( E_ALL );
  @ini_set( 'display_errors', 1 );
  @ini_set('error_prepend_string','<div class="alert--danger"');
  @ini_set('error_append_string','<br/></div>');
} else {
  error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );
}

/**
 * Fonction de tracing error pour mode debug
 */
function _echo( $var, $var_dump = 0 ){

  if ( get_var($_ENV["DEBUG"], 'false') === 'false' ) return null;
  echo '<pre class="alert--inverse"><strong>DEBUG: </strong>';
  if($var_dump) var_dump($var);
  else print_r($var);
  echo '</pre>';

}

/**
 * Get Auth header
 */
function getAuthorizationHeader(){
  $headers = null;
  if (isset($_SERVER['Authorization'])) {
      $headers = trim($_SERVER["Authorization"]);
  }
  else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
      $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
  } elseif (function_exists('apache_request_headers')) {
      $requestHeaders = apache_request_headers();
      $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
      if (isset($requestHeaders['Authorization'])) {
          $headers = trim($requestHeaders['Authorization']);
      }
  }
  return $headers;
}


/***********************************************/
/*        CONSTANTS                            */
/***********************************************/

define('MYSQL', 'mysql:host=' . get_var( $_ENV["DB_HOST"], "127.0.0.1") . ';dbname=' . get_var( $_ENV["DB_TABLE"], "database") );
define('DB_USER', get_var( $_ENV["DB_USER"], "user") );
define('DB_PASSWORD', get_var( $_ENV["DB_PASSWORD"], "user") );



/***********************************************/
/*        ROUTES                               */
/***********************************************/

$app = new Silex\Application();

$app->before(function () {

  _echo(getAuthorizationHeader());
});


$app->get('/', function (){
  if ( glob( ABSPATH . 'public/index.html' ) )
    return require(ABSPATH . 'public/index.html');
  else
    return 'You must compile app front end with npm build!';
});

/**
 * Route: api/order
 */
$app->get('/api/orders', function (){

  $result = array();

  // DB QUERY
  try{
  $db = new PDO(MYSQL , DB_USER, DB_PASSWORD );  
    $sth = $db->query('SELECT * FROM orders LEFT JOIN customers ON orders.customer = customers.id');
    $sth->setFetchMode(PDO::FETCH_ASSOC); 
    foreach( $sth as $order){
      $order["cart"] = @json_decode($order["cart"]);
      $order["customer"] = array( "lastname" => $order["lastname"], "firstname" => $order["firstname"]);
      unset($order["lastname"]);
      unset($order["firstname"]);
      unset($order["email"]);
      array_push($result, $order);
    }
    $db = null;
  } catch (Exception $e){
    _echo($e);
    http_response_code(503);
    die();
  }

  // Response
  $response = new \Symfony\Component\HttpFoundation\JsonResponse();
  $response->setContent(json_encode($result, JSON_NUMERIC_CHECK));
  return $response;
});


/**
 * Route: api/order/{id}
 */
$app->get('/api/order/{id}', function ($id) use ($app) {

  $result = array();
  
  // DB QUERY
  try{
    $db = new PDO(MYSQL , DB_USER, DB_PASSWORD );  
    $sth = $db->query('SELECT * FROM orders LEFT JOIN customers ON orders.customer = customers.id WHERE orders.id = ' . $id);
    $sth->setFetchMode(PDO::FETCH_ASSOC); 
    foreach( $sth as $order){
      $order["cart"] = @json_decode($order["cart"]);
      $order["customer"] = array( "lastname" => $order["lastname"], "firstname" => $order["firstname"]);
      unset($order["lastname"]);
      unset($order["firstname"]);
      unset($order["email"]);
      array_push($result, $order);
    }
    $db = null;
  } catch (Exception $e){
    _echo($e);
    http_response_code(503);
    die();
  }

  // Response
  $response = new \Symfony\Component\HttpFoundation\JsonResponse();
  $response->setContent(json_encode($result, JSON_NUMERIC_CHECK));
  return $response;
});

/**
 * Route: api/products
 */
$app->get('/api/products', function (){

  $result = array();

  // DB QUERY
  try{
  $db = new PDO(MYSQL , DB_USER, DB_PASSWORD );  
    $sth = $db->query('SELECT * FROM products');
    $sth->setFetchMode(PDO::FETCH_ASSOC); 
    foreach( $sth as $product){
      array_push($result, $product);
    }
    $db = null;
  } catch (Exception $e){
    _echo($e);
    http_response_code(503);
    die();
  }

  // Response
  $response = new \Symfony\Component\HttpFoundation\JsonResponse();
  $response->setContent(json_encode($result, JSON_NUMERIC_CHECK));
  return $response;
});

/**
 * Route: api/product/{sku}
 */
$app->get('/api/product/{sku}', function ($sku) use ($app){

  $result = array();

  // DB QUERY
  try{
  $db = new PDO(MYSQL , DB_USER, DB_PASSWORD );  
    $sth = $db->query('SELECT * FROM products WHERE sku = "' . $sku . '"');
    $sth->setFetchMode(PDO::FETCH_ASSOC); 
    foreach( $sth as $product){
      array_push($result, $product);
    }
    $db = null;
  } catch (Exception $e){
    _echo($e);
    http_response_code(503);
    die();
  }

  // Response
  $response = new \Symfony\Component\HttpFoundation\JsonResponse();
  $response->setContent(json_encode($result, JSON_NUMERIC_CHECK));
  return $response;
});

$app->run();