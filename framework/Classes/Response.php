<?hh // strict
/**
 * groundwork-hacklang
 *
 * @author Nathan Davison <http://www.nathandavison.com>
 */

namespace Groundwork\Classes;

/**
 * Handles the HTTP responses - all output should go through this sucker.
 */
class Response implements IContainable
{
    /**
     * An array of possible HTTP response codes along with their header value.
     */
    private Map<int, string> $codes = Map {
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        204 => 'No Content',
        301 => 'Moved Permanently',
        302 => 'Found',
        304 => 'Not Modified',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        410 => 'Gone',
        415 => 'Unsupported Media Type',
        417 => 'Expectation Failed',
        429 => 'Too Many Requests',
        500 => 'Internal Server Error',
        501 => 'Not Implemented'
    };
        
    /**
     * Output a JSON formatted response of the supplied body param, along with 
     * the supplied code param as the HTTP status code.
     */
    public function send(int $code, mixed $body = ''): void
    {
        // determine the code and body to return
        if ($this->codes->contains($code)) {
            if (!$body) {
                $body = $this->codes->get($code);
            }
        } else {
            $code = 500;
            $body = 'API attempted to return an unknown HTTP status.';
        }
                
        // respond
        header('HTTP/1.1 ' . $code . ' ' . $this->codes->get($code));
        header('Content-type: application/json');
        echo json_encode($body);
    }
}
