<?php declare(strict_types=1);

namespace Tests;

use Sedder\Options;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Mockery;

final class OptionsTest extends TestCase
{
    public function testParse()
    {
        $this->assertInstanceOf(Options::class, (new Options(['s/foo/bar/', __FILE__]))->parse());
    }

    public function testValidate()
    {
        $this->assertInstanceOf(Options::class, (new Options(['s/foo/bar/', __FILE__]))->validate());
    }

    public function testValidateBadNumberOfArguments()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid number of arguments.');

        (new Options(['blah']))->validate();
    }

    public function testValidateFileNotExists()
    {
        $filename = 'blah.txt';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('File "' . $filename . '" does not exists.');

        (new Options(['s/foo/bar/', $filename]))->validate();
    }

    public function testValidateBadFlag()
    {
        $flag = '-X';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown flag "' . $flag . '".');

        (new Options([$flag, 'blah', __FILE__]))->validate();
    }

    public function testValidateBadString()
    {
        $string = 'blah';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot parse "' . $string . '".');

        (new Options([$string, __FILE__]))->validate();
    }
}
