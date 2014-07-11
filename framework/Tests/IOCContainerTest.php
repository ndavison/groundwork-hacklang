<?hh

namespace Groundwork\Tests;

require __DIR__ . '/../../vendor/autoload.php';

class IOCContainerTest extends \PHPUnit_Framework_TestCase
{
    protected $app;
    
    public function setUp()
    {
        $this->app = new \Groundwork\Classes\IOCContainer();
    }
        
    public function testIocRegistersAnAlias()
    {
        $this->app->register('foo', function() {
            $object = new TestContainable();
            $object->name = 'foobar';
            return $object;
        });
        
        $foo = $this->app->get('foo');
        
        $this->assertTrue($foo instanceof TestContainable);
        $this->assertEquals($foo->name, 'foobar');
    }
    
    public function testIocReturnsSameInstance()
    {
        $this->app->register('foo', function() {
            $object = new TestContainable();
            $object->name = 'foobar';
            return $object;
        });
        
        $foo = $this->app->get('foo');
        
        $this->assertTrue($foo instanceof TestContainable);
        $this->assertEquals($foo->name, 'foobar');
        
        $foo->name = 'bar';
        
        $foo2 = $this->app->get('foo');
        
        $this->assertEquals($foo2->name, 'bar');
    }
    
    public function testIocReturnsNewInstance()
    {
        $this->app->register('foo', function() {
            $object = new TestContainable();
            $object->name = 'foobar';
            return $object;
        });
        
        $foo = $this->app->get('foo');
        
        $this->assertTrue($foo instanceof TestContainable);
        $this->assertEquals($foo->name, 'foobar');
        
        $foo->name = 'bar';
        
        $foo2 = $this->app->getNew('foo');
        
        $this->assertEquals($foo2->name, 'foobar');
    }
}

class TestContainable implements \Groundwork\Classes\IContainable
{
    public string $name = '';
}
