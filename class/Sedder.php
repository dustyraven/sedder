<?php declare(strict_types=1);

namespace Sedder;

class Sedder
{
    /**
     * @var Options $options
     */
    private $options;

    /**
     * @var string $data
     */
    private $data;

    /**
     * Class constructor
     * @param Options $options
     */
    public function __construct(Options $options)
    {
        $this->options = $options;
    }

    public function process(): Sedder
    {
        $this->data = str_replace(
            $this->options->getSearch(),
            $this->options->getReplace(),
            file_get_contents($this->options->getFilename())
        );
        return $this;
    }

    public function output(): Sedder
    {
        if ($this->options->isInplace()) {
            file_put_contents($this->options->getFilename(), $this->data);
        } else {
            echo $this->data;
        }
        return $this;
    }
}
