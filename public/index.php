<?php

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use eye\Plugins\ActionAcl,
    Phalcon\Mvc\Dispatcher,
    Phalcon\Crypt,
    Phalcon\Mvc\Router;
use eye\Utils\ResultMessage;

try {
    putenv('LC_ALL=en_US.UTF-8');
    //date_default_timezone_set('PRC');
    $loader = new Loader();
    $loader->registerNamespaces(
            array(
                'eye\Utils' => '../app/utils/',
                'eye\Plugins' => '../app/plugins/',
                'eye\Controllers' => '../app/controllers/',
            )
    );
    $loader->registerDirs(array(
        '../app/controllers/',
        '../app/models/',
        '../app/conf/',
        '../app/utils/',
    ));
    $loader->register();

    $di = new FactoryDefault();

    $di->set('view', function () {
        $view = new View();
        $view->setViewsDir('../app/views/');
        return $view;
    });

    $di->set('dispatcher', function() use ($di) {
        $dispatcher = new Dispatcher();
        $dispatcher->setDefaultNamespace("eye\Controllers");

        $eventsManager = $di->getShared('eventsManager');
        $actionAcl = new ActionAcl($di);
        $eventsManager->attach('dispatch', $actionAcl);

        $dispatcher->setEventsManager($eventsManager);

        return $dispatcher;
    });

    $di->set('router', function () {

        $router = new Router();
        $router->add("/pages/get/{page}", array(
            'controller' => 'pages',
            'action' => 'get',
            'page' => 1
        ));
        $router->add("/reports", array(
            'controller' => 'reports',
            'action' => 'index'
        ));
        return $router;
    });

    $di->set('crypt', function() {
        $crypt = new Crypt();
        $crypt->setKey('zhaopeng12345678');
        return $crypt;
    });
    
    $di->set('rm', function() {
        return new ResultMessage();
    });

    $application = new Application($di);

    echo $application->handle()->getContent();
} catch (\Exception $e) {
    echo "Exception: ", $e->getMessage();
}
