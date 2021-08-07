<?php declare(strict_types=1);

use Sedder\Options;
use Sedder\Sedder;

require __DIR__ . '/vendor/autoload.php';

// Get params from the command line
$params = $argv;

// Remove the first element (script filename)
array_shift($params);

try {
    $options = (new Options($params))
                ->validate()
                ->parse();
} catch (InvalidArgumentException $e) {
    echo "\nError: " . $e->getMessage() . "\n\n";
    echo "Usage: {$argv[0]} [-i] 's/{search}/{replace}/' input-file\n\n";
    die(1);
}

(new Sedder($options))
    ->process()
    ->output();
