<?php

declare(strict_types=1);

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $filename = dirname(__DIR__) . '/.env';

    if (file_exists($filename)) {
        $lines = file($filename);
        foreach($lines as $line) {
            putenv(trim($line));
        }
    }
}
