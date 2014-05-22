<?hh // strict
/**
 * groundwork-hacklang
 *
 * @author Nathan Davison <http://www.nathandavison.com>
 */
 
namespace Resources;

use \Groundwork\Classes\HTTPResource;
use \Groundwork\Classes\Request;
use \Groundwork\Classes\Response;
use \Groundwork\Classes\IContainable;

/**
 * The Home resource. This is an example of how a resource class should be 
 * defined.
 */
class HomeResource extends HTTPResource implements IContainable
{           
    /**
     * HTTP GET
     * 
     * This will respond with a JSON version of whatever data is in the query 
     * string when accessed via a standard GET request.
     */
    protected function http_GET(): void
    {
        $this->response->send(200, $this->request->uriValues);
    }
    
    /**
     * HTTP POST
     * 
     * This will respond with a JSON version of whatever data is in the POST 
     * request body.
     */
    protected function http_POST(): void
    {
        $this->response->send(200, $this->request->body->data);
    }
    
    /**
     * HTTP PUT
     * 
     * This will respond with a JSON version of whatever data is in the PUT 
     * request body.
     */
    protected function http_PUT(): void
    {
        $this->response->send(200, $this->request->body->data);
    }
    
    /**
     * HTTP DELETE
     * 
     * This will respond with a message when this resource is accessed via a 
     * HTTP DELETE request.
     */
    protected function http_DELETE(): void
    {
        $this->response->send(200, 'Deleted.');
    }
}
