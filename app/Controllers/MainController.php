<?php

/**
 * Project: auth.local;
 * File: MainController.php;
 * Developer: Matvienko Alexey (matvienko.alexey@gmail.com);
 * Date & Time: 24.11.2019, 22:54
 * Comment:
 */


namespace app\Controllers;

use ma1ex\Core\Controller;
use ma1ex\Core\Router;

class MainController extends Controller {

    // Параметры при вызове контроллера передаются из роутера
    public function __construct(array $params) {
        // Инициализация базовых параметров в родительском классе
        parent::__construct($params);

        // Имя контейнера шаблонов (layout).
        // Можно не указывать, тогда будет использоваться по умолчанию - 'default'
        //$this->view->setLayout('default');

        /* Инициализация модели
         * Если основная рабочая модель для этого контроллера именем схожа с именем
         * этого контроллера, то достаточно вызвать метод loadModel().
         * Или $this->model = $this->getModel(), если нужна сторонняя модель
         */
        //$this->model = $this->getModel('app\Models\\' . $this->params['controller']);
        //$this->loadModel();

        // Построение главного меню
        $this->view->add([
            'menu' => Router::buildMenu()
        ]);
    }

    public function indexAction() {
        // Имя подключаемого шаблона
        // Для метода index можно не указывать, т.к. по умолчанию - 'index'
        //$this->view->setView($this->params['action']);

        // Перечень пеменных для выводав шаблоне
        $this->view->addHeader('css/style.css', 'css');
        $this->view->addHeader('js/app.js');
        $this->view->add([
            'page_title' => 'Главная страница',
            'page_caption' => 'Hello, World! <br> I`m a Main page! <br><br>'
        ]);
        $this->view->render();

    }

    public function aboutAction() {
        $this->view->setView($this->params['action']);
        $this->view->addHeader('css/style.css', 'css');
        $this->view->add([
            'page_title' => 'Об этом сайте',
            'page_caption' => 'Страница "About"'
        ]);
        $this->view->render();
    }
}