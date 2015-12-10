<?php
/**
 * Copyright (c) 2015 Hazır Tur
 *    <http://hazirtur.com>
 */

/**
 * @object  Autoload
 * @author  Kerem <qeremy@gmail>
 */
final class Autoload
{
    /**
     * Singleton stuff.
     * @var self
     */
    private static $instance;

    /**
     * Application namespace
     * @var string
     */
    private static $namespace = 'Application';

    /**
     * Forbid idle initializations.
     */
    final private function __clone() {}
    final private function __construct() {}

    /**
     * Unregister auload.
     *
     * @return void
     */
    final public function __destruct() {
        spl_autoload_unregister([$this, 'load']);
    }

    /**
     * Get an instance of Autoload.
     *
     * @return self
     */
    final public static function init() {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Register SPL Autoload.
     *
     * @return bool
     */
    final public function register() {
        return spl_autoload_register([$this, 'load']);
    }

    /**
     * Load an object (class/trait/interface) file.
     *
     * @param  string $objectName
     * @return mixed
     * @throws \RuntimeException
     */
    final public static function load($objectName) {
        // Autoload::load('./Single')
        // Autoload::load('router/Router/Route')
        if (0 === strpos($objectName, './')) {
            $objectName = str_replace('.', self::$namespace, $objectName);
        }

        // internal Application object invoked
        if (0 === strpos($objectName, self::$namespace)) {
            $objectFile = self::fixSlashes(sprintf(
                '%s/%s/%s.php', __dir__,
                    self::$namespace,
                        // remove Application namespace once
                        substr_replace($objectName, '', 0, strlen(self::$namespace))
            ));
        } else {
            // external object invoked with namespace
            $objectFile = self::fixSlashes(sprintf(
                '%s/%s/%s.php', __dir__,
                    // here namespace a prefix as subdir
                    strtolower(substr($objectName, 0, strpos($objectName, '\\'))),
                        $objectName
            ));
            // try without namespace
            if (!is_file($objectFile)) {
                $objectFile = self::fixSlashes(sprintf(
                    '%s/%s/%s.php', __dir__,
                        // here namespace a prefix as subdir
                        strtolower($objectName),
                            $objectName
                ));
            }
        }

        // check file exists
        if (!is_file($objectFile)) {
            // for external libs
            // @todo open in the future!!!
            // if (get_global('throw_autoload_exception') === false) {
            //     return;
            // }
            // throw regular exception
            throw new \RuntimeException("Object file not found! file: `{$objectFile}`.");
        }

        // include file
        $return = require($objectFile);

        // !!! REMOVE THESE CONTROLS AFTER BASIC DEVELOPMENT !!!
        $objectName = str_replace('/', '\\', $objectName);

        // check: interface name is same with filaname?
        if (strripos($objectName, 'interface') !== false) {
            if (!interface_exists($objectName, false)) {
                throw new \RuntimeException(
                    "Interface file `{$objectFile}` has been loaded but no ".
                    "interface found such as `{$objectName}`.");
            }
            return $return;
        }
        // check: trait name is same with filaname?
        if (strripos($objectName, 'trait') !== false) {
            if (!trait_exists($objectName, false)) {
                throw new \RuntimeException(
                    "Trait file `{$objectFile}` has been loaded but no ".
                    "trait found such as `{$objectName}`.");
            }
            return $return;
        }
        // check: class name is same with filaname?
        if (!class_exists($objectName, false)) {
            throw new \RuntimeException(
                "Class file `{$objectFile}` has been loaded but no ".
                "class found such as `{$objectName}`.");
        }

        return $return;
    }

    /**
     * Prepare file path.
     *
     * @return string
     */
    final public static function fixSlashes($path) {
        return preg_replace(['~\\\\~', '~/+~'], '/', $path);
    }
}

// auto-init as a shorcut for require/include actions
return Autoload::init();
