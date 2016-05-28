<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Network;

/**
 * Route definition
 */
class Route implements RouteInterface
{

    /**
     * Regex of some common parts in URI
     */
    const YEAR = '[12][0-9]{3}';
    const MONTH = '0[1-9]|1[012]';
    const DAY = '0[1-9]|[12][0-9]|3[01]';
    const ID = '[0-9]+';
    const MONGOID = '[0-9a-f]{24}';
    const SLUG = '[a-z0-9-_]+';
    const BASE64 = '[a-zA-Z0-9=]+';
    const YEARMONTH = '[12][0-9]{3}-(0[1-9]|1[012])';
    const YEARWEEK = '[12][0-9]{3}-w(0[1-9]|[1-4][0-9]|5[0-2])';
    const DATE = '[12][0-9]{3}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])';
    const PATH = '[^\\?%*:|"<>\.]+';

    /**
     * URI pattern
     *
     * @var string
     */
    protected $pattern = '';

    /**
     * URI regex
     *
     * @var string
     */
    protected $regex = '';

    /**
     * Controller
     *
     * @var string
     */
    protected $controller = '';

    /**
     * Action
     *
     * @var string
     */
    protected $action = '';

    /**
     * Params
     *
     * @var array
     */
    protected $params = array();

    /**
     * Params in pattern
     *
     * @var array
     */
    protected $paramsInPattern = array();

    /**
     * Redirect
     *
     * @var string
     */
    protected $redirect = '';

    /**
     * Output
     *
     * @var string
     */
    protected $output = 'auto';

    /**
     * Data types
     *
     * @var array
     */
    protected $dataTypes = array(
        'year' => self::YEAR,
        'month' => self::MONTH,
        'day' => self::DAY,
        'id' => self::ID,
        'mongoid' => self::MONGOID,
        'slug' => self::SLUG,
        'base64' => self::BASE64,
        'monthyear' => self::YEARMONTH,
        'weekyear' => self::YEARWEEK,
        'date' => self::DATE,
        'path' => self::PATH
    );

    /**
     * Construct
     *
     * @param array $options
     */
    public function __construct($options = null)
    {
        if (!is_array($options)) {
            return;
        }

        $properties = array('pattern', 'controller', 'action', 'params', 'redirect', 'output');

        foreach ($properties as $property) {
            if (isset($options[$property])) {
                $method = 'set' . ucfirst($property);
                $this->$method($options[$property]);
            }
        }
    }

    /**
     * Set URI pattern
     *
     * @param string $pattern
     *
     * @return \Vhmis\Network\Route
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;

        // Escape các ký tự đặt biệt
        $special = array('.');
        $escape = array('\\.');
        $this->pattern = str_replace($special, $escape, $this->pattern);

        $this->patternToRegex();

        return $this;
    }

    /**
     * Set controller
     *
     * @param string $controller
     *
     * @return \Vhmis\Network\Route
     */
    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * Set action
     *
     * @param string $action
     *
     * @return \Vhmis\Network\Route
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Set params
     *
     * @param array $params
     *
     * @return \Vhmis\Network\Route
     */
    public function setParams($params)
    {
        if (is_array($params)) {
            foreach ($params as $key => $value) {
                $this->params[$key] = $value;
            }
        }

        return $this;
    }

    /**
     * Set redirect
     *
     * @param string $redirect
     *
     * @return \Vhmis\Network\Route
     */
    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;

        return $this;
    }

    /**
     * Set output
     *
     * @param string $output
     *
     * @return \Vhmis\Network\Route
     */
    public function setOutput($output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * Clear route properties
     *
     * @return \Vhmis\Network\Route
     */
    public function clear()
    {
        $this->pattern = '';
        $this->regex = '';
        $this->controller = '';
        $this->action = '';
        $this->output = 'html';
        $this->redirect = '';
        $this->params = array();
        $this->paramsInPattern = array();

        return $this;
    }

    /**
     * Convert pattern to regex format
     */
    public function patternToRegex()
    {
        // Find all params in patterns
        // Param [paramType:paramName]
        $match = preg_match_all('/\[(.*?)\]/', $this->pattern, $params);

        // Regex and name of params
        $regexParams = array();
        $nameParams = array();

        if ($match >= 1) {
            foreach ($params[1] as $value) {
                $value = explode(':', $value, 2);
                if (count($value) == 2) {
                    if (isset($this->dataTypes[$value[0]])) {
                        $regexParams[] = '(?<' . $value[1] . '>' . $this->dataTypes[$value[0]] . ')';
                    } else {
                        $regexParams[] = '(?<' . $value[1] . '>' . $this->dataTypes['slug'] . ')';
                    }

                    $nameParams[] = $value[1];
                }
            }
        }

        // Chuyển link pattern sang link regex
        $this->regex = str_replace('/', '\\/', $this->pattern);
        $this->regex = '/' . 'so324pecial' . str_replace($params[0], $regexParams, $this->regex) . 'so324pecial' . '/';
        $this->paramsInPattern = $nameParams;
    }

    /**
     * Check valid of URI
     *
     * @param string $value
     *
     * @return array
     */
    public function check($value)
    {
        $result = array(
            'match' => false
        );

        if (!is_string($value)) {
            return $result;
        }

        $match = preg_match_all($this->regex, 'so324pecial' . $value . 'so324pecial', $params, PREG_SET_ORDER);

        // Only match one
        if ($match !== 1) {
            return $result;
        }

        // Matched
        $result['match'] = true;
        $result['controller'] = $this->controller;
        $result['output'] = $this->output;
        $result['action'] = $this->action;
        $result['params'] = $this->params;

        // Add value of paramsInPattern to params
        foreach ($this->paramsInPattern as $name) {
            $result['params'][$name] = $params[0][$name];
        }

        // Redirect
        $result['redirect'] = $this->redirect === '' ? '' : $this->makeRedirect($result['params']);

        return $result;
    }

    /**
     * Get full redirect
     *
     * @param array $params
     *
     * @return string
     */
    public function makeRedirect($params)
    {
        $redirect = '';

        foreach ($params as $name => $value) {
            $redirect = str_replace('[' . $name . ']', $value, $this->redirect);
        }

        return $redirect;
    }
}
