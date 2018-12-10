<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 08.11.2018
 * Time: 9:46
 */

namespace Service\Repository;

use core\Service\ServiceLocator;
use Service;
use Entity;
use Respect;
use Entity\Factory\Factory;
use Symfony\Component\Process\Exception\InvalidArgumentException;

class Meeting extends Repository
{

    /**
     * @var Service\DateTime
     */
    private $_dateTimeService;

    function __construct() {
        parent::__construct();
        $this->_init();
    }

    private function _init() {
        $this->_connection = $this->_databaseService->meetingConnection();
        $this->_mapper = Repository::mapper($this->_connection);
        $this->_mapper->entityNamespace = "\\Entity\\";
        $this->_mapper->setStyle(new Respect\Data\Styles\Meeting());
        $this->_db = new Respect\Relational\Db($this->_connection);
        $this->_initServices();
    }

    protected function _initServices() {
        $this->_dateTimeService = ServiceLocator::dateTimeService();
    }

    /**
     * @return Entity\UserType[]
     */
    public function getUsersTypes() {
        try {
            return $this->_loadObjects(array(), 'user_type');
        } catch (\Exception $e) {
            logException($e);
            return null;
        }
    }

    /**
     * @param string $sessionId
     * @return Entity\User
     */
    public function getUserBySessionId($sessionId) {
        try {
            /** @var Entity\User[] */
            return $this->_loadObjectByFilter(array('session_id' => $sessionId), 'user');
            //$user = $this->_loadObjects('user', null, true, true, array('session_id' => $sessionId), 'Entity\\User');
        } catch (\Exception $e) {
            logException($e);
            return null;
        }
    }

    /**
     * @param string $login
     * @return Entity\User
     */
    public function getUserByLogin($login) {
        /** @var Entity\User */
        return $this->_loadObjectByFilter(array('login' => $login), 'user');
    }

    /**
     * @param string $email
     * @return Entity\User
     */
    public function getUserByEmail($email) {
        /** @var Entity\User */
        return $this->_loadObjectByFilter(array('email' => $email), 'user');
    }

    /**
     * @param string $phone
     * @return Entity\User
     */
    public function getUserByPhone($phone) {
        /** @var Entity\User */
        return $this->_loadObjectByFilter(array('phone' => $phone), 'user');
    }

    /**
     * @param int $id
     * @return Entity\User
     */
    public function getUserById($id) {
        /** @var Entity\User */
        return $this->_loadObjectByFilter(array('id' => $id), 'user');
    }

    /**
     * @param string[] $fieldsToSearchIn
     * @param string $search
     * @param string $orderBy
     * @param bool $direction
     * @param int[] $limit
     * @param array $permissionsUserFor
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getUsersBySearch($fieldsToSearchIn, $search, $orderBy, $direction, $limit, $permissionsUserFor) {
        if (!is_array($fieldsToSearchIn) && count($fieldsToSearchIn) > 0) {
            throw new \InvalidArgumentException('Fields to search in must be not empty array');
        }

        if (!is_array($permissionsUserFor) && count($permissionsUserFor) > 0) {
            throw new \InvalidArgumentException('Permissions user for must be not empty array');
        }

        $select = $this->
            _db
            ->select('SQL_CALC_FOUND_ROWS *')
            ->from('user');

        $fieldsToSearchInCount = count($fieldsToSearchIn);
        $orLikeConditions = array();
        for ($i = 0; $i < $fieldsToSearchInCount; $i++) {
            $firstScope = $i === 0 ? '(' : '';
            $lastScope = $i === $fieldsToSearchInCount - 1 ? ')' : '';
            $orLikeConditions[] = "{$firstScope}{$fieldsToSearchIn[$i]} LIKE '%{$search}%'{$lastScope}";
        }
        $select->where(implode(' OR ', $orLikeConditions));

        $permissionSelfCondition = '';
        $permissionUserTypeCondition = '';
        $isFirstUserTypeCondition = true;
        foreach ($permissionsUserFor as $permissionUserFor => $permissionValue) {
            if ($permissionUserFor === 'self') {
                $permissionSelfCondition = $permissionValue['permission']
                    ? "id = {$permissionValue['id']} OR"
                    : "id != {$permissionValue['id']} AND";
                continue;
            }

            $logic = $permissionValue['permission']
                ? ($isFirstUserTypeCondition ? '' : ' OR ')
                : ($isFirstUserTypeCondition ? '' : ' AND ');
            $sign = $permissionValue['permission'] ? '=' : '!=';

            $isFirstUserTypeCondition = false;

            $permissionUserTypeCondition .= $logic . "user_type_id $sign {$permissionValue['id']}";
        }

        $select->and("( $permissionSelfCondition ($permissionUserTypeCondition))");

        $select->orderBy($orderBy);

        $direction ? $select->asc() : $select->desc();

        $objects = $select->limit($limit[0].','.$limit[1])->fetchAll(function($obj) {
            return Factory::createEntity((array) $obj, 'Entity\\User');
        });

        return $objects;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getAllUsersOfLastSearch() {

        $select =
            $this->_db
            ->select('FOUND_ROWS()')
            ->from('user')
            ->limit('1')
            ->fetchAll();
        if (count($select) <= 0) {
            throw new \Exception('Null rows return, when counting last database response');
        }
        return intval($select[0]->{'FOUND_ROWS()'});
    }

    /**
     * @param Entity\User $user
     */
    public function saveUser($user) {
        $this->_mapper->user->persist($user);
        $this->_mapper->flush();
    }

