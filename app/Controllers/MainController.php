<?php

/**
 * Project: auth.local;
 * File: MainController.php;
 * Developer: Matvienko Alexey (matvienko.alexey@gmail.com);
 * Date & Time: 24.11.2019, 22:54
 * Comment:
 */


namespace app\Controllers;

use app\Models\User;
use ma1ex\Core\Controller;
use ma1ex\Core\Db;
use ma1ex\Core\Router;

class MainController extends Controller {

    /**
     * @var User
     */
    private $user;

    // Параметры при вызове контроллера передаются из роутера
    public function __construct(array $params) {
        // Инициализация базовых параметров в родительском классе
        parent::__construct($params);

        // Имя контейнера шаблонов (layout).
        // Если не указать, будет использоваться по умолчанию - 'default'
        //$this->view->setLayout('default');

        // Инициализация модели для этого контроллера
        //$this->loadModel();
        $this->user = new User();

        // Построение главного меню
        $this->view->setView([
            'menu' => 'chanks' . DS . 'menu'
        ]);
        $this->view->add([
            'menu' => Router::buildMenu()
        ]);
    }

    public function indexAction() {
        $users = [];
        $all = $this->user->getAllWithoutPass();
        for ($i = 0; $i < sizeof($all); $i++) {
            $users[] = [
                'id' => $all[$i]->id,
                'login' => $all[$i]->login,
                'email' => $all[$i]->email,
                'name' => $all[$i]->name
            ];
        }

        // Имя подключаемого шаблона
        $this->view->setView([
            'main' => $this->params['action']
        ]);

        // Перечень пеменных для выводав шаблоне
        $this->view->addHeader('css/style.css', 'css');
        $this->view->addHeader('js/app.js');
        $this->view->add([
            'page_title' => 'Главная страница',
            'page_caption' => 'Hello, World! <br> I`m a Main page! <br><br>',
            'users' => $users
        ]);
        $this->view->render();
    }

    public function aboutAction() {
        $this->view->setView([
            'main' => $this->params['action']
        ]);
        $this->view->addHeader('css/style.css', 'css');
        $this->view->add([
            'page_title' => 'Об этом сайте',
            'page_caption' => 'Страница "About"'
        ]);
        $this->view->render();
    }
}