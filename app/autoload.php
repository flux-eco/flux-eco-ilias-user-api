<?php

//require_once __DIR__ . '/libs/flux-ilias-rest-api-client/autoload.php';

spl_autoload_register(function (string $class) {
    $namespace = "Flux\\IliasUserImportApi";
    $baseDirectory = '/app/src';
    loadClassFile($namespace, $class, $baseDirectory);
});

spl_autoload_register(function (string $class) {
    $namespace = "Shuchkin";
    $baseDirectory = '/app/libs/simple-xlsx/src';
    loadClassFile($namespace, $class, $baseDirectory);
});


/**
 * @param string $namespace
 * @param string $class
 * @param string $baseDirectory
 * @return void
 */
function loadClassFile(string $namespace, string $class, string $baseDirectory): void
{
    $classNameParts = explode($namespace, $class);
    // not our responsibility
    if (count($classNameParts) !== 2) {
        return;
    }
    $filePath = str_replace('\\', '/', $classNameParts[1]) . '.php';
    require $baseDirectory . $filePath;
}