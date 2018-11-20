<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 04.07.2017
 * Time: 13:34
 */

namespace Service;

use core\Service\ServiceLocator;
use Service\Basic;
use Service;
use \InvalidArgumentException;

class Validator extends Basic
{

    /**
     * @var Service\User\Profile
     */
    private $_userProfileService;

    function __construct()
    {
        $this->_initServices();
    }

    private function _initServices() {
        $this->_userProfileService = ServiceLocator::userProfileService();
    }

    /**
     * @param array $validators
     */
    public function check(array $validators) {
        if (!is_array($validators)) {
            throw new \BadMethodCallException('Validators is not array');
        }

        foreach ($validators as $validator=>$valuesToCheck) {
            if (!is_array($valuesToCheck)) {
                $this->_validate($validator, $valuesToCheck);
                continue;
            }
            foreach ($valuesToCheck as $valueToCheck)
                $this->_validate($validator, $valueToCheck);
        }
    }

    /**
     * @param string $validator
     * @param string $value
     */
    private function _validate($validator, $value) {
        if (!method_exists($this, '_'.$validator))
            throw new \BadMethodCallException("Validator's method does not exists");

        $this->{'_'.$validator}($value);
    }

    private function _login($login) {

        if (empty($login))
            throw new \InvalidArgumentException('Login is empty');
        if (strlen($login) > 50)
            throw new \InvalidArgumentException('Login length is over than 30');
        $checkedLogin = preg_replace("/[^a-z0-9]/", "", $login);

        if ($checkedLogin !== $login) {
            throw new \InvalidArgumentException("Login has bad chars, must be 'a-z or 0-9'");
        }
    }

    private function _loginNotExists($login) {
        $user = $this->_userProfileService->getUserByLogin($login);

        if ($user === null)
            return;
        throw new \InvalidArgumentException('User with this login has been already exists');
    }

    private function _email($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if (!checkdnsrr(substr($email, strpos($email, "@") + 1, strlen($email)), 'MX')) {
                throw new \InvalidArgumentException('Bad email');
            }
        }
    }

    private function _emailNotExists($email)
    {
        $user = $this->_userProfileService->getUserByEmail($email);

        if ($user === null)
            return;
        throw new \InvalidArgumentException('User with this email has been already exists');
    }

    private function _int($var)
    {
        if (!(is_int(intval($var))))
            throw new \InvalidArgumentException('Value is not int');
    }

    private function _sortingDirection($var)
    {
        $var = strtoupper($var);
        if (!($var === 'DESC' || $var === 'ASC'))
            throw new \InvalidArgumentException('Bad sorting direction');
    }
}