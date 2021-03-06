<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf09d9e561b849a698ee391c01c1cf8f5
{
    public static $firstCharsPsr4 = array (
        'P' => true,
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\SimpleCache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/simple-cache/src',
        ),
        'PhpOffice\\PhpSpreadsheet\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpoffice/phpspreadsheet/src/PhpSpreadsheet',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->firstCharsPsr4 = ComposerStaticInitf09d9e561b849a698ee391c01c1cf8f5::$firstCharsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf09d9e561b849a698ee391c01c1cf8f5::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
