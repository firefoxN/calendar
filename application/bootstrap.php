<?php
use app\core\Route;

//echo $_SERVER['DOCUMENT_ROOT'];
//ini_set('include_path', $_SERVER['DOCUMENT_ROOT']);

require_once 'classes/abstractSingleton.php';
require_once 'classes/Autoloader.php';
require_once 'base/Base.php';
require_once 'config/config.php';
require_once 'components/library/ServiceLocatorInterface.php';
require_once 'components/library/ServiceLocator.php';
require_once 'components/services/DataBase.php';
require_once 'core/Core.php';


$locator = \app\components\library\ServiceLocator::i();
$locator->set('database', \app\components\services\DataBase::getInstance($MYSQL_HOST, $MYSQL_DB_NAME, $MYSQL_USER, $MYSQL_PASSWORD));
$app = new \app\core\Core($locator);
$app->start();
//start router
//Route::start($locator);
