<?hh
/**
 * Function test for the home resource.
 */

namespace Tests;

class HomeResourceTest extends \PHPUnit_Framework_TestCase
{
    private $curlHandle;
    private $curlOptions;
    private $apiBaseURL;
    
    public function setUp()
    {
        require __DIR__ . '/../Config/Config.php';
        $config = new \Config\Config();
        $this->apiBaseURL = 'http://localhost' . $config->baseurl;
        $this->curlHandle = curl_init();
        $this->curlOptions[CURLOPT_RETURNTRANSFER] = true;
    }
    
    public function tearDown()
    {
        curl_close($this->curlHandle);
    }
    
    public function testGetHTTPMethodResponse()
    {
        $this->curlOptions[CURLOPT_URL] = $this->apiBaseURL.'?a=1&b=2';
        curl_setopt_array($this->curlHandle, $this->curlOptions);
        $response = curl_exec($this->curlHandle);
        $responseObj = json_decode($response);
        
        $this->assertTrue(is_a($responseObj, 'stdClass') === true);
        $this->assertEquals($responseObj->a, '1');
        $this->assertEquals($responseObj->b, '2');
        $this->assertEquals(200, curl_getinfo($this->curlHandle, 
                CURLINFO_HTTP_CODE));
    }
    
    public function testPostHTTPMethodResponse()
    {
        $this->curlOptions[CURLOPT_URL] = $this->apiBaseURL;
        $this->curlOptions[CURLOPT_CUSTOMREQUEST] = 'POST';
        $this->curlOptions[CURLOPT_POSTFIELDS] = json_encode(array(
            'testfield' => 'test',
            'testfield2' => 'test2'
        ));
        curl_setopt_array($this->curlHandle, $this->curlOptions);
        $response = curl_exec($this->curlHandle);
        $responseObj = json_decode($response);
        
        $this->assertTrue(is_a($responseObj, 'stdClass') === true);
        $this->assertEquals($responseObj->testfield, 'test');
        $this->assertEquals($responseObj->testfield2, 'test2');
        $this->assertEquals(200, curl_getinfo($this->curlHandle, 
                CURLINFO_HTTP_CODE));
    }
    
    public function testPutHTTPMethodResponse()
    {
        $this->curlOptions[CURLOPT_URL] = $this->apiBaseURL;
        $this->curlOptions[CURLOPT_CUSTOMREQUEST] = 'PUT';
        $this->curlOptions[CURLOPT_POSTFIELDS] = json_encode(array(
            'testfield' => 'test',
            'testfield2' => 'test2'
        ));
        curl_setopt_array($this->curlHandle, $this->curlOptions);
        $response = curl_exec($this->curlHandle);
        $responseObj = json_decode($response);
        
        $this->assertTrue(is_a($responseObj, 'stdClass') === true);
        $this->assertEquals($responseObj->testfield, 'test');
        $this->assertEquals($responseObj->testfield2, 'test2');
        $this->assertEquals(200, curl_getinfo($this->curlHandle, 
                CURLINFO_HTTP_CODE));
    }
    
    public function testDeleteHTTPMethodResponse()
    {
        $this->curlOptions[CURLOPT_URL] = $this->apiBaseURL;
        $this->curlOptions[CURLOPT_CUSTOMREQUEST] = 'DELETE';
        curl_setopt_array($this->curlHandle, $this->curlOptions);
        $response = curl_exec($this->curlHandle);

        $this->assertEquals($response, '"Deleted."');
        $this->assertEquals(200, curl_getinfo($this->curlHandle, 
                CURLINFO_HTTP_CODE));
    }
}