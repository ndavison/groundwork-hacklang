<?hh

namespace Groundwork\Tests;

require __DIR__ . '/../../vendor/autoload.php';

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function testRequestEstablishesPropertiesOnCreation()
    {
        $_SERVER['REQUEST_URI'] = '/api/test/sub/1';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET = array('a' => '1', 'b' => '2');
        
        $request = new \Groundwork\Classes\Request('/api/', new \Groundwork\Classes\RequestBody());
        $uriValues = $request->uriValues;
        
        $this->assertEquals('test/sub/1', $request->route);
        $this->assertEquals('GET', $request->httpMethod);
        $this->assertEquals('2', $uriValues->get('b'));
    }
}
