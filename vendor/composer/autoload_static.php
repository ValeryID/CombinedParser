<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit24ab75807c98e4df7b676c5aca3c481f
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit24ab75807c98e4df7b676c5aca3c481f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit24ab75807c98e4df7b676c5aca3c481f::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit24ab75807c98e4df7b676c5aca3c481f::$classMap;

        }, null, ClassLoader::class);
    }
}