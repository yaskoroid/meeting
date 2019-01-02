<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 06.11.2018
 * Time: 11:20
 */

namespace core\Service;

use core;
use Service;

class ServiceLocator extends core\Service\BaseServiceLocator {

    /**
     * @return Service\Context
     */
    public static function contextService() {
        return self::_factory()->get("Service\\Context");
    }

    /**
     * @return Service\Repository\Database\Connector
     */
    public static function repositoryDatabaseConnectorService() {
        return self::_factory()->get("Service\\Repository\\Database\\Connector");
    }

    /**
     * @return Service\Repository\Database
     */
    public static function repositoryDatabaseService() {
        return self::_factory()->get("Service\\Repository\\Database");
    }

    /**
     * @return Service\Repository\Meeting
     */
    public static function repositoryMeetingService() {
        return self::_factory()->get("Service\\Repository\\Meeting");
    }

    /**
     * @return Service\Repository\InformationSchema
     */
    public static function repositoryInformationSchemaService() {
        return self::_factory()->get("Service\\Repository\\InformationSchema");
    }

    /**
     * @return Service\Entity\User
     */
    public static function userService() {
        return self::_factory()->get("Service\\Entity\\User");
    }

    /**
     * @return Service\Entity\UserType
     */
    public static function userTypeService() {
        return self::_factory()->get("Service\\Entity\\UserType");
    }

    /**
     * @return Service\Core\Auth
     */
    public static function authService() {
        return self::_factory()->get("Service\\Core\\Auth");
    }

    /**
     * @return Service\Downloader
     */
    public static function downloaderService() {
        return self::_factory()->get("Service\\Downloader");
    }

    /**
     * @return Service\Helper
     */
    public static function helperService() {
        return self::_factory()->get("Service\\Helper");
    }

    /**
     * @return Service\Image
     */
    public static function imageService() {
        return self::_factory()->get("Service\\Image");
    }

    /**
     * @return Service\Permission
     */
    public static function permissionService() {
        return self::_factory()->get("Service\\Permission");
    }

    /**
     * @return Service\Validator
     */
    public static function validatorService() {
        return self::_factory()->get("Service\\Validator");
    }

    /**
     * @return Service\Utils
     */
    public static function utilsService() {
        return self::_factory()->get("Service\\Utils");
    }

    /**
     * @return Service\Entity\Email
     */
    public static function emailService() {
        return self::_factory()->get("Service\\Entity\\Email");
    }

    /**
     * @return Service\Entity\ChangeConfirm
     */
    public static function changeConfirmService() {
        return self::_factory()->get("Service\\Entity\\ChangeConfirm");
    }

    /**
     * @return Service\DateTime
     */
    public static function dateTimeService() {
        return self::_factory()->get("Service\\DateTime");
    }

    /**
     * @return Service\Template
     */
    public static function templateService() {
        return self::_factory()->get("Service\\Template");
    }

    /**
     * @return Service\Path
     */
    public static function pathService() {
        return self::_factory()->get("Service\\Path");
    }

    /**
     * @return Service\Entity\Settings
     */
    public static function settingsService() {
        return self::_factory()->get("Service\\Entity\\Settings");
    }

    /**
     * @return Service\Entity\File
     */
    public static function fileService() {
        return self::_factory()->get("Service\\Entity\\File");
    }

    /**
     * @return Service\Entity\Task
     */
    public static function taskService() {
        return self::_factory()->get("Service\\Entity\\Task");
    }

    /**
     * @return Service\Entity\Base
     */
    public static function entityBaseService() {
        return self::_factory()->get("Service\\Entity\\Base");
    }
}