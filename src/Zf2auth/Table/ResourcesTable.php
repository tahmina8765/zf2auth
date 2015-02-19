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
use Zf2auth\Entity\Resources;
use Zend\Db\Sql\Predicate\Expression;

class ResourcesTable extends AbstractTableGateway
{

    protected $table = 'resources';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Resources());

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

    public function getResources($id)
    {
        $id = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            return false;
        }
        return $row;
    }

    public function saveResources(Resources $resources)
    {
        $result = array(
            'status' => 0,
            'message' => ''
        );
        $data = array(
            'name' => $resources->name,
            'title' => $resources->title,
        );

        $id = (int) $resources->id;
        if ($id == 0) {
            unset($data['modified_by']);
            unset($data['modified']);
            $result['status'] = $this->insert($data);
        } else {
            if ($this->getResources($id)) {
                unset($data['created_by']);
                unset($data['created']);
                $result['status'] = $this->update($data, array('id' => $id));
            } else {
                $result['message'] = 'ID does not exist';
            }
        }

        return $result;
    }

    public function deleteResources($id)
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

    public function dropdownResources(Select $select = null)
    {
        if (null === $select)
            $select = new Select();
        $select->from($this->table);
        $resultSet = $this->selectWith($select);
        $resultSet->buffer();

        $options = array();
        $options[''] = '--- Please Select ---';
        if (count($resultSet) > 0) {
            foreach ($resultSet as $row) {
                $title = $row->getTitle();
                $options[$row->getId()] = empty($title) ? $row->getName() : $row->getTitle();
            }
        }
        return $options;
    }

    public function getPrimaryResources()
    {
        $primaryResources = array(
            'home',
            'users/login',
            'users/authenticate',
            'users/forgot-password',
        );
        $adapter = $this->adapter;
        $select = new Select();
        $select->from($this->table);
        $select->where->in('name', $primaryResources);
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
