<?php

namespace Zf2auth\Table;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zf2auth\Entity\Users;

class UsersTable extends AbstractTableGateway
{

    protected $table = 'users';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Users());

        $this->initialize();
    }

    public function fetchAll(Select $select = null, $paginated = false)
    {
        $adapter = $this->adapter;
        if (null === $select)
            $select = new Select();
        $select->from($this->table);
        if ($paginated) {
            $paginatorAdapter = new DbSelect($select, $adapter);
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        } else {
            $sql = new Sql($adapter);
            $statement = $sql->getSqlStringForSqlObject($select);
            $resultSet = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
            $resultSet->buffer();
            return $resultSet;
        }
    }

    public function getUsers($id)
    {
        $id = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            return false;
        }
        return $row;
    }

    public function saveUsers(Users $users)
    {
        $result = array(
            'status' => 0,
            'message' => ''
        );
        $data = array(
            'name' => $users->name,
            'username' => $users->username,
            'email' => $users->email,
            'password' => $users->password,
            'salt' => $users->salt,
            'email_check_code' => $users->email_check_code,
            'is_disabled' => $users->is_disabled,
            'created' => date('Y-m-d h:i:s'),
            'modified' =>  date('Y-m-d h:i:s'),
        );

        $id = (int) $users->id;
        if ($id == 0) {
            unset($data['modified_by']);
            unset($data['modified']);
            $result['status'] = $this->insert($data);
        } else {
            if ($this->getUsers($id)) {
                unset($data['created_by']);
                unset($data['created']);
                $result['status'] = $this->update($data, array('id' => $id));
            } else {
                $result['message'] = 'ID does not exist';
            }
        }

        return $result;
    }

    public function deleteUsers($id)
    {
        $result = array(
            'status' => 0,
            'message' => ''
        );
        try {
            $result['status'] = $this->delete(array('id' => $id));
        } catch (\Exception $e) {
            $result['message'] = 'Information can not be deleted.';
        }
        return $result;
    }

    public function dropdownUsers(Select $select = null)
    {
        if (null === $select)
            $select = new Select();
        $select->from($this->table);
        $resultSet = $this->selectWith($select);
        $resultSet->buffer();

        $options = array();
        $options[''] = '--- Please Select ---';
        if (count($resultSet) > 0) {
            foreach ($resultSet as $row)
                $options[$row->getId()] = $row->getName();
        }
        return $options;
    }

    public function truncate()
    {
        $adapter = $this->adapter;
        $statement = 'SET foreign_key_checks=0;TRUNCATE TABLE ' . $this->table.' ;SET foreign_key_checks=1;';
        $resultSet = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
    }

    /**
     * Custom finction
     */
    public function getUsersByUserName($username)
    {
        $rowset = $this->select(array('username' => $username));
        $row = $rowset->current();
        if (!$row) {
            return false;
        }
        return $row;
    }

    public function getUsersByEmail($email, $id = 0)
    {
        $where = new \Zend\Db\Sql\Where();
        $where->notEqualTo('id', (int) $id);
        $where->equalTo('email', $email);
        $rowset = $this->select($where);
        $row = $rowset->current();
        if (!$row) {
            return false;
        }
        return $row;
    }

    public function ConfirmEmailCheckCode(Users $users)
    {

        $data = array(
            'email_check_code' => $users->email_check_code,
            'is_disabled' => 0,
        );

        $id = (int) $users->id;
        if ($id == 0) {
            $this->insert($data);
        } else {
            if ($this->getUsers($id)) {
                $this->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function saveRegistration(Users $users, $formData)
    {
        $return = array();
        $adapter = $this->adapter;
//        echo "<pre>";
//        print_r($formData);
//        echo "</pre>";
//        die();
        $email_check_code = $this->getEmailCheckCode();
        $data = array(
            'username' => $users->email,
            'email' => $users->email,
            'password' => md5($users->password),
            'email_check_code' => $email_check_code,
            'is_disabled' => 0,
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s'),
        );

        $id = (int) $users->id;
        if ($id == 0) {
            unset($data['modified']);
            $this->adapter->getDriver()->getConnection()->beginTransaction();

            $this->insert($data);
            $user_id = $this->lastInsertValue;

            /**
             * Insert User Role Table
             */
            $userrolesdata = array(
                'user_id' => $user_id,
                'role_id' => empty($formData['role_id']) ? DEFAULT_ROLE_ID : $formData['role_id'],
            );
            $sqluserroles = new Sql($this->adapter);
            $insertuserroles = $sqluserroles->insert('user_roles')->values(
                    $userrolesdata
            );
            $statementuserroles = $sqluserroles->getSqlStringForSqlObject($insertuserroles);
            $resultSetuserroles = $adapter->query($statementuserroles, $adapter::QUERY_MODE_EXECUTE);
            $resultSetuserroles->buffer();
            $this->adapter->getDriver()->getConnection()->commit();
            $return['status'] = 1;
        } else {

            unset($data['created']);
            unset($data['username']);
            unset($data['email']);
            unset($data['password']);
            unset($data['email_check_code']);

            if ($this->getUsers($id)) {

                $this->adapter->getDriver()->getConnection()->beginTransaction();
                $this->update($data, array('id' => $id));



                $user_id = $id;

                /**
                 * Insert User Role Table
                 */
                $userrolesdata = array(
                    'user_id' => $user_id,
                    'role_id' => $formData['role_id'],
                );
                $sqluserroles = new Sql($this->adapter);
                $user_role_id = $formData->user_role_id;
                if (!empty($user_role_id)) {
                    $insertuserroles = $sql->update('user_roles')->set(
                                    $userrolesdata
                            )->where(array('id' => $user_role_id,));
                } else {
                    $insertuserroles = $sql->insert('user_roles')->values(
                            $userrolesdata
                    );
                }


                $statementuserroles = $sqluserroles->getSqlStringForSqlObject($insertuserroles);
                $resultSetuserroles = $adapter->query($statementuserroles, $adapter::QUERY_MODE_EXECUTE);
                $resultSetuserroles->buffer();
                $this->adapter->getDriver()->getConnection()->commit();
                $return['status'] = 1;
            } else {
                $return['status'] = false;
                $return['message'] = 'Form id does not exist';
            }
        }
        return $return;
    }

    public function resetPassword(Users $users)
    {
        $adapter = $this->adapter;
//        echo "<pre>";
//        print_r($users);
//        echo "</pre>";
//        die();
        $data = array(
            'password' => md5($users->password),
        );

        $id = (int) $users->id;
        if ($id > 0) {
            if ($this->getUsers($id)) {
                return $result = $this->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function savePasswordAccessToken(Users $users)
    {
        $adapter = $this->adapter;
//        echo "<pre>";
//        print_r($users);
//        echo "</pre>";
//        die();
        $data = array(
            'password_access_tocken' => $users->password_access_tocken,
            'access_token_valid_till' => $users->access_token_valid_till,
        );

        $id = (int) $users->id;
        if ($id > 0) {
            if ($this->getUsers($id)) {
                return $result = $this->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function changePassword(Users $users)
    {
        $adapter = $this->adapter;
//        echo "<pre>";
//        print_r($users);
//        echo "</pre>";
//        die();
        $data = array(
            'password' => md5($users->password),
            'password_access_tocken' => '',
            'access_token_valid_till' => '',
        );

        $id = (int) $users->id;
        if ($id > 0) {
            if ($this->getUsers($id)) {
                $this->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function fetchAllByIdentity($identity, $identitykey = 'email')
    {
        $adapter = $this->adapter;
        $select = new Select();
        $select->from($this->table);
        $select->join('user_roles', 'user_roles.user_id = users.id', array('role_id' => 'role_id'), 'inner');
        $select->join('roles', 'user_roles.role_id = roles.id', array('role_name' => 'name', 'role_label' => 'label', 'role_weight' => 'weight'), 'inner');
        $where = new \Zend\Db\Sql\Where();
        $where->addPredicate(
                new \Zend\Db\Sql\Predicate\Expression("(users." . $identitykey . " = '" . $identity . "')")
        );
        if (!empty($where)) {
            $select->where($where);
        }

//        echo "<pre>";
//        echo $select->getSqlString();
//        die();

        $sql = new Sql($adapter);
        $statement = $sql->getSqlStringForSqlObject($select);
        $resultSet = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        $resultSet->buffer();
        return $resultSet;
    }

    /**
     * Generate E-mail check code
     */
    private function getEmailCheckCode()
    {
        // return md5(date('Y-m-d H:i:s'));
        return mt_rand(10000000, 99999999);
    }

    /**
     * Generate Password
     * @param type $l
     * @param type $c
     * @param type $n
     * @param type $s
     * @return boolean
     */
    public function generatePassword($l = 8, $c = 0, $n = 0, $s = 0)
    {
        // get count of all required minimum special chars
        $count = $c + $n + $s;
        $out = "";
        // sanitize inputs; should be self-explanatory
        if (!is_int($l) || !is_int($c) || !is_int($n) || !is_int($s)) {
            trigger_error('Argument(s) not an integer', E_USER_WARNING);
            return false;
        } elseif ($l < 0 || $l > 20 || $c < 0 || $n < 0 || $s < 0) {
            trigger_error('Argument(s) out of range', E_USER_WARNING);
            return false;
        } elseif ($c > $l) {
            trigger_error('Number of password capitals required exceeds password length', E_USER_WARNING);
            return false;
        } elseif ($n > $l) {
            trigger_error('Number of password numerals exceeds password length', E_USER_WARNING);
            return false;
        } elseif ($s > $l) {
            trigger_error('Number of password capitals exceeds password length', E_USER_WARNING);
            return false;
        } elseif ($count > $l) {
            trigger_error('Number of password special characters exceeds specified password length', E_USER_WARNING);
            return false;
        }

        // all inputs clean, proceed to build password
        // change these strings if you want to include or exclude possible password characters
        $chars = "abcdefghijklmnopqrstuvwxyz";
        $caps = strtoupper($chars);
        $nums = "0123456789";
        $syms = "!@#$%^&*()-+?";

        // build the base password of all lower-case letters
        for ($i = 0; $i < $l; $i++) {
            $out .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }

        // create arrays if special character(s) required
        if ($count) {
            // split base password to array; create special chars array
            $tmp1 = str_split($out);
            $tmp2 = array();

            // add required special character(s) to second array
            for ($i = 0; $i < $c; $i++) {
                array_push($tmp2, substr($caps, mt_rand(0, strlen($caps) - 1), 1));
            }
            for ($i = 0; $i < $n; $i++) {
                array_push($tmp2, substr($nums, mt_rand(0, strlen($nums) - 1), 1));
            }
            for ($i = 0; $i < $s; $i++) {
                array_push($tmp2, substr($syms, mt_rand(0, strlen($syms) - 1), 1));
            }

            // hack off a chunk of the base password array that's as big as the special chars array
            $tmp1 = array_slice($tmp1, 0, $l - $count);
            // merge special character(s) array with base password array
            $tmp1 = array_merge($tmp1, $tmp2);
            // mix the characters up
            shuffle($tmp1);
            // convert to string for output
            $out = implode('', $tmp1);
        }

        return $out;
    }

    public function isExistEmail($email, $id = Null)
    {
        $adapter = $this->adapter;
        $select = new Select();
        $select->from($this->table);
        $select->where(array('email' => $email));
        if (!empty($id)) {
            $select->where("`id` != {$id}");
        }
        $sql = new Sql($adapter);
        $statement = $sql->getSqlStringForSqlObject($select);
        $resultSet = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        $resultSet->buffer();
        return $resultSet;
    }

    public function changeEmial($email, $id)
    {
        $data = array(
            'username' => $email,
            'email' => $email
        );
        if ($id > 0) {
            if ($this->getUsers($id)) {
                $rusult = $this->update($data, array('id' => $id));
            } else {
                throw new \Exception('Your id does not exist');
            }
        }

        return $rusult;
    }

    public function emailValidationCk($email)
    {
        if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i", $email)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
