<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6352f6a466ce8ed28010a12e45e8f033
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'MatthiasWeb\\WPU\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'MatthiasWeb\\WPU\\' => 
        array (
            0 => __DIR__ . '/..' . '/matthiasweb/wordpress-plugin-updater/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6352f6a466ce8ed28010a12e45e8f033::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6352f6a466ce8ed28010a12e45e8f033::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
