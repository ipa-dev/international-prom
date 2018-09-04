<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit632b75aab0ec601b5bc6f8edb8ff3391
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit632b75aab0ec601b5bc6f8edb8ff3391::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit632b75aab0ec601b5bc6f8edb8ff3391::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
