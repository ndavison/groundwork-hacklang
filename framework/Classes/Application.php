<?hh // strict
/**
 * groundwork-hacklang
 *
 * @author Nathan Davison <http://www.nathandavison.com>
 */

namespace Groundwork\Classes;

use Config\IocBinds;
use Config\Config;
use Config\Routes;

/**
 * The main app scope.
 */
class Application extends Container implements IContainable
{    
    /**
     * The app Config instance.
     */
    private Config $config;
    
    /**
     * The app IocBinds instance.
     */
    private IocBinds $iocBinds;
    
    /**
     * The app Routes instance.
     */
    private Routes $routes;

    public function __construct(Config $config, IocBinds $iocBinds, Routes $routes)
    {
        $this->config = $config;
        $this->iocBinds = $iocBinds;
        $this->routes = $routes;
    }
    
    /**
     * Initialise the IoC aliases and routes.
     */
    public function init(): void
    {
        // register the IoC binds for the core classes
        $appInstance = $this;
        $appConfig = $this->config;
        $this->register('app', function($app) use ($appInstance) {
            return $appInstance;
        });
        $this->register('request', function($app) use ($appConfig) {
            return new Request($appConfig->baseurl, new RequestBody());
        });
        $this->register('response', function($app) {
            return new Response();
        });
        $this->register('router', function($app) {
            return new Router();
        });
        
        // register the app IoC binds
        $this->iocBinds->registerBinds($this);
        
        // register the app routes
        $router = $this->get('router');
        if ($router !== null && $router instanceof Router) {
            $this->routes->registerRoutes($router);
        }
    }
    
    /**
     * Execute the application.
     */
    public function execute(): void
    {
        // get the core classes from the IoC container
        $request = $this->get('request');
        $router = $this->get('router');
        $response = $this->get('response');

        // attempt to match the requested route with a registered route
        if ($router !== null && $router instanceof Router && $request !== null && $request instanceof Request && $response !== null && $response instanceof Response) {
            if ($router->matchRequest($request->route, $request->httpMethod)) {

                // a match was found - pass any route params to the Request instance
                $request->routeParams = $router->params;

                // the closure associated with the matched route
                $closure = $router->getClosure();

                // attempt to call the closure
                if (is_callable($closure)) {
                    // call the output
                    $closure($this);
                } else {
                    // there was a problem calling the route's closure
                    $response->send(500, 'This route callback was not callable.');
                }

            } else {
                // a route wasn't matched - return a 404
                $response->send(404, 'The requested resource was not found.');
            }
        } else {
            throw new ServerException('Failed to instantiate classes.');
        }
    }
}
