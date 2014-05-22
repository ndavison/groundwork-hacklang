<?hh // strict
/**
 * groundwork-hacklang
 *
 * @author Nathan Davison <http://www.nathandavison.com>
 */

namespace Groundwork\Classes;

/**
 * Handles the logic of matching the requested resource to a predefined route.
 */
class Router implements IContainable
{
    /**
     * The routes registered with the Router.
     */
    public Map<string, (function (Application): void)> $routes = Map {};
    
    /**
     * The route that was matched by the request, populated after calling 
     * the Router::matchRequest() method.
     */
    private string $matched = '';
    
    /**
     * The params that were matched in the comparison with the request, 
     * populated after calling the Router::matchRequest() method.
     */
    public Map<string, string> $params = Map {};
           
    /**
     * Register a route to the Router instance. The second $callback param is 
     * the callback logic.
     */
    public function register(string $route, (function (Application): void) $callback, string $httpMethod = ''): void
    {
        // convert empty routes to 'home'
        if (!$route) {
            $route = 'home';
		}
        
        // append the http method to the route
        if ($httpMethod) {
            $route = $httpMethod . ':' . $route;
		}
                
        $this->routes->add(Pair {$route, $callback});
    }
    
    /**
     * Shortcut to register a GET route.
     */
    public function get(string $route, (function (Application): void) $callback): void
    {
        $this->register($route, $callback, 'GET');
    }
    
    /**
     * Shortcut to register a POST route.
     */
    public function post(string $route, (function (Application): void) $callback): void
    {
        $this->register($route, $callback, 'POST');
    }
    
    /**
     * Shortcut to register a PUT route.
     */
    public function put(string $route, (function (Application): void) $callback): void
    {
        $this->register($route, $callback, 'PUT');
    }
    
    /**
     * Shortcut to register a DELETE route.
     */
    public function delete(string $route, (function (Application): void) $callback): void
    {
        $this->register($route, $callback, 'DELETE');
    }
        
    /**
     * Compares the requested route param with the registered routes and checks 
     * whether there is a match - true on a match, false if not.
     */
    public function matchRequest(string $requestedRoute, string $httpMethod): bool
    {
        // iterate through each route that has been registered
        foreach ($this->routes as $route => $callback) {
            
            // convert the route to a regex pattern
            $routeRx = preg_replace(
                        '%/:?([^ /?]+)(\?)?%',
                        '/\2(?P<\1>[^ /?]+)\2',
                    $route);
            
            // if the route also defined a HTTP method to match against, 
            // append the requested route with the request method
            $routeMethod = strstr($route, ':', true);
            if ($routeMethod && substr($routeMethod, -1) != '/') {
                $requestPrepend = $httpMethod . ':';
            } else {
                $requestPrepend = '';
            }
            
            // check for a regex match with the requested route. Store the 
            // matches in a variable so the Request instance can be informed.
			$uriParams = array();
            if (preg_match('%^' . (string) $routeRx . '$%',
                    $requestPrepend.$requestedRoute,
                    $uriParams)
                ) {
                // before returning true, store the params 
                // that were matched, and store the matched route.
                foreach ($uriParams as $key => $value) {
                    if (!is_numeric($key)) {
						$this->params->add(Pair {$key, $value});
					}
                }
                $this->matched = $route;
                
                return true;
            }
        }
        
        // no matches caught
        return false;
    }
    
    /**
     * Returns a closure which contains the logic to generate the 
     * output for the requested route.
     */
    public function getClosure(): (function (Application): void)
    {           
        $callback = $this->routes->get($this->matched);
		if ($callback !== null && is_callable($callback)) {
			return $callback;
		} else {
			throw new ServerException('The route callback was not callable.');
		}
    }
}
