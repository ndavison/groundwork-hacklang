<?hh

namespace Groundwork\Tests;

require __DIR__ . '/../../vendor/autoload.php';

class ResourceTest extends \PHPUnit_Framework_TestCase
{
    public function httpRequestMethodDataSet()
    {
        return array(
            array('GET'),
            array('POST'),
            array('PUT'),
            array('DELETE')
        );
    }
    
    /**
     * @dataProvider httpRequestMethodDataSet
     */
    public function testResourceOutputMethodCallsCorrectMethod($httpMethod)
    {
        $_SERVER['REQUEST_URI'] = '/api/test';
        $_SERVER['REQUEST_METHOD'] = $httpMethod;
        
        $request = new \Groundwork\Classes\Request('/api/', new \Groundwork\Classes\RequestBody());
        $response = new \Groundwork\Classes\Response();
        $resource = new Dummy($request, $response);
		$resource->output();
        
        $this->assertEquals($resource->result, $httpMethod);
    }
}

class Dummy extends \Groundwork\Classes\HTTPResource
{       
	public string $result = '';

    protected function http_GET(): void
    {
        $this->result = 'GET';
    }
    
    protected function http_POST(): void
    {
        $this->result = 'POST';
    }
    
    protected function http_PUT(): void
    {
        $this->result = 'PUT';
    }
    
    protected function http_DELETE(): void
    {
        $this->result = 'DELETE';
    }
}
