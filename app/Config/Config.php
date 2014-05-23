<?hh // strict
/**
 * groundwork-hacklang
 *
 * @author Nathan Davison <http://www.nathandavison.com>
 */

namespace Config;

/**
 * Define app configuration in here.
 */
class Config
{
    /**
     * Define the base web directory with slashes e.g. if groundwork is 
     * accessible via http://localhost/gw/ then '/gw/' would be the value.
     */
    public string $baseurl = '/groundwork/public/';
}
