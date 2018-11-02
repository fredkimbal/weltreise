<?PHP

session_start();
error_reporting(NONE);
//error_reporting(E_NONE);
$_SESSION['rootfolder'] = getcwd(); // aktuelle Berechtigungen auslesen

require_once $_SESSION['rootfolder'].'/API/APIFactory.php';

require_once $_SESSION['rootfolder'].'/Libs/klogger-1.0.0b/KLogger.php';
$_SESSION['logpath'] = $_SESSION['rootfolder'] . '/Log/logfile.log';
$_SESSION['loglevel'] = KLogger::DEBUG;

define('SMARTY_DIR', $_SESSION['rootfolder'].'/Libs/smarty-3.1.30/libs/');
require_once(SMARTY_DIR . 'Smarty.class.php');

//header('Content-Type: application/json');
// * wont work in FF w/ Allow-Credentials
//if you dont need Allow-Credentials, * seems to work
header('Access-Control-Allow-Origin: *');
//if you need cookies or login etc
header('Access-Control-Allow-Credentials: true');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
{
  header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
  header('Access-Control-Max-Age: 604800');
  //if you need special headers
  header('Access-Control-Allow-Headers: x-requested-with');
  exit(0);
}
// Requests from the same server don't have a HTTP_ORIGIN header
if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}
try {
    $args = explode('/', rtrim($_REQUEST['request'], '/'));    
    $endpoint = array_shift($args);
    
    $API = APIFactory::GetAPI($endpoint, $_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
    if (!isset($API)) {
        echo json_encode(Array('error' => 'Unknown Endpoint'));
    }
    else {
        $data = $API->processAPI();
        echo $data;
    }
}
catch (Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}
?>