<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit364abfd98f9c61e17e59ae23ae0fa37e
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit364abfd98f9c61e17e59ae23ae0fa37e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit364abfd98f9c61e17e59ae23ae0fa37e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit364abfd98f9c61e17e59ae23ae0fa37e::$classMap;

        }, null, ClassLoader::class);
    }
}