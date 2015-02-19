<?php

namespace Zf2auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zf2auth\Entity\RoleResources;
use Zf2auth\Form\RoleResourcesForm;
use Zf2auth\Form\RoleResourcesSearchForm;
use Zend\Db\Sql\Select;

class RoleResourcesController extends Zf2authAppController
{

    public $vm;

    function __construct()
    {
        parent::__construct();
        $this->vm = new viewModel();
    }

    /**
     * Search Action
     * Receive the POST value from 'Search Form' and returns the GET params to the index action.
     * So when index get the search params, it will always receive as GET and same format either comes from the search form or the pagination link.
     * Author: Tahmina Khatoon
     */
    public function searchAction()
    {

        $request = $this->getRequest();

        $url = 'index';

        if ($request->isPost()) {
            $formdata = (array) $request->getPost();
            $search_data = array();
            foreach ($formdata as $key => $value) {
                if ($key != 'submit') {
                    if (!empty($value)) {
                        $search_data[$key] = $value;
                    }
                }
            }
            if (!empty($search_data)) {
                $search_by = json_encode($search_data);
                $url .= '/search_by/' . $search_by;
            }
        }
        $this->redirect()->toUrl($url);
    }

    /**
     * index Action
     * Receive the search params
     * Build the search query
     * Generate the search result as a list
     * @return type
     * Author: Tahmina Khatoon
     */
    public function indexAction()
    {
        $searchform = new RoleResourcesSearchForm();
        $searchform->get('submit')->setValue('Search');

        $select = new Select();

        $order_by = $this->params()->fromRoute('order_by') ?
                $this->params()->fromRoute('order_by') : 'id';
        $order = $this->params()->fromRoute('order') ?
                $this->params()->fromRoute('order') : Select::ORDER_DESCENDING;

        $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
        $item_per_page = $this->params()->fromRoute('item_per_page') ? (int) $this->params()->fromRoute('item_per_page') : 10;
        $page_range = $this->params()->fromRoute('page_range') ? (int) $this->params()->fromRoute('page_range') : 7;

        $select->order($order_by . ' ' . $order);
        $search_by = $this->params()->fromRoute('search_by') ?
                $this->params()->fromRoute('search_by') : '';


        $select->join('roles', 'roles.id = role_resources.role_id', array('role_name' => 'name'), 'left');
        $select->join('resources', 'resources.id = role_resources.resource_id', array('resource_name' => 'name'), 'left');

        $where = new \Zend\Db\Sql\Where();
        $formdata = array();
        if (!empty($search_by)) {
            $formdata = (array) json_decode($search_by);
            if (!empty($formdata['role_id'])) {
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('role_id', '%' . $formdata['role_id'] . '%')
                );
            }
            if (!empty($formdata['resource_id'])) {
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('resource_id', '%' . $formdata['resource_id'] . '%')
                );
            }
        }
        if (!empty($where)) {
            $select->where($where);
        }

        $paginator = $this->getRoleResourcesTable()->fetchAll($select, true);
        $paginator->setCurrentPageNumber($page)
                ->setItemCountPerPage($item_per_page)
                ->setPageRange($page_range);

        $totalRecord = $paginator->getTotalItemCount();
        $currentPage = $paginator->getCurrentPageNumber();
        $totalPage = $paginator->count();

        $searchform->setData($formdata);
        $this->vm->setVariables(array(
            'flashMessages' => $this->flashMessenger()->getMessages(),
            'search_by' => $search_by,
            'order_by' => $order_by,
            'order' => $order,
            'page' => $page,
            'item_per_page' => $item_per_page,
            'paginator' => $paginator,
            'pageAction' => 'role_resources/index',
            'form' => $searchform,
            'totalRecord' => $totalRecord,
            'currentPage' => $currentPage,
            'totalPage' => $totalPage
        ));
        return $this->vm;
    }

    /**
     * add Action
     * Insert row in 'role_resources'
     * @return type
     * Author: Tahmina Khatoon
     */
    public function addAction()
    {
        $form = new RoleResourcesForm();

        /**
         * Set Roles List
         */
        $options = $this->getRolesTable()->dropdownRoles();
        $form->get('role_id')->setOptions(array('value_options' => $options));

        /**
         * Set Resource List
         */
        $options = $this->getResourcesTable()->dropdownResources();
        $form->get('resource_id')->setOptions(array('value_options' => $options));


        $request = $this->getRequest();
        if ($request->isPost()) {
            $role_resources = new RoleResources();
            $form->setInputFilter($role_resources->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $role_resources->exchangeArray($form->getData());
                $confirm = $this->getRoleResourcesTable()->saveRoleResources($role_resources);
                $redirect = false;
                if (!empty($confirm['status'])) {
                    switch ($confirm['status']) {
                        case '1':
                            $redirect = true;
                            $this->flashMessenger()->addMessage(array('success' => $this->message->success));
                            break;
                        default:
                            $this->flashMessenger()->addMessage(array('error' => $this->message->error));
                            break;
                    }
                }

                if ($redirect) {
                    // Redirect to list of role_resourcess
                    return $this->redirect()->toRoute('role_resources');
                }
            }
        }
        $this->vm->setVariables(array(
            'flashMessages' => $this->flashMessenger()->getMessages(),
            'form' => $form
        ));

        return $this->vm;
    }

    /**
     * Edit Action
     * Edit row in 'role_resources'
     * @return type
     * Author: Tahmina Khatoon
     */
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('role_resources', array(
                        'action' => 'add'
            ));
        }
        $role_resources = $this->getRoleResourcesTable()->getRoleResources($id);

        $form = new RoleResourcesForm();
        $form->bind($role_resources);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($role_resources->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $confirm = $this->getRoleResourcesTable()->saveRoleResources($form->getData());

                $redirect = false;
                if (!empty($confirm['status'])) {
                    switch ($confirm['status']) {
                        case '1':
                            $redirect = true;
                            $this->flashMessenger()->addMessage(array('success' => $this->message->success));
                            break;
                        default:
                            $this->flashMessenger()->addMessage(array('error' => $this->message->error));
                            break;
                    }
                }

                if ($redirect) {
                    // Redirect to list of role_resourcess
                    return $this->redirect()->toRoute('role_resources');
                }
            }
        }

        /**
         * Set Roles List
         */
        $options = $this->getRolesTable()->dropdownRoles();
        $form->get('role_id')->setOptions(array('value_options' => $options));

        /**
         * Set Resource List
         */
        $options = $this->getResourcesTable()->dropdownResources();
        $form->get('resource_id')->setOptions(array('value_options' => $options));

        $this->vm->setVariables(array(
            'flashMessages' => $this->flashMessenger()->getMessages(),
            'id' => $id,
            'form' => $form,
        ));

        return $this->vm;
    }

    /**
     * Delete Action
     * Delete row from 'role_resources'
     * @return type
     * Author: Tahmina Khatoon
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('role_resources');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {

            $id = (int) $request->getPost('id');
            $confirm = $this->getRoleResourcesTable()->deleteRoleResources($id);


            if (!empty($confirm['status'])) {
                switch ($confirm['status']) {
                    case '1':
                        $this->flashMessenger()->addMessage(array('success' => $this->message->success));
                        break;
                    default:
                        $this->flashMessenger()->addMessage(array('error' => $this->message->error));
                        break;
                }
            }

            // Redirect to list of role_resourcess
            return $this->redirect()->toRoute('role_resources');
        }
        $this->vm->setVariables(array(
            'flashMessages' => $this->flashMessenger()->getMessages(),
            'id' => $id,
            'role_resources' => $this->getRoleResourcesTable()->getRoleResources($id)
        ));

        return $this->vm;
    }

}
