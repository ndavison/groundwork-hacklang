<?hh

namespace Groundwork\Tests;

require __DIR__ . '/../../vendor/autoload.php';

class RouterTest extends \PHPUnit_Framework_TestCase
{   
    public function testRouterMatchesRequestCorrectly()
    {
        $router = new \Groundwork\Classes\Router();
                
        $router->register('users/:id/:field', function($ioc) {});
        $matched = $router->matchRequest('users/12/name', 'GET');
        
        $this->assertTrue($matched);
    }
    
    public function testRouterFailsToMatchRequestCorrectly()
    {
        $router = new \Groundwork\Classes\Router();
                
        $router->register('foo/:id/:field', function($ioc) {});
        $matched = $router->matchRequest('/api/users/12/name', 'GET');
        
        $this->assertFalse($matched);
    }
    
    public function testRouterMatchesSpecificHTTPMethodCorrectly()
    {
        $router = new \Groundwork\Classes\Router();
                
        $router->register('gettest/:id/:field', function($ioc) {}, 'GET');
        $router->register('posttest/:id/:field', function($ioc) {}, 'POST');
        $router->register('puttest/:id/:field', function($ioc) {}, 'PUT');
        $router->register('deltest/:id/:field', function($ioc) {}, 'DELETE');
        
        $this->assertTrue($router->matchRequest('gettest/12/name', 'GET'));
        $this->assertTrue($router->matchRequest('posttest/12/name', 'POST'));
        $this->assertTrue($router->matchRequest('puttest/12/name', 'PUT'));
        $this->assertTrue($router->matchRequest('deltest/12/name', 'DELETE'));
    }
    
    public function testRouterMatchesSpecificHTTPMethodCorrectlyUsingShortcuts()
    {
        $router = new \Groundwork\Classes\Router();
                
        $router->get('gettest/:id/:field', function($ioc) {});
        $router->post('posttest/:id/:field', function($ioc) {});
        $router->put('puttest/:id/:field', function($ioc) {});
        $router->delete('deltest/:id/:field', function($ioc) {});
        
        $this->assertTrue($router->matchRequest('gettest/12/name', 'GET'));
        $this->assertTrue($router->matchRequest('posttest/12/name', 'POST'));
        $this->assertTrue($router->matchRequest('puttest/12/name', 'PUT'));
        $this->assertTrue($router->matchRequest('deltest/12/name', 'DELETE'));
    }
    
    public function testRouterFailsToMatchSpecificHTTPMethodCorrectly()
    {
        $router = new \Groundwork\Classes\Router();
                
        $router->register('gettest/:id/:field', function($ioc) {}, 'GET');
        $router->register('posttest/:id/:field', function($ioc) {}, 'POST');
        $router->register('puttest/:id/:field', function($ioc) {}, 'PUT');
        $router->register('deltest/:id/:field', function($ioc) {}, 'DELETE');
        
        $this->assertFalse($router->matchRequest('gettest/12/name', 'POST'));
        $this->assertFalse($router->matchRequest('posttest/12/name', 'GET'));
        $this->assertFalse($router->matchRequest('puttest/12/name', 'DELETE'));
        $this->assertFalse($router->matchRequest('deltest/12/name', 'PUT'));
    }
    
    public function testRouterObtainsRouteParamsCorrectly()
    {
        $router = new \Groundwork\Classes\Router();
                
        $router->register('users/:id/:field', function($ioc) {});
        $router->matchRequest('users/12/name', 'GET');
        $routeParams = $router->params;
        
        $this->assertEquals($routeParams->get('id'), '12');
        $this->assertEquals($routeParams->get('field'), 'name');
    }
    
    public function routesDataSet()
    {
        return array(
            array('','', 'home', true),
            array('','/', 'home', true),
            array('/','', 'home', true),
            array('/api/','/api/', 'home', true),
            array('','/home/1', 'home/:id', true),
            array('/api/','/api/home/1', 'home/:id', true),
            array('/api/','/api/home/1', 'home/:id?', true),
            array('/api/','/api/home', 'home/:id?', true),
            array('/api/sub/','/api/sub/home', 'home', true),
            array('/api/','/api/home/1', 'home', false),
            array('/api/','/api/foo/1', 'home/:id', false)
        );
    }
        
    /**
     * @dataProvider routesDataSet
     */
    public function testRouterReturnsClosureWithClosureMapping(
        $basedir, 
        $requestedRoute, 
        $registeredRoute, 
        $expected
    ) {
        $router = new \Groundwork\Classes\Router();
        
        $_SERVER['REQUEST_URI'] = $requestedRoute;
        $_SERVER['REQUEST_METHOD'] = 'GET';
        
        $request = new \Groundwork\Classes\Request($basedir, new \Groundwork\Classes\RequestBody());
        
        $router->register($registeredRoute, function($ioc) {});
        $matched = $router->matchRequest($request->route, 'GET');
        
        $this->assertTrue($matched === $expected);
    }
}
