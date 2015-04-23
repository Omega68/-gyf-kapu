<?

error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE & ~E_USER_NOTICE & ~E_WARNING & ~E_DEPRECATED);

require_once __DIR__."/loader/loader_main.php";

session_start();

$config=include_once __DIR__."/admin/authenticator_config.php";

if (empty($config['class'])) throw new Exception('Authenticator class empty');
if (!class_exists($config['class'])) throw new Exception('Authenticator class not found:'. $config['class']);
$authenticator=new $config['class'];

$router=new Protected_Site_Router('admin_site','admin_login_site',$authenticator);
$router->run();
