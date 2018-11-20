<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 08.11.2018
 * Time: 11:00
 */

namespace core;


class Monitoring
{
    /**
     * @var bool
     */
    private static $_isInit = false;

    /**
     * @var bool
     */
    private static $_isPinbaPrepared = false;

    /**
     * @var array
     */
    private static $_pinbaData;

    /**
     * @var array [actionName => timerResource]
     */
    private static $_timers = [];

    public static function init() {
        if (self::$_isInit) {
            return;
        }
        self::_initPinbaConfig();
        self::$_isInit = true;
    }

    public static function disable() {
        if (!self::$_isPinbaPrepared) {
            return null;
        }
        ini_set('pinba.enabled', 0);
    }

    /**
     * @param string $category
     * @param string $action
     * @param array $tags
     * @return resource|null
     */
    public static function start($category, $action, array $tags = []) {
        if (!self::$_isPinbaPrepared) {
            return null;
        }
        $actionName = "{$category}::{$action}";
        $tags['__hostname'] = self::$_pinbaData['hostname'];
        $tags['__server_name'] = self::$_pinbaData['server_name'];
        $tags['category'] = $category;
        $tags['group'] = $actionName;
        self::$_timers[$actionName] = pinba_timer_start($tags);
    }

    /**
     * @param string $category
     * @param string $action
     */
    public static function stop($category, $action) {
        if (!self::$_isPinbaPrepared) {
            return;
        }
        $actionName = "{$category}::{$action}";
        if (!array_key_exists($actionName, self::$_timers)) {
            return;
        }
        pinba_timer_stop(self::$_timers[$actionName]);
    }

    private static function _initPinbaConfig() {
        if (!self::_checkPinbaIsInstalled()) {
            return;
        }
        $pinbaServerAndPort = $GLOBALS['config']['pinba']['server'];
        if (!$pinbaServerAndPort) {
            return;
        }
        ini_set('pinba.enabled', 1);
        ini_set('pinba.auto_flush', 1);
        ini_set('pinba.server', $pinbaServerAndPort);
        $pinbaData = pinba_get_info();
        if (!array_key_exists('server_name', $pinbaData) || !$pinbaData['server_name'] || $pinbaData['server_name'] === 'unknown') {
            $pinbaData['server_name'] = $GLOBALS['config']['cli_domain'];
            if (function_exists('pinba_server_name_set')) {
                pinba_server_name_set($pinbaData['server_name']);
            }
        }
        if (!array_key_exists('hostname', $pinbaData) || !$pinbaData['hostname'] || $pinbaData['hostname'] === 'unknown') {
            $pinbaData['hostname'] = $GLOBALS['config']['cli_domain'];
            if (function_exists('pinba_hostname_set')) {
                pinba_hostname_set($pinbaData['hostname']);
            }
        }
        self::$_pinbaData = $pinbaData;
        self::$_isPinbaPrepared = true;
    }

    /**
     * @return bool
     */
    private static function _checkPinbaIsInstalled() {
        return function_exists('pinba_get_info');
    }
}