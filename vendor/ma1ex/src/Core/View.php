<?php

/**
 * Project: auth.local;
 * File: View.php;
 * Developer: Matvienko Alexey (matvienko.alexey@gmail.com);
 * Date & Time: 24.11.2019, 22:48
 * Comment: View base class
 */


namespace ma1ex\Core;


class View {

    /**
     * Router parameters
     *
     * @var array
     */
    protected $routeParams;

    /**
     * Template vars
     *
     * @var array
     */
    protected $data = [];

    /**
     * Name or path to layout file
     *
     * @var string
     */
    protected $layout = APP_TPL_LAYOUTS_PATH . 'default.php';

    /**
     * Array templates
     *
     * @var array
     */
    protected $view = [];

    public function __construct(array $routeParams = []) {
        // Init base params
        $this->setParams($routeParams);
        $this->add([
            'headers' => '',
            'footers' => ''
        ]);
    }

    /**
     * Full path to layout file
     *
     * @param string $layout
     */
    public function setLayout(string $layout): void {
        $this->layout = APP_TPL_LAYOUTS_PATH . $layout  . '.php';
    }

    /**
     * Return path to layout
     *
     * @return string
     */
    public function getLayout(): string {
        return $this->layout;
    }

    /**
     * Set templates
     *
     * @param array $view
     */
    public function setView(array $view): void {
        if (is_array($view)) {
            $this->view = array_merge($this->view, $view);
        }
    }

    /**
     * Return path for view by name
     *
     * @param string $name
     * @return string
     */
    public function getView(string $name): string {
        if (array_key_exists($name, $this->view)) {
            return $this->view[$name];
        }
        return '';
    }

    /**
     * Add header includes: css, javascript
     *
     * @param string $incResource
     * @param string $type
     */
    public function addHeader(string $incResource, string $type = 'js'): void {
        static $resource = '';
        $resource .= $this->addResource($incResource, $type);
        $this->add(['headers' => $resource]);
    }

    /**
     * Add footer includes: css, javascript
     *
     * @param string $incResource
     * @param string $type
     */
    public function addFooter(string $incResource, string $type = 'js'): void {
        static $resource = '';
        $resource .= $this->addResource($incResource, $type);
        $this->add(['footers' => $resource]);
    }

    /**
     * Return HTML string by type name
     *
     * @param string $incResource
     * @param string $type
     * @return string
     */
    protected function addResource(string $incResource, string $type): string {
        $resource = '';

        switch ($type) {
            case 'css':
                $resource .= '<link rel="stylesheet" href="' . $incResource . '">' . "\r\n";
                break;
            case 'js':
                $resource .= '<script src="' . $incResource .'"></script>' . "\r\n";
                break;
            default:
                $resource .= $incResource . "\r\n";
        }

        return $resource;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params): void {
        if (is_array($params)) {
            $this->routeParams = $params;
        }
    }

    /**
     * Route params
     *
     * @return array
     */
    public function getParams(): array {
        return $this->routeParams;
    }

    /**
     * Template vars = ['key' => 'param']
     *
     * @param array $vars
     */
    public function add(array $vars) {
        if (is_array($vars)) {
            $this->data = array_merge($this->data, $vars);
        }
    }

    /**
     * Return all template vars
     *
     * @return array
     */
    public function getVars() {
        return $this->data;
    }

    /**
     * Output all view data: layout, template and template variables
     */
    public function render(): void {
        // Включение буферизации вывода
        ob_start();
        // Экспорт ключей массива для дальнейшего их использования по именам в шаблоне и лэйауте
        extract($this->getVars());
        /* Подключение файла шаблона, загрузка его в буфер и присвоение переменной
           $content для дальнейшего вывода в файле layout`а */
        foreach($this->view as $view => $path) {
            if (file_exists($file = APP_TPL_PATH . $path . '.php')) {
                require $file;
            } else {
                trigger_error('Template "' . $file . '" not found!', E_USER_ERROR);
            }
        }

        $content = ob_get_clean();
        /* Подключение файла layout`а и передача в него всех переменных для вывода
           контента */
        if (file_exists($this->layout)) {
            require $this->layout;
        } else {
            trigger_error('Layout "' . $this->layout . '" not found!', E_USER_ERROR);
        }
    }
}