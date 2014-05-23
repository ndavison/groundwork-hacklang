<?hh // strict
/**
 * groundwork-hacklang
 *
 * @author Nathan Davison <http://www.nathandavison.com>
 */

namespace Config;

use Groundwork\Classes\Application;
use Groundwork\Classes\HTTPResource;
use Groundwork\Classes\Request;
use Groundwork\Classes\Response;
use Groundwork\Classes\ServerException;

/**
 * If you want your classes to be registered in the IoC container, 
 * define the binds here. Below is an example bind for the HomeResource 
 * resource class.
 */
class IocBinds
{
    /**
     * Registers the IoC binds.
     */
    public function registerBinds(Application $app): void {

        // the HomeResource class bind
        $app->register('HomeResource', function($app) {
            $request = $app->get('request');
            $response = $app->get('response');
            if ($request instanceof Request && $response instanceof Response) { 
                $resource = new \Resources\HomeResource($request, $response);
                return $resource;
            } else {
                throw new ServerException('Request and/or Response IoC binds were not valid.');
            }
        });
    }
}
