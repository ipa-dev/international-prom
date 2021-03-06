<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita53a3c3e55193a5d2d58ecf2f3c953a4
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WPRemoteMediaExt\\RemoteMediaExt\\' => 32,
            'WPForms\\' => 8,
            'WPCore\\' => 7,
        ),
        'S' => 
        array (
            'Symfony\\Component\\EventDispatcher\\' => 34,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WPRemoteMediaExt\\RemoteMediaExt\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'WPForms\\' => 
        array (
            0 => __DIR__ . '/..' . '/loumray/wpforms/src',
        ),
        'WPCore\\' => 
        array (
            0 => __DIR__ . '/..' . '/loumray/wpcore/src',
        ),
        'Symfony\\Component\\EventDispatcher\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/event-dispatcher',
        ),
    );

    public static $prefixesPsr0 = array (
        'G' => 
        array (
            'Guzzle\\Tests' => 
            array (
                0 => __DIR__ . '/..' . '/guzzle/guzzle/tests',
            ),
            'Guzzle' => 
            array (
                0 => __DIR__ . '/..' . '/guzzle/guzzle/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita53a3c3e55193a5d2d58ecf2f3c953a4::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita53a3c3e55193a5d2d58ecf2f3c953a4::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInita53a3c3e55193a5d2d58ecf2f3c953a4::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
