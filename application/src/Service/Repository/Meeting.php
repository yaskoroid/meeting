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

class Meeting extends Repository {

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
     * @param string $sessionId
     * @return Entity\User
     */
    public function getUserBySessionId($sessionId) {
        try {
            /** @var Entity\User[] */
            return $this->_loadObjectByFilter(array('session_id' => $sessionId), 'User');
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
        return $this->_loadObjectByFilter(array('login' => $login), 'User');
    }

    /**
     * @param string $email
     * @return Entity\User
     */
    public function getUserByEmail($email) {
        /** @var Entity\User */
        return $this->_loadObjectByFilter(array('email' => $email), 'User');
    }

    /**
     * @param string $phone
     * @return Entity\User
     */
    public function getUserByPhone($phone) {
        /** @var Entity\User */
        return $this->_loadObjectByFilter(array('phone' => $phone), 'User');
    }

    /**
     * @param int $id
     * @return Entity\User
     */
    public function getUserById($id) {
        /** @var Entity\User */
        return $this->_loadObjectByFilter(array('id' => $id), 'User');
    }

    /**
     * @param string[] $fieldsToSearchIn
     * @param string $search
     * @param string $orderBy
     * @param bool $direction
     * @param int[] $limit
     * @param array $userPermissionsForUserRead
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getUsersBySearch($fieldsToSearchIn, $search, $orderBy, $direction, $limit, $userPermissionsForUserRead) {
        if (!is_array($fieldsToSearchIn) && count($fieldsToSearchIn) > 0) {
            throw new \InvalidArgumentException('Fields to search in must be not empty array');
        }

        if (!is_array($userPermissionsForUserRead) && count($userPermissionsForUserRead) > 0) {
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
        foreach ($userPermissionsForUserRead as $userPermissionForUserRead => $userPermissionValue) {
            if ($userPermissionForUserRead === 'self') {
                $permissionSelfCondition = $userPermissionValue['permission']
                    ? "id = {$userPermissionValue['id']} OR"
                    : "id != {$userPermissionValue['id']} AND";
                continue;
            }

            $logic = $userPermissionValue['permission']
                ? ($isFirstUserTypeCondition ? '' : ' OR ')
                : ($isFirstUserTypeCondition ? '' : ' AND ');
            $sign = $userPermissionValue['permission'] ? '=' : '!=';

            $isFirstUserTypeCondition = false;

            $permissionUserTypeCondition .= $logic . "user_type_id $sign {$userPermissionValue['id']}";
        }

        $select->and("( $permissionSelfCondition ($permissionUserTypeCondition))");

        $select->orderBy($this->realProperty($orderBy));

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
   /* public function saveUser($user) {
        $this->_mapper->user->persist($user);
        $this->_mapper->flush();
    }*/

    /**
     * @param Entity\User $user
     */
   /* public function deleteUser($user) {
        $this->_mapper->user->remove($user);
        $this->_mapper->flush();
    }*/

    /**
     * @param Entity\Email $email
     */
   /* public function saveEmail($email) {
        $this->_mapper->email->persist($email);
        $this->_mapper->flush();
    }*/


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
     * @return Entity\ChangeConfirm[]|array
     */
    public function getChangeConfirmByEntityNameAndFilter($entityName, $filter) {
        $this->filterRealProperty($filter);
        $changesConfirms = $this->_mapper->{$this->realProperty('changeConfirm')}(
            array_merge(
                $filter,
                array(
                    $this->realProperty('entityName') => $this->realProperty($entityName)
                )
            )
        )->fetchAll();

        return $this->_setRealMappers($changesConfirms, 'ChangeConfirm');
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

    /**
     * @param string $setting
     * @return mixed
     */
    /*public function getSettings($setting) {
        return $this->_mapper->{$this->realProperty($setting)}->fetchAll();
    }*/

    /**
     * @param string $setting
     * @param int $id
     * @return mixed
     */
    /*public function getSettingById($setting, $id) {
        return $this->_loadObjectByFilter(array('id' => $id), $setting);
    }*/

    /**
     * @param string $setting
     * @param mixed $entity
     */
    /*public function saveSetting($setting, $entity) {
        $this->_mapper->{$this->realProperty($setting)}->persist($entity);
        $this->_mapper->flush();
    }*/

    /**
     * @param string $setting
     * @param array $ids
     */
    /*public function deleteSettingsByIds($setting, array $ids) {

        $settingEntitiesToDelete =
            $this->_db
                ->select('*')
                ->from($this->realProperty($setting))
                ->where("id IN ('" . implode("','", $ids) . "')")
                ->fetchAll(function($obj) use ($setting) {
                    return Factory::createEntity((array) $obj, 'Entity\\' . ucfirst($setting));
                });

        if (count($settingEntitiesToDelete) > 0)
            $this->deleteSettings($setting, $settingEntitiesToDelete);
    }*/

    /**
     * @param string $settingName
     * @param array $settings
     */
    /*public function deleteSettings($settingName, array $settings) {
        foreach ($settings as $setting) {
            $this->_mapper->{$this->realProperty($settingName)}->remove($setting);
        }
        $this->_mapper->flush();
    }*/

    /**
     * @param string $entityName
     * @return array
     */
    public function getEntities($entityName) {
        $entities = $this->_mapper->{$this->realProperty($entityName)}->fetchAll();
        return $this->_setRealMappers($entities, $entityName);
    }

    /**
     * @param string $entityName
     * @param int $id
     * @return mixed
     */
    public function getEntityById($entityName, $id) {
        return $this->_loadObjectByFilter(array('id' => $id), $entityName);
    }

    /**
     * @param string $entityName
     * @param array $ids
     * @return Entity\File[]
     */
    public function getEntitiesByIds($entityName, array $ids) {

        return
            $this->_db
                ->select('*')
                ->from($this->realProperty($entityName))
                ->where("id IN ('" . implode("','", $ids) . "')")
                ->fetchAll(function($obj) use ($entityName) {
                    return Factory::createEntity((array) $obj, 'Entity\\'
                        . ucfirst($this->styledProperty($entityName)));
                });
    }

    /**
     * @param string $entityName
     * @param mixed $entity
     */
    public function saveEntity($entityName, $entity) {
        $this->_mapper->{$this->realProperty($entityName)}->persist($entity);
        $this->_mapper->flush();
    }

    /**
     * @param string $entityName
     * @param array $entities
     */
    public function saveEntities($entityName, array $entities) {
        if (!is_array($entities))
            throw new \InvalidArgumentException('Entities to save must be array');

        foreach ($entities as $entity)
            $this->_mapper->{$this->realProperty($entityName)}->persist($entity);

        if (count($entities) > 0)
            $this->_mapper->flush();
    }

    /**
     * @param string $entityName
     * @param array $ids
     */
    public function deleteEntitiesByIds($entityName, array $ids) {

        $entitiesToDelete = $this->getEntitiesByIds($entityName, $ids);

        if (count($entitiesToDelete) > 0)
            $this->deleteEntities($entityName, $entitiesToDelete);
    }

    /**
     * @param string $entityName
     * @param array $entities
     */
    public function deleteEntities($entityName, array $entities) {
        if (!is_array($entities))
            throw new \InvalidArgumentException('Entities to remove must be array');

        foreach ($entities as $entity) {
            $this->_mapper->{$this->realProperty($entityName)}->remove($entity);
        }

        if (count($entities) > 0)
            $this->_mapper->flush();
    }
}