<?hh // strict
/**
 * groundwork-hacklang
 *
 * @author Nathan Davison <http://www.nathandavison.com>
 */

namespace Groundwork\Classes;

/**
 * The Inversion of Control container.
 */
class Container
{
    /**
     * Class instances that the container manages.
     */
    private Map<string, IContainable> $instances = Map {};
    
    /**
     * Alias to closure mappings for generating the instances.
     */
    private Map<string, (function (Container): IContainable)> $registered = Map {};
    
    /**
     * Registers an alias and closure mapping.
     */
    public function register(string $alias, (function (Container): IContainable) $closure): void
    {       
        $this->registered->add(Pair {$alias, $closure});
    }
    
    /**
     * Returns the instance associated with the supplied alias.
     */
    public function get(string $alias): ?IContainable
    {
        // does the alias exist?
        if (!$this->registered->contains($alias)) {
            return null;
        }
        
        // does an instance already exist for this alias?
        $instance = $this->instances->get($alias);
        if ($instance !== null) {
            return $instance;
        }
        
        // execute the closure to get the first instance of this alias, and return it
        $closure = $this->registered->get($alias);
        if ($closure !== null) {
            $this->instances->set($alias, $closure($this));
        }
        return $this->instances->get($alias);
    }
    
    /**
     * Returns a new instance associated with the supplies alias.
     */
    public function getNew(string $alias): ?IContainable
    {
        // does the alias exist?
        if (!$this->registered->contains($alias)) {
            return null;
        }
        
        // execute the closure and return its return   
        $closure = $this->registered->get($alias);
        return ($closure !== null) ? $closure($this) : null;
    }
}
