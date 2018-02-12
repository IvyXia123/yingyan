<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ActionAcl
 *
 * @author ZHAOPENG
 */
namespace eye\Plugins;

use Phalcon\Events\Event,
    Phalcon\Mvc\User\Plugin,
    Phalcon\Mvc\Dispatcher,
    Phalcon\Acl;
use Phalcon\Acl\Adapter\Memory as AclList;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;

class ActionAcl extends Plugin {

    public function __construct($dependencyInjector) {
        $this->_dependencyInjector = $dependencyInjector;
    }

    private function getAcl() {
        if (!isset($this->persistent->acl)) {
            
            $acl = new AclList();
            $acl->setDefaultAction(Acl::DENY);

            $roles = array(
                'guest' => new Role('guest'),
                'staff' => new Role('staff'),
                'super_staff' => new Role('super_staff'),
                'admin' => new Role('admin'),
            );
            foreach ($roles as $role) {
                $acl->addRole($role);
            }
            
            $loginResource = new Resource("login");
            $usersResource = new Resource("users");
            $pagesResource = new Resource("pages");
            $taskResource  = new Resource("task");
            //$getResource  = new Resource("get");
            
            
            $acl->addResource($loginResource, array('doLogin'));
            $acl->addResource($pagesResource, "*");
            $acl->addResource($usersResource, "*");
            $acl->addResource($taskResource, "*");
            $acl->addResource($taskResource, "scheduler");
            //$acl->addResource($getResource, "*");
            
            $acl->deny("guest", "*", "*");
            $acl->allow("guest", "login", "*");
            $acl->allow("guest", "task", "scheduler");
            
            $acl->allow("staff", "*", "*");
            $acl->deny("staff", "users", "*");
            
            $acl->allow("super_staff", "*", "*");
            $acl->deny("super_staff", "users", "*");
            
            $acl->allow("admin", "*", "*");
            

            $this->persistent->acl = $acl;
        }
        return $this->persistent->acl;
    }

    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher) {
        //xdebug_print_function_stack('join beforeDispatch');
        $role = trim($dispatcher->getDI()->get("cookies")->get('puser')->getValue());
        $user = trim($dispatcher->getDI()->get("cookies")->get('user')->getValue());
        //$role = str_replace("\0", "", $role);
        //echo $role;
        if (!$role) {
            $role = 'guest';
            $dispatcher->getDI()->get("cookies")->set('puser','guest');
        }else if($role != 'guest' && $role != 'staff' && $role != 'admin' && $role != 'super_staff'){
            $role = 'guest';
            $dispatcher->getDI()->get("cookies")->set('puser','guest');
        }
        
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        $acl = $this->getAcl();
        
        $allowed = $acl->isAllowed($role, $controller, $action);
        if ($allowed != Acl::ALLOW) {
            $dispatcher->forward(array(
                'controller' => 'login', 
                'action' => 'index'
            ));
            return false;
        }
    }

}
