<?php

/**
 * Project: auth.local;
 * File: View.php;
 * Developer: Matvienko Alexey (matvienko.alexey@gmail.com);
 * Date & Time: 24.11.2019, 22:48
 * Comment:
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
     * Name or path to template file
     *
     * @var string
     */
    protected $view = APP_TPL_PATH . 'index.php';

    public function __construct(array $routeParams = []) {
        // Init base params
        $this->setParams($routeParams);
        $this->add(['headers' => '']);
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
     * Set template
     *
     * @param string $view
     */
    public function setView(string $view): void {
        $this->view = APP_TPL_PATH . $view . '.php';
    }

    /**
     * Return path to view
     *
     * @return string
     */
    public function getView(): string {
        return $this->view;
    }

    /**
     * Add header includes: css, javascript
     *
     * @param string $incResource
     * @param string $type
     */
    public function addHeader(string $incResource, string $type = 'js'): void {
        static $resource = '';
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

        $this->add(['headers' => $resource]);
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
        if (file_exists($this->getView())) {
            require $this->getView();
        } else {
            trigger_error('Template "' . $this->getView() . '" not found!', E_USER_ERROR);
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