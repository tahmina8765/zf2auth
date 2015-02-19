<?php

namespace Zf2auth\Model;

use Zend\Authentication\Storage;
use Zf2auth\Table\RolesTable;

class Zf2AuthStorage extends Storage\Session
{

    public function setRememberMe($rememberMe = 0, $time = 1209600)
    {
        if ($rememberMe == 1) {
            $this->session->getManager()->rememberMe($time);
        }
    }

    public function forgetMe()
    {
        $this->session->getManager()->forgetMe();
    }

    public function getRole()
    {
        if (isset($_SESSION['zf2authSession'])) {
            foreach ($_SESSION['zf2authSession'] as $val) {
                $storage = $val;
            }
//            echo "<pre>";
//            print_r($storage);
//            echo "</pre>";
//            die();
            if (!empty($storage)) {
                return $storage['role_name'];
//                return 'Administrator';
            }
        }
        return 'Guest';
    }

    public function getRoleLabel()
    {
        if (isset($_SESSION['zf2authSession'])) {
            foreach ($_SESSION['zf2authSession'] as $val) {
                $storage = $val;
            }
            if (!empty($storage)) {
                return $storage['role_label'];
            }
        }
        return 'Guest';
    }

    public function getAllRoles()
    {
        $roleTable = new RolesTable();
        $roles = $roleTable->fetchAll();
//        echo "<pre>";
//        print_r($roles);
//        die();
        return $roles;
    }

}

?>