    /**
     * @param Entity\User $user
     */
    public function deleteUser($user) {
        $this->_mapper->user->remove($user);
        $this->_mapper->flush();
    }

    /**
     * @param Entity\Email $email
     */
    public function saveEmail($email) {
        $this->_mapper->email->persist($email);
        $this->_mapper->flush();
    }


    /**
     * @param Entity\ChangeConfirm[] $changesConfirms
     * @throws \InvalidArgumentException
     */
    public function saveChangesConfirms($changesConfirms) {
        if (!is_array($changesConfirms))
            throw new \InvalidArgumentException('Changes confirms to save must be an array');

        foreach ($changesConfirms as $changeConfirm) {
            $this->_mapper->{$this->realProperty('changeConfirm')}->persist($changeConfirm);
        }
        $this->_mapper->flush();
    }

    /**
     * @param string $entityName
     * @param array $types
     * @param string $newValue
     * @return Entity\ChangeConfirm[]
     */
    public function getActualChangesConfirmsByTypeAndNewValue($entityName, array $types, $newValue) {

        /** @var Entity\ChangeConfirm[] $result */
        $changesConfirms =
            $this->_db
                ->select('*')
                ->from('change_confirm')
                ->where(
                    array(
                        'entity_name'        => $this->realProperty($entityName),
                        'new_value'          => $newValue,
                        'date_time_expires >=' => $this->_dateTimeService->formatMySqlUtc() . ' UTC'
                    )
                )
                ->and("type IN ('" . implode("','", $types) . "')")
                ->fetchAll(function($obj) {
                    return Factory::createEntity((array) $obj, 'Entity\\ChangeConfirm');
                });
        return $changesConfirms;
    }

    /**
     * @param string $entityName
     * @param array $filter
     * @return Entity\ChangeConfirm[]
     */
    public function getChangeConfirmByEntityNameAndFilter($entityName, $filter) {
        $this->filterRealProperty($filter);
        return $this->_mapper->{$this->realProperty('changeConfirm')}(
            array_merge(
                $filter,
                array(
                    $this->realProperty('entityName') => $this->realProperty($entityName)
                )
            )
        )->fetchAll();
    }

    /**
     * @param Entity\ChangeConfirm[] $changesConfirms
     */
    public function removeChangesConfirms($changesConfirms) {
        foreach ($changesConfirms as $changeConfirm) {
            $this->_mapper->{$this->realProperty('changeConfirm')}->remove($changeConfirm);
        }
        $this->_mapper->flush();
    }

    /**
     * @param string $entityName
     * @param array $hashes
     */
    public function removeChangesConfirmsByHashes($entityName, $hashes) {

        /** @var Entity\ChangeConfirm[] $result */
        $changesConfirms =
            $this->_db
            ->select('*')
            ->from('change_confirm')
            ->where(array('entity_name' => $this->realProperty($entityName)))
            ->and("hash IN ('" . implode("','", $hashes) . "')")
            ->fetchAll(function($obj) {
                return Factory::createEntity((array) $obj, 'Entity\\ChangeConfirm');
            });

        if (count($changesConfirms) > 0)
            $this->removeChangesConfirms($changesConfirms);
    }
}