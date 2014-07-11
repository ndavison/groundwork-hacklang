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
     * accessible under your web root as /gw/, then /gw/public/ would be 
     * the correct value here. In situation like this, consider setting 
     * up an alias (so <webroot>/gw/public is mapped to something nicer).
     */
    public string $baseurl = '/gw/public/';
}
