<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit80c188db8f6e354210ff4c4bd39743dd
{
    public static $prefixesPsr0 = array (
        'H' => 
        array (
            'Highlight\\' => 
            array (
                0 => __DIR__ . '/..' . '/scrivo/highlight.php',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit80c188db8f6e354210ff4c4bd39743dd::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}