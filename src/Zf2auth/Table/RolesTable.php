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
use Zf2auth\Entity\Roles;

class RolesTable extends AbstractTableGateway
{

    protected $table = 'roles';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Roles());

        $this->initialize();
    }

    public function fetchAll(Select $select = null, $paginated = false)
    {
        $adapter = $this->adapter;
        if (null === $select) {
            $select = new Select();
            $select->order('order' . ' ' . 'ASC');
        }
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

    public function getRoles($id)
    {
        $id = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            return false;
        }
        return $row;
    }

    public function saveRoles(Roles $roles)
    {
        $result = array(
            'status' => 0,
            'message' => ''
        );
        $data = array(
            'name' => $roles->name,
            'label' => $roles->label,
            'weight' => $roles->weight,
            'order' => $roles->order,
        );

        $id = (int) $roles->id;
        
        if ($id == 0) {
            unset($data['modified_by']);
            unset($data['modified']);
            $result['status'] = $this->insert($data);
        } else {
            if ($this->getRoles($id)) {
                unset($data['created_by']);
                unset($data['created']);
                $result['status'] = $this->update($data, array('id' => $id));
            } else {
                $result['message'] = 'ID does not exist';
            }
        }

        return $result;
    }

    public function deleteRoles($id)
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

    public function dropdownRoles(Select $select = null, $displayEmpty = true)
    {
        if (null === $select)
            $select = new Select();
        $select->from($this->table);
        $select->order('order' . ' ' . 'ASC');

        $resultSet = $this->selectWith($select);

        $resultSet->buffer();

        $options = array();
        if ($displayEmpty) {
            $options[''] = '--- Please Select ---';
        }
        if (count($resultSet) > 0) {
            foreach ($resultSet as $row)
                $options[$row->getId()] = $row->getLabel() . ' - ' . $row->getName();
        }
        return $options;
    }

    public function truncate()
    {
        $adapter = $this->adapter;
        $statement = 'SET foreign_key_checks=0;TRUNCATE TABLE ' . $this->table.' ;SET foreign_key_checks=1;';
        $resultSet = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
    }

}
