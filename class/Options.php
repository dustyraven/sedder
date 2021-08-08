<?php declare(strict_types=1);

namespace Sedder;

use InvalidArgumentException;

class Options
{
    const FLAGS = [
        '-i'
    ];

    const COMMANDS = [
        's'
    ];

    // NOTE: every ESCAPE_SEARCH/REPLACE should have corresponding UNESCAPE_SEARCH/REPLACE.

    const ESCAPE_SEARCH = [
        '\/'
    ];

    const ESCAPE_REPLACE = [
        '{{ESCAPE-SLASH}}'
    ];

    const UNESCAPE_SEARCH = [
        '{{ESCAPE-SLASH}}'
    ];

    const UNESCAPE_REPLACE = [
        '/'
    ];

    /**
     * @var array $source
     */
    private $source;

    /**
     * @var int $sourceCount
     */
    private $sourceCount;

    /**
     * @var bool $inplace
     */
    private $inplace;

    /**
     * @var string $search
     */
    private $search;

    /**
     * @var string $replace
     */
    private $replace;

    /**
     * @var string $filename
     */
    private $filename;

    /**
     * @return bool
     */
    public function isInplace(): bool
    {
        return $this->inplace;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getSearch(): string
    {
        return $this->search;
    }

    /**
     * @return string
     */
    public function getReplace(): string
    {
        return $this->replace;
    }

    /**
     * Class constructor
     * @param array $source
     */
    public function __construct(array $source)
    {
        $this->source = $source;
        $this->sourceCount = count($this->source);
    }

    /**
     * Parse input parameters
     * @return Options
     */
    public function parse(): Options
    {
        $this->filename = end($this->source);
        $this->inplace = '-i' === reset($this->source);

        list(, $this->search, $this->replace) = explode('/', $this->escape($this->source[$this->sourceCount - 2]));
        $this->search = $this->unescape($this->search);
        $this->replace = $this->unescape($this->replace);

        return $this;
    }

    /**
     * Validate input parameters
     * @return Options
     * @throws InvalidArgumentException
     */
    public function validate(): Options
    {

        if ($this->sourceCount < 2 || $this->sourceCount > 3) {
            throw new InvalidArgumentException('Invalid number of arguments.');
        }

        if (!file_exists(end($this->source))) {
            throw new InvalidArgumentException('File "' . end($this->source) . '" does not exists.');
        }

        if (3 === $this->sourceCount && !in_array(reset($this->source), static::FLAGS)) {
            throw new InvalidArgumentException('Unknown flag "' . reset($this->source) . '".');
        }

        $escaped = $this->escape($this->source[$this->sourceCount - 2]);

        if (!in_array(explode('/', $escaped)[0], static::COMMANDS)) {
            throw new InvalidArgumentException('Unknown command "' . explode('/', $escaped)[0] . '".');
        }

        if (!preg_match('@^[' . implode('', static::COMMANDS) . ']\/[^\/]+?\/[^\/]*?\/$@', $escaped)) {
            throw new InvalidArgumentException('Cannot parse "' . $this->source[$this->sourceCount - 2] . '".');
        }

        return $this;
    }

    public function escape(string $string): string
    {
        return str_replace(static::ESCAPE_SEARCH, static::ESCAPE_REPLACE, $string);
    }

    public function unescape(string $string): string
    {
        return str_replace(static::UNESCAPE_SEARCH, static::UNESCAPE_REPLACE, $string);
    }
}
