<?hh
/** 
 * groundwork-hacklang - A RESTful API framework for backbone.js and equivalent 
 * JSON clients written in Hack for HHVM.
 * 
 * This is the main script that all requests are channelled through.
 * 
 * @author Nathan Davison <http://www.nathandavison.com>
 */
 
// Require the Composer autoloader - thanks Composer!
require '../vendor/autoload.php';

function groundwork() {
	
	// Instantiate the Application instance and execute.
	try {
		$app = new \Groundwork\Classes\Application(new \Config\Config(), new \Config\IocBinds(), new \Config\Routes());
		$app->init();
		$app->execute();
	// catch request errors
	} catch (\Groundwork\Classes\RequestException $e) {
		$response = new \Groundwork\Classes\Response();
		$response->send(400, $e->getMessage());
	// catch server errors
	} catch (\Groundwork\Classes\ServerException $e) {
		$response = new \Groundwork\Classes\Response();
		$response->send(500, $e->getMessage());
	}
}
groundwork();
