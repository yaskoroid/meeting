<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 08.11.2018
 * Time: 9:46
 */

namespace Service\Repository;

use Entity;
use Respect;
use Entity\Factory\Factory;
use Symfony\Component\Process\Exception\InvalidArgumentException;

class Meeting extends Repository
{

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
     * @param int $id
     * @return Entity\User
     */
    public function getUserById($id) {
        /** @var Entity\User */
        return $this->_loadObjectByFilter(array('id' => $id), 'user');
    }

    /**
     *
     */
    //SELECT COLUMN_NAME,COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS where TABLE_SCHEMA = 'roman' AND TABLE_NAME = 'user'
    /*public function getUserColumns() {
        $columns = array(
            'COLUMN_NAME', 'COLUMN_COMMENT'
        );
        $userTableColumnsList = $this->_loadObjectsColumns('user', $columns);
    }*/

    /**
     * @param string[] $fieldsToSearchIn
     * @param string $search
     * @param string $orderBy
     * @param bool $direction
     * @param int[] $limit
     * @param array $permissionsUserFor
     * @return array
     */
    public function getUsersBySearch($fieldsToSearchIn, $search, $orderBy, $direction, $limit, $permissionsUserFor) {
        if (!is_array($fieldsToSearchIn) && count($fieldsToSearchIn) > 0) {
            throw new InvalidArgumentException('Fields to search in must be no empty array');
        }

        if (!is_array($permissionsUserFor) && count($permissionsUserFor) > 0) {
            throw new InvalidArgumentException('Permissions user for must be not empty array');
        }

        $select = $this->
            _db
            ->select('SQL_CALC_FOUND_ROWS *')
            ->from('user');
        $fieldsToSearchInCount = count($fieldsToSearchIn);
        for ($i = 0; $i < $fieldsToSearchInCount; $i++) {
            $firstScope = $i === 0 ? '(' : '';
            $lastScope = $i === $fieldsToSearchInCount - 1 ? ')' : '';
            $condition = "{$firstScope}{$fieldsToSearchIn[$i]} LIKE '%{$search}%'{$lastScope}";
            $i == 0 ? $select->where($condition) : $select->or($condition);
        }

        foreach ($permissionsUserFor as $permissionUserFor => $permissionValue) {
            if (!$permissionValue['permission']) {
                $conditionField = $permissionUserFor === 'self' ? 'id' : 'user_type_id';
                $select->and("{$conditionField} != {$permissionValue['id']}");
            }
        }

        $select->orderBy($orderBy);

        $direction ? $select->asc() : $select->desc();

        $objects = $select->limit($limit[0].','.$limit[1])->fetchAll(function($obj) {
            return Factory::createEntity((array) $obj, 'Entity\\User');
        });
/*var_dump($fieldsToSearchIn);
var_dump($search);

        var_dump($orderBy);
        var_dump($direction);
        var_dump($limit);*/
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
     * @throws \Exception
     */
    public function saveUser($user) {
        $this->_mapper->user->persist($user);
        $this->_mapper->flush();
    }

    /**
     * @param Entity\Email $email
     * @throws \Exception
     */
    public function saveEmail($email) {
        $this->_mapper->email->persist($email);
        $this->_mapper->flush();
    }

    /**
     * @param Entity\UserChangeConfirm $userChangeConfirm
     * @throws \Exception
     */
    public function saveUserChangeConfirm($userChangeConfirm) {
        $this->_mapper->userChangeConfirm->persist($userChangeConfirm);
        $this->_mapper->flush();
    }
}