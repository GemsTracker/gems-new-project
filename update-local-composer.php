<?php

if (!is_dir('./lib')) {
    exit;
}

function dirs(string $path): array
{
    $dirs = [];
    foreach (glob($path . '/*') as $dir) {
        if ($dir === '.' || $dir === '..' || !is_dir($dir)) {
            continue;
        }

        $dirs[] = $dir;
    }
    return $dirs;
}

$composer = json_decode(file_get_contents(__DIR__ . '/composer.json'), true);

$localComposer = [
    'require' => [],
    'repositories' => [],
];

$libPath = './lib';
foreach (dirs($libPath) as $vendor) {
    $vendorPath = $vendor;
    $vendor = substr($vendor, strlen($libPath . '/'));

    foreach (dirs($vendorPath) as $package) {
        $packagePath = $package;
        $package = substr($package, strlen($vendorPath . '/'));

        if (!is_dir($packagePath . '/.git')) {
            continue;
        }

        if (!isset($composer['require'][$vendor . '/' . $package])) {
            continue;
        }
        $alias = $composer['require'][$vendor . '/' . $package];

        $localComposer['repositories'][] = [
            'type' => 'path',
            'url' => substr($packagePath, strlen($libPath . '/')),
        ];

        $branch = trim(`git -C "$packagePath" symbolic-ref --short HEAD`);

        if (str_contains($branch, 'not a git repository')) {
            continue;
        }

        if ($branch . '-dev' !== $alias) {
            $localComposer['require'][$vendor . '/' . $package] = 'dev-' . $branch . ' as ' . $alias;
        }
    }
}

$localJson = json_encode($localComposer);
if ($localJson !== file_get_contents('lib/composer.local.json')) {
    file_put_contents('lib/composer.local.json', $localJson);
    echo 'Updated local composer file; retry command' . PHP_EOL;
    exit(1);
}
