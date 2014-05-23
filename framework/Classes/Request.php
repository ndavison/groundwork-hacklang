<?hh
/**
 * groundwork-hacklang
 *
 * @author Nathan Davison <http://www.nathandavison.com>
 */

namespace Groundwork\Classes;

/**
 * Contains information about the request.
 */
class Request implements IContainable
{    
    /**
     * The RequestBody instance.
     */
    public RequestBody $body;
    
    /**
     * The query string array.
     */
    public Map<string, string> $uriValues = Map {};
    
    /**
     * A Map of any params in the URI that the matching route 
     * defined. E.g. /user/:id against /user/1 will put the value '1' in the 
     * key 'id' of this array.
     */
    public Map<string, string> $routeParams = Map {};
    
    /**
     * The route that was requested relative to the defined base 
     * of the app.
     */
    public string $route = '';
    
    /**
     * The HTTP method the request used.
     */
    public string $httpMethod = '';
            
    /**
     * Populate the properties on object creation.
     */
    public function __construct(string $basedir, RequestBody $body)
    {       
        // establish the route property
        $this->route = str_replace($basedir, '', $_SERVER['REQUEST_URI']);
        if (isset($_SERVER['QUERY_STRING'])) {
            $this->route = str_replace('?' . $_SERVER['QUERY_STRING'], '', $this->route);
        }
        $this->route = rtrim($this->route, '/');
        if ($this->route == '') {
            $this->route = 'home';
        }
        $this->route = ltrim($this->route, '/');
        
        // establish the httpMethod property
        $this->httpMethod = $_SERVER['REQUEST_METHOD'];
        
        // establish the body property
        $this->body = $body;
                
        // establish the URI values property
        if (isset($_GET) && is_array($_GET)) {
            foreach ($_GET as $key => $value) {
                $this->uriValues->add(Pair {$key, $value});
            }
        }
    }
        
    /**
     * Get the data in the request body as a JSON encoded string.
     */
    public function bodyToJSON(): string
    {
       return json_encode($this->body->data); 
    }
}
