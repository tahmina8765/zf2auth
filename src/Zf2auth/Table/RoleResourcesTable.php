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
use Zf2auth\Entity\RoleResources;

class RoleResourcesTable extends AbstractTableGateway
{

    protected $table = 'role_resources';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new RoleResources());

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

    public function getRoleResources($id)
    {
        $id = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            return false;
        }
        return $row;
    }

    public function getRoleResourcesByRoleIdAndResourceId($role_id, $resource_id)
    {
        $role_id = (int) $role_id;
        $resource_id = (int) $resource_id;
        $rowset = $this->select(array('role_id' => $role_id, 'resource_id' => $resource_id));
        $row = $rowset->current();
        if (!$row) {
            return false;
        }
        return $row;
    }

    public function saveRoleResources(RoleResources $role_resources)
    {
        $result = array(
            'status' => 0,
            'message' => ''
        );
        $data = array(
            'role_id' => $role_resources->role_id,
            'resource_id' => $role_resources->resource_id,
        );

        $id = (int) $role_resources->id;
        if (empty($id)) {
            $existdata = $this->getRoleResourcesByRoleIdAndResourceId($data['role_id'], $data['resource_id']);
            if (!empty($existdata)) {
                $id = $existdata->id;
            }
        }
        if ($id == 0) {
            unset($data['modified_by']);
            unset($data['modified']);
            $result['status'] = $this->insert($data);
        } else {
            if ($this->getRoleResources($id)) {
                unset($data['created_by']);
                unset($data['created']);
                $result['status'] = $this->update($data, array('id' => $id));
            } else {
                $result['message'] = 'ID does not exist';
            }
        }

        return $result;
    }

    public function deleteRoleResources($id)
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

    public function dropdownRoleResources(Select $select = null)
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

    public function getResourcesBasedOnRole($role_id)
    {
        $adapter = $this->adapter;
        $select = new Select();
        $select->from($this->table);

        $select->join('resources', 'resources.id = role_resources.resource_id', array('resource_name' => 'name'), 'left');
        $select->where('role_id =' . $role_id);
        // $resultSet = $this->selectWith($select);
//        echo $role_id;
//        echo $select->getSqlString();
//        die();
        $sql = new Sql($adapter);
        $statement = $sql->getSqlStringForSqlObject($select);
        $resultSet = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        $resultSet->buffer();


        return $resultSet;
    }

    public function truncate()
    {
        $adapter = $this->adapter;
        $statement = 'SET foreign_key_checks=0;TRUNCATE TABLE ' . $this->table.' ;SET foreign_key_checks=1;';
        $resultSet = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
    }

}
