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
     * @return Service\User\Profile
     */
    public static function userProfileService() {
        return self::_factory()->get("Service\\User\\Profile");
    }

    /**
     * @return Service\User\Type
     */
    public static function userTypeService() {
        return self::_factory()->get("Service\\User\\Type");
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
     * @return Service\User\Permission
     */
    public static function permissionService() {
        return self::_factory()->get("Service\\User\\Permission");
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
     * @return Service\Email
     */
    public static function emailService() {
        return self::_factory()->get("Service\\Email");
    }

    /**
     * @return Service\User\ChangeConfirm
     */
    public static function userChangeConfirmService() {
        return self::_factory()->get("Service\\User\\ChangeConfirm");
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
}