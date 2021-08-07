<?php declare(strict_types=1);

namespace Tests;

use Sedder\Sedder;
use Sedder\Options;
use PHPUnit\Framework\TestCase;
use Mockery;

final class SedderTest extends TestCase
{
    /**
     * @var Options|Mockery\MockInterface
     */
    private $options;

    /**
     * @var string $fixture
     */
    private $fixture;

    public function setUp()
    {
        parent::setUp();

        /** @var Options|Mockery\MockInterface */
        $this->options = Mockery::mock(Options::class);
        $this->fixture = __DIR__ . DIRECTORY_SEPARATOR . 'fixture.txt';
    }

    public function tearDown()
    {
        $this->options = null;

        parent::tearDown();
    }

    public function testProcessInstance()
    {
        $this->options->shouldReceive('getSearch')
            ->andReturn('foo');
        $this->options->shouldReceive('getReplace')
            ->andReturn('bar');
        $this->options->shouldReceive('getFilename')
            ->andReturn($this->fixture);

        $this->assertInstanceOf(Sedder::class, (new Sedder($this->options))->process());
    }

    public function testOutputInstance()
    {
        $this->options->shouldReceive('isInplace')
            ->andReturn(false);

        $this->assertInstanceOf(Sedder::class, (new Sedder($this->options))->output());
    }

    public function testAll()
    {
        $this->options->shouldReceive('isInplace')
            ->andReturn(false);
        $this->options->shouldReceive('getSearch')
            ->andReturn('sunshine');
        $this->options->shouldReceive('getReplace')
            ->andReturn('World');
        $this->options->shouldReceive('getFilename')
            ->andReturn($this->fixture);

        ob_start();

        (new Sedder($this->options))
            ->process()
            ->output();

        $this->assertSame("Hello World!\n", ob_get_clean());
    }
}
