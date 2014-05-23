<?hh // strict
/**
 * groundwork-hacklang
 *
 * @author Nathan Davison <http://www.nathandavison.com>
 */

namespace Groundwork\Classes;

/**
 * All resource classes should extend from this class.
 */
abstract class HTTPResource
{    
    /**
     * The Request instance.
     */
    protected Request $request;
    
    /**
     * The Response instance.
     */
    protected Response $response;
        
    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
    }
                
    /**
     * Generate the output of the requested HTTPResource instance.
     */
    public function output(): void
    {
        if (method_exists($this, 'http_' . $this->request->httpMethod)) {
            // execute the requested HTTPResource instance's method that 
            // corresponds with the requested HTTP method.
            switch ($this->request->httpMethod) {
                case 'GET':
                    $this->http_GET();
                    break;
                    
                case 'POST':
                    $this->http_POST();
                    break;
                    
                case 'PUT':
                    $this->http_PUT();
                    break;
                    
                case 'DELETE':
                    $this->http_DELETE();
                    break;
                    
                case 'HEAD':
                    $this->http_HEAD();
                    break;
                    
                case 'OPTIONS':
                    $this->http_OPTIONS();
                    break;
                    
                default:
                    $this->response->send(405, 'The requested resource does not support the HTTP method'. ' "' . $this->request->httpMethod . '".');
                    break;
            }
        } else {
            // the HTTPResource instance didn't define a method for the request's HTTP method - return a 405.
            $this->response->send(405, 'The requested resource does not support the HTTP method'. ' "' . $this->request->httpMethod . '".');
        }   
    }
    
    /**
     * HTTP GET
     */
    protected function http_GET(): void
    {
    }
    
    /**
     * HTTP POST
     */
    protected function http_POST(): void
    {
    }
    
    /**
     * HTTP PUT
     */
    protected function http_PUT(): void
    {
    }
    
    /**
     * HTTP DELETE
     */
    protected function http_DELETE(): void
    {
    }
    
    /**
     * HTTP HEAD
     */
    protected function http_HEAD(): void
    {
    }
    
    /**
     * HTTP OPTIONS
     */
    protected function http_OPTIONS(): void
    {
    }
}
