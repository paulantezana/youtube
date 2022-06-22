<?php
date_default_timezone_set('America/Lima');

function exceptions_error_handler($severity, $message, $filename, $lineno)
{

  $dateTime =  date('Y-m-d H:i:s');
  error_log("{$dateTime}: {$severity} ${message}" . PHP_EOL . $filename . "({$lineno})" . PHP_EOL, 3,  __DIR__ . '/errors.log');
  if (error_reporting() == 0) {
    return;
  }
  if (error_reporting() & $severity) {
    throw new ErrorException($message, 0, $severity, $filename, $lineno);
  }
}
set_error_handler('exceptions_error_handler');

$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$requestUri = parse_url('http://example.com' . $_SERVER['REQUEST_URI'], PHP_URL_PATH);
$virtualPath = '/' . ltrim(substr($requestUri, strlen($scriptName)), '/');
$hostName = (stripos(@$_SERVER['REQUEST_SCHEME'], 'https') === 0 ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'];

define('HOST', $hostName);
define('PORT', strlen($_SERVER['SERVER_PORT']) > 0 ? ':' . $_SERVER['SERVER_PORT'] : '');
define('URI', $requestUri);
define('URL_PATH', rtrim($scriptName, '/'));
define('URL', $virtualPath);

define('ROOT_DIR', $_SERVER["DOCUMENT_ROOT"] . rtrim($scriptName, '/') . '/..');
define('PUBLIC_ROOT_DIR', $_SERVER["DOCUMENT_ROOT"] . rtrim($scriptName, '/'));
define('CONTROLLER_PATH', ROOT_DIR . '/app/Controllers');
define('MODEL_PATH', ROOT_DIR . '/app/Models');
define('VIEW_PATH', ROOT_DIR . '/app/Views');
define('CERVICE_PATH', ROOT_DIR . '/app/Services');
define('HELPER_PATH', ROOT_DIR . '/app/Helpers');

define('SESS_USER', 'df120');

define('APP_NAME', 'BUS');
define('APP_AUTHOR', 'paulantezana');
define('APP_AUTHOR_WEB', 'http://paulantezana.com');
define('APP_DESCRIPTION', 'Venta de pasajes');
define('APP_EMAIL', 'contacto@sistemadepasajes.com');
define('APP_PHONE', '+51910475188');
define('APP_COLOR', '#185D9A');

define('APP_DEV', true);

define('FILE_PATH', '/files');

define('GOOGLE_RECAPTCHA_SITE_KEY','6LdxRmogAAAAAHVzX_wJ5mMtvmSyiy2Ednrb84qI');
define('GOOGLE_RECAPTCHA_SECRET_KEY','6LdxRmogAAAAADb-AUIdwBcTSCMpgJjUxDfa9v_o');