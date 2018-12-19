<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 04.07.2017
 * Time: 13:34
 */

namespace Service;

use core\Service\ServiceLocator;
use Service;
use \InvalidArgumentException;
use model\Def;

class Validator extends Basic
{

    /**
     * @var Service\User\Profile
     */
    private $_userProfileService;

    /**
     * @var Service\Utils
     */
    private $_utilsService;

    /**
     * @var Service\User\Type
     */
    private $_userTypeService;

    /**
     * @var Service\ChangeConfirm
     */
    private $_changeConfirmService;

    /**
     * @var Service\Settings
     */
    private $_settingsService;

    function __construct() {
        self::_initServices();
    }

    private function _initServices() {
        $this->_userProfileService   = ServiceLocator::userProfileService();
        $this->_utilsService         = ServiceLocator::utilsService();
        $this->_userTypeService      = ServiceLocator::userTypeService();
        $this->_changeConfirmService = ServiceLocator::changeConfirmService();
        $this->_settingsService   = ServiceLocator::settingsService();
    }

    /**
     * @param array $validators
     */
    public function check(array $validators) {
        if (!is_array($validators))
            throw new \BadMethodCallException('Validators is not array');

        foreach ($validators as $validator=>$valueToCheck) {

            if (!is_string($validator))
                throw new \InvalidArgumentException('Validator must be string');

            if ($this->_isArrayValueToCheck($valueToCheck)) {
                foreach ($valueToCheck as $oneValueToCheck)
                    $this->_validateValueToCheck($validator, $oneValueToCheck);
                continue;
            }

            $this->_validateValueToCheck($validator, $valueToCheck);
        }
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private function _isValueToCheck($value) {
        return is_string($value);
    }

    /**
     * @param array|string $valueToCheck
     * @return bool
     */
    private function _isValueToCheckHaveValidatorParams($valueToCheck) {

        if (!is_array($valueToCheck))
            return false;

        if (count($valueToCheck) !== 2)
            return false;//throw new \InvalidArgumentException('Value of validator argument must be an array with one element');

        if ($this->_utilsService->isAssoc($valueToCheck))
            throw new \InvalidArgumentException('Value of validator with params must be not assoc array');

        if (!$this->_isValueToCheck($valueToCheck[0]))
            throw new \InvalidArgumentException('Value of validator with params must be an array with the key that is value to check and must have a string type');

        if (!is_array($valueToCheck[1]))
            return false;

        foreach ($valueToCheck[1] as $validatorParam) {
            if (!is_scalar($validatorParam))
                return false;
        }

        return true;
    }

    /**
     * @param array|string $valueToCheck
     * @return bool
     */
    private function _isArrayValueToCheck($valueToCheck) {

        if ($this->_isValueToCheck($valueToCheck))
            return false;

        try {
            if ($this->_isValueToCheckHaveValidatorParams($valueToCheck))
                return false;
        } catch (\Exception $e) {}

        if (!is_array($valueToCheck))
            return false;

        if ($this->_utilsService->isAssoc($valueToCheck))
            throw new \InvalidArgumentException('Array of values to check must be sequence, not assoc');

        $valuesToCheckCount = 0;
        $valuesToCheckHaveValidatorParamsCount  = 0;
        foreach ($valueToCheck as $valuesToCheck) {
            if ($this->_isValueToCheck($valuesToCheck))
                $valuesToCheckCount++;

            if ($this->_isValueToCheckHaveValidatorParams($valuesToCheck))
                $valuesToCheckHaveValidatorParamsCount++;
        }

        if (($valuesToCheckCount === count($valueToCheck) && $valuesToCheckHaveValidatorParamsCount === 0) ||
            ($valuesToCheckHaveValidatorParamsCount === count($valueToCheck) && $valuesToCheckCount === 0))
            return true;

        throw new \InvalidArgumentException('Values to check for validator have wrong syntax or not alike');
    }

    /**
     * @param string $validator
     * @param array|string $valueToCheck
     */
    private function _validateValueToCheck($validator, $valueToCheck) {
        if ($this->_isValueToCheck($valueToCheck)) {
            $this->_validate($validator, $valueToCheck);
            return;
        }

        if ($this->_isValueToCheckHaveValidatorParams($valueToCheck)) {
            $this->_validate($validator, $valueToCheck[0], $valueToCheck[1]);
            return;
        }

        throw new \InvalidArgumentException('Bad argument for validate value');
    }

    /**
     * @param string $validator
     * @param string $value
     * @param array $valueToCheckValidatorParams
     */
    private function _validate($validator, $value, array $valueToCheckValidatorParams = array()) {
        if (!method_exists($this, '_' . $validator))
            throw new \BadMethodCallException("Validator's method does not exists");

        if (!$this->_isValueToCheck($value))
            throw new \InvalidArgumentException('Value to check must be have string type');

        if (!is_array($valueToCheckValidatorParams))
            throw new \InvalidArgumentException('Validator params bust be an array');

        if (!empty($valueToCheckValidatorParams)) {
            $this->{'_' . $validator}($value, $valueToCheckValidatorParams);
            return;
        }

        $this->{'_' . $validator}($value);
    }

    /**
     * @param string $string
     * @param array $minmax
     */
    private function _strlen($string, array $minmax = array()) {
        if (!is_array($minmax) || empty($minmax))
            throw new \InvalidArgumentException('String min and max length values must be an not empty array');

        $min = $this->_utilsService->arrayGetRecursive($minmax, array(0));
        $max = $this->_utilsService->arrayGetRecursive($minmax, array(1));

        if ($min === null || $max === null)
            throw new \InvalidArgumentException('Strlen validator must have min and max length params');

        try {
            $this->_intPositive($min);
            $this->_intPositive($max);
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException('String max or min length must be positive numeric');
        }

        if ($max < $min)
            throw new \InvalidArgumentException('Strlen validator max parameter must be more than min');

        if (strlen($string) > $max)
            throw new \InvalidArgumentException("Length is over than $max");
        if (strlen($string) < $min)
            throw new \InvalidArgumentException("Length is less than $min");
    }

    /**
     * @param string $login
     */
    private function _login($login) {
        $this->_strlen($login, array(4, 50));

        $checkedLogin = preg_replace("/[^a-z0-9]/", "", $login);

        if ($checkedLogin !== $login)
            throw new \InvalidArgumentException("Login has bad chars, must be 'a-z or 0-9'");
    }

    /**
     * @param string $login
     */
    private function _loginNotExists($login) {
        $user = $this->_userProfileService->getUserByLogin($login);

        if ($user === null)
            return;
        throw new \InvalidArgumentException('User with this login has been already exists');
    }

    /**
     * @param string $email
     */
    private function _loginUserCreateConfirm($email) {
        $loginsOfUserCreation = $this->_changeConfirmService->getLoginOfUserCreation($email);

        if (is_array($loginsOfUserCreation) && count($loginsOfUserCreation) > 0)
            throw new \InvalidArgumentException('This login used in account creation request');
    }

    /**
     * @param string $email
     */
    private function _email($email) {
        $this->_strlen($email, array(1, 80));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            throw new \InvalidArgumentException('Bad email value');

        if (!checkdnsrr(substr($email, strpos($email, "@") + 1, strlen($email)), 'MX'))
                throw new \InvalidArgumentException('Bad email');
    }

    /**
     * @param string $email
     */
    private function _emailNotExists($email) {
        $user = $this->_userProfileService->getUserByEmail($email);

        if ($user === null)
            return;
        throw new \InvalidArgumentException('User with this email has been already exists');
    }

    /**
     * @param string $email
     */
    private function _emailUserCreateConfirm($email) {
        $emailsOfUserCreation = $this->_changeConfirmService->getEmailOfUserCreation($email);

        if (is_array($emailsOfUserCreation) && count($emailsOfUserCreation) > 0)
            throw new \InvalidArgumentException('This email used in account creation or email changing request');
    }

    /**
     * @param string $userTypeId
     */
    private function _userTypeId($userTypeId) {
        $this->_intPositive($userTypeId);

        $userTypes = $this->_userTypeService->getUsersTypes();
        if (empty($userTypes) || !is_array($userTypes))
            throw new \InvalidArgumentException('Users types ids is empty or not array');

        if (!array_key_exists(intval($userTypeId), $userTypes))
            throw new \InvalidArgumentException('User type id is not valid');
    }

    /**
     * @param string $phone
     */
    private function _phone($phone) {
        if (strlen($phone) !== Def\Def::$constPhoneNumberLength + 1)
            throw new \InvalidArgumentException('Phone must have ' . (Def\Def::$constPhoneNumberLength + 1) . ' chars');

        if (substr($phone, 0, 1) !== '+')
            throw new \InvalidArgumentException("Phone first char must be a '+'");

        if (!preg_match('/[0-9]/', substr($phone, 1)))
            throw new \InvalidArgumentException("Phone  be a '+' and " . Def\Def::$constPhoneNumberLength .
                ' digits');
    }

    /**
     * @param string $phone
     */
    private function _phoneNotExists($phone) {
        $user = $this->_userProfileService->getUserByPhone($phone);

        if ($user === null)
            return;
        throw new \InvalidArgumentException('User with this phone has been already exists');
    }

    /**
     * @param string $phone
     */
    private function _phoneUserCreateConfirm($phone) {
        $phonesOfUserCreation = $this->_changeConfirmService->getPhoneOfUserCreation($phone);

        if (is_array($phonesOfUserCreation) && count($phonesOfUserCreation) > 0)
            throw new \InvalidArgumentException('This phone used in account creation');
    }

    /**
     * @param string $zeroone
     */
    private function _zeroone($zeroone) {
        $this->_intPositive($zeroone);

        $intZeroOne = intval($zeroone);

        if ($intZeroOne < 0 || $intZeroOne > 1)
            throw new \InvalidArgumentException("Value must be '0' or '1'");
    }

    /**
     * @param string $filePath
     */
    private function _extImage($filePath) {
        $imagesExt = Service\Downloader::EXT_IMG;

        $fileExt = $this->_utilsService->getExtention($filePath);
        if (!in_array(pathinfo($filePath, PATHINFO_EXTENSION), $imagesExt))
            throw new \InvalidArgumentException('Bad image file extension');

    }

    /**
     * @param string $filePath
     */
    private function _mimeImage($filePath) {
        $imagesMime = Service\Downloader::MIME_IMG_TYPES;

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if (!in_array(finfo_file($finfo, $filePath), $imagesMime)) {
            finfo_close($finfo);
            throw new \InvalidArgumentException('Bad image file type');
        }
        finfo_close($finfo);
    }

    /**
     * @param string $password
     */
    private function _password($password) {
        $this->_strlen($password, array(3, 50));
    }

    /**
     * @param string $hash
     */
    private function _hash128($hash) {
        $this->_strlen($hash, array(128, 128));

        $checkedHash = preg_replace("/[^a-f0-9]/", "", $hash);

        if ($checkedHash !== $hash)
            throw new \InvalidArgumentException("Hash has bad chars, must be 'a-f or 0-9'");
    }

    /**
     * @param string $var
     */
    private function _int($var) {
        if (strlen($var) < 1 || strlen($var) > 11)
            throw new \InvalidArgumentException('Integer must have from 1 to 11 chars');

        if (substr($var, 0, 1) === '-' && (strlen($var) < 2 || strlen($var) > 11))
            throw new \InvalidArgumentException('Negative integer must have a value');

        if (!(is_string($var) || is_int($var)) ||
            preg_match('/[^-0-9]/', strval($var)) ||
            intval($var) < -2147483647 ||
            intval($var) > 2147483647)
            throw new \InvalidArgumentException('Value is not integer');
    }

    /**
     * @param string $var
     */
    private function _intPositive($var) {
        $this->_int($var);
        if (intval($var) < 0)
            throw new \InvalidArgumentException('Value is not integer positive');
    }

    /**
     * @param string $var
     */
    private function _intPositiveCommaSeparated($var) {
        if (!is_string($var))
            throw new \InvalidArgumentException('Values is not string');

        if (empty($var))
            throw new \InvalidArgumentException('Values is empty string');

        $values = explode(',', $var);

        if (count($values) === 0)
            throw new \InvalidArgumentException('Values count is zero');

        foreach($values as $value) {
            $this->_intPositive($value);
        }
    }

    /**
     * @param string$var
     */
    private function _sortingDirection($var) {
        $var = strtoupper($var);
        if (!($var === 'DESC' || $var === 'ASC'))
            throw new \InvalidArgumentException('Bad sorting direction');
    }

    /**
     * @param string $settingId
     * @param array $settingOneParameter
     * @throws \InvalidArgumentException
     */
    private function _settingExists($settingId, array $settingOneParameter) {
        $this->_intPositive($settingId);

        if (count($settingOneParameter) !== 1)
            throw new \InvalidArgumentException('Parameter to check setting must be single');

        if (!is_string($settingOneParameter[0]))
            throw new \InvalidArgumentException('Setting must be string');

        $settingEntity = $this->_settingsService->getById($settingOneParameter[0], $settingId);

        if ($settingEntity === null)
            throw new \InvalidArgumentException(ucfirst($settingOneParameter[0]) . ' with this id not found');
    }

    /**
     * @param string $date
     * @throws \InvalidArgumentException
     */
    private function _date($date) {
        $this->_strlen($date, array(10, 10));

        if (substr_count($date, '-') !== 2)
            throw new \InvalidArgumentException("Date must have two '-' symbols");

        $dateParams = explode('-', $date);

        if (!checkdate($dateParams[1], $dateParams[2], $dateParams[0]))
            throw new \InvalidArgumentException(
                "Date with year $dateParams[0], month $dateParams[1], and day $dateParams[2] is not valid"
            );
    }
}
