<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Controller;

use \Vhmis\Network;
use \Vhmis\Di\ServiceManager;
use \Vhmis\View\View;

/**
 * Controller
 */
class Controller implements \Vhmis\Di\ServiceManagerAwareInterface
{
    /**
     * App info and request info
     *
     * @var array
     */
    public $appInfo;

    /**
     * App name
     *
     * @var string
     */
    public $app;

    /**
     * App url
     *
     * @var string
     */
    public $appUrl;

    /**
     * Controller
     *
     * @var string
     */
    public $controller;

    /**
     * Action
     *
     * @var string
     */
    public $action;

    /**
     * Params
     *
     * @var array
     */
    public $params;

    /**
     * Layout
     *
     * @var string
     */
    public $layout = 'Default';

    /**
     * Template
     *
     * @var string
     */
    public $template = 'Default';

    /**
     * Output
     *
     * @var string
     */
    public $output;

    /**
     * Container
     *
     * @var \Vhmis\Di\ServiceManager
     */
    public $sm;

    /**
     * View
     *
     * @var \Vhmis\View\View
     */
    public $view;

    /**
     * @var \Vhmis\Network\Response
     */
    public $response;

    /**
     * @var \Vhmis\Network\Request
     */
    public $request;

    /**
     * Construct
     *
     * @param \Vhmis\Network\Request  $request
     * @param \Vhmis\Network\Response $response
     */
    public function __construct(Network\Request $request = null, Network\Response $response = null)
    {
        $this->request = $request != null ? $request : new Network\Request();
        $this->response = $response != null ? $response : new Network\Response();
        $this->view = new View;

        $this->appInfo = $request->app;
        $this->app = $this->appInfo['app'];
        $this->appUrl = $this->appInfo['appUrl'];

        $this->action = $this->appInfo['action'];
        $this->params = $this->appInfo['params'];
        $this->output = $this->findOutputType($this->appInfo['output']);
        $this->controller = $this->appInfo['controller'];
    }

    /**
     * Set service manager
     *
     * @param \Vhmis\Di\ServiceManager $sm
     */
    public function setServiceManager(ServiceManager $sm)
    {
        $this->sm = $sm;
    }

    /**
     * Do request
     */
    public function init()
    {
        $template = isset($this->template) ? $this->template : 'Default';
        $layout = isset($this->layout) ? $this->layout : 'Default';

        $this->view->setTemplate($template)->setLayout($layout)->setAppUrl($this->appUrl)->setOutput($this->output);
        $this->view->setApp($this->app)->setController($this->controller)->setView($this->action);

        $this->beforeInit();

        $action = 'action' . $this->action;

        if (method_exists($this, $action)) {
            $this->$action();
        } else {
            echo 'Not found ' . $this->action . ' action. Create new method : ' . $action;
            exit();
        }

        $content = $this->view->render();

        $this->response->body($content)->response();

        $this->afterInit();
    }

    public function beforeInit()
    {}

    public function afterInit()
    {
        exit();
    }

    /**
     * Find output type
     *
     * html|json|xml|text
     *
     * @param string $output
     *
     * @return string
     */
    protected function findOutputType($output)
    {
        if ($output === 'auto') {
            if ($this->request->isAjaxRequest()) {
                $output = $this->request->findAjaxReponseContentType();
            } else {
                $output = 'html';
            }
        }

        return $output;
    }

    /**
     * Get model of current app
     *
     * @param string $model
     *
     * @return \Vhmis\Db\ModelInterface
     *
     * @throws \Exception
     */
    protected function model($model)
    {
        return $this->appModel($this->appInfo['app'], $model);
    }

    /**
     * Get model of app
     *
     * @param string $app
     * @param string $model
     *
     * @return \Vhmis\Db\ModelInterface
     *
     * @throws \Exception
     */
    protected function appModel($app, $model)
    {
        $fullname = $app . '\Model\\' . $model;

        $model = $this->sm->getModel($fullname);

        if ($model === null) {
            throw new \Exception('Model ' . $fullname . 'not found');
        }

        return $model;
    }

    /**
     * Set data for view
     *
     * @param string $key
     * @param mixed  $data
     *
     * @return \Vhmis\Controller\Controller
     */
    public function set($key, $data)
    {
        $this->view->setData($key, $data);

        return $this;
    }

    /**
     * Gọi view
     *
     * @param mixed  $data
     * @param string $view
     * @param string $layout
     * @param string $template
     */
    public function end($data, $view = '', $layout = '', $template = '')
    {
        if ($view !== '') {
            $this->view->setView($view);
        }

        if ($layout !== '') {
            $this->view->setLayout($layout);
        }

        if ($template !== '') {
            $this->view->setTemplate($template);
        }

        $content = $this->view->render($data);

        $this->response->body($content)->response();

        $this->afterInit();
    }

    /**
     * Display message
     *
     * @param array  $data
     * @param string $layout
     */
    public function message($data, $layout = '')
    {
        if ($layout == '') {
            $layout = 'Message';
        }

        $this->view->setNoView();

        $content = $this->view->setLayout($layout)->render($data);

        $this->response->body($content)->response();

        $this->afterInit();
    }

    /**
     * Display error
     *
     * @param array  $data
     * @param string $layout
     */
    public function error($data, $layout = 'Error')
    {
        $this->message($data, $layout);
    }
}
