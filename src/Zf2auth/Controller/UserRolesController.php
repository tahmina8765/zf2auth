<?php

namespace Zf2auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zf2auth\Entity\UserRoles;
use Zf2auth\Form\UserRolesForm;
use Zf2auth\Form\UserRolesSearchForm;
use Zend\Db\Sql\Select;

class UserRolesController extends Zf2authAppController
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
        $searchform = new UserRolesSearchForm();
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


        $where = new \Zend\Db\Sql\Where();
        $formdata = array();
        if (!empty($search_by)) {
            $formdata = (array) json_decode($search_by);
            if (!empty($formdata['user_id'])) {
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('user_id', '%' . $formdata['user_id'] . '%')
                );
            }
            if (!empty($formdata['role_id'])) {
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('role_id', '%' . $formdata['role_id'] . '%')
                );
            }
        }
        if (!empty($where)) {
            $select->where($where);
        }

        $paginator = $this->getUserRolesTable()->fetchAll($select, true);
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
            'pageAction' => 'user_roles/index',
            'form' => $searchform,
            'totalRecord' => $totalRecord,
            'currentPage' => $currentPage,
            'totalPage' => $totalPage
        ));
        return $this->vm;
    }

    /**
     * add Action
     * Insert row in 'user_roles'
     * @return type
     * Author: Tahmina Khatoon
     */
    public function addAction()
    {
        $form = new UserRolesForm();


        $request = $this->getRequest();
        if ($request->isPost()) {
            $user_roles = new UserRoles();
            $form->setInputFilter($user_roles->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $user_roles->exchangeArray($form->getData());
                $confirm = $this->getUserRolesTable()->saveUserRoles($user_roles);
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
                    // Redirect to list of user_roless
                    return $this->redirect()->toRoute('user_roles');
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
     * Edit row in 'user_roles'
     * @return type
     * Author: Tahmina Khatoon
     */
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('user_roles', array(
                        'action' => 'add'
            ));
        }
        $user_roles = $this->getUserRolesTable()->getUserRoles($id);

        $form = new UserRolesForm();
        $form->bind($user_roles);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($user_roles->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $confirm = $this->getUserRolesTable()->saveUserRoles($form->getData());

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
                    // Redirect to list of user_roless
                    return $this->redirect()->toRoute('user_roles');
                }
            }
        }
        $this->vm->setVariables(array(
            'flashMessages' => $this->flashMessenger()->getMessages(),
            'id' => $id,
            'form' => $form,
        ));

        return $this->vm;
    }

    /**
     * Delete Action
     * Delete row from 'user_roles'
     * @return type
     * Author: Tahmina Khatoon
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('user_roles');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {

            $id = (int) $request->getPost('id');
            $confirm = $this->getUserRolesTable()->deleteUserRoles($id);


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

            // Redirect to list of user_roless
            return $this->redirect()->toRoute('user_roles');
        }
        $this->vm->setVariables(array(
            'flashMessages' => $this->flashMessenger()->getMessages(),
            'id' => $id,
            'user_roles' => $this->getUserRolesTable()->getUserRoles($id)
        ));

        return $this->vm;
    }

}
