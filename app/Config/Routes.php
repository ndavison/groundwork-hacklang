<?hh // strict
/**
 * groundwork-hacklang
 *
 * @author Nathan Davison <http://www.nathandavison.com>
 */

namespace Config;

use Groundwork\Classes\Resource;
use Groundwork\Classes\Router;
use Groundwork\Classes\Request;
use Groundwork\Classes\Response;
use Groundwork\Classes\ServerException;

/**
 * Define the app routes in this file. This is done via the Router::register() 
 * method, or methods matching the four major HTTP methods (get, post, put and 
 * delete).
 */
class Routes
{
	/**
	 * Registers the application routes with the router.
	 */
	 public function registerRoutes(Router $router): void
	 {
		// the app home
		$router->register('', function($ioc) {
			$resource = $ioc->get('HomeResource');
			if ($resource instanceof \Resources\HomeResource) {
				$resource->output();
			} else {
				throw new ServerException('The resource was not a HomeResource instance.');
			}
		});
	 }
}
