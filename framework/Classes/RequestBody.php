<?hh // strict
/**
 * groundwork-hacklang
 *
 * @author Nathan Davison <http://www.nathandavison.com>
 */

namespace Groundwork\Classes;

/**
 * The HTTP request body.
 */
class RequestBody implements IContainable
{
	/**
	 * A Map of data that was in the request body.
	 */
	public Map<string, string> $data = Map {};

	/**
	 * Parse the request body on instantiation.
	 */
	public function __construct()
	{
		// get the request body raw data
		$body = file_get_contents('php://input', 'r');
		
		// handle the body if it is valid JSON
		if ($bodyAsJSON = json_decode($body)) {
			foreach ($bodyAsJSON as $property => $value) {
				$this->data->add(Pair {$property, $value});
			}
		} else if ($body) {
			throw new RequestException('The request body data was not valid JSON.');
		}
	}
}
