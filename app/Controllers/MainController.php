<?php

/**
 * Project: auth.local;
 * File: MainController.php;
 * Developer: Matvienko Alexey (matvienko.alexey@gmail.com);
 * Date & Time: 24.11.2019, 22:54
 * Comment: Default controller
 */


namespace app\Controllers;

use app\Models\User;
use ma1ex\Core\Controller;
use ma1ex\Core\Router;

class MainController extends Controller {

    /**
     * @var User
     */
    private $user;

    private $loggedUser = 'Guest';

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

    /**
     * Start page
     */
    public function indexAction() {

        $users = [];
        $allUsers = $this->user->getAllWithoutPass();
        for ($i = 0; $i < sizeof($allUsers); $i++) {
            $users[] = [
                'id' => $allUsers[$i]->id,
                'login' => $allUsers[$i]->login,
                'email' => $allUsers[$i]->email,
                'name' => $allUsers[$i]->name
            ];
        }

        if (isset($_SESSION['user']) && isset($_COOKIE['auth_local_autorize'])) {
            $this->loggedUser = (string) array_key_first($_SESSION['user']);
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
            'page_caption' => 'This Main page',
            'users' => $users,
            'logged_user' => $this->loggedUser
        ]);
        $this->view->render();
    }

    /**
     * Test page - About
     */
    public function aboutAction() {
        $text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed 
        do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris 
        nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in 
        reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla 
        pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa 
        qui officia deserunt mollit anim id est laborum.';

        $this->view->setView([
            'main' => $this->params['action']
        ]);
        $this->view->addHeader('css/style.css', 'css');
        $this->view->add([
            'page_title' => 'Тестовая страница',
            'page_caption' => 'Страница "About"',
            'page_text' => $text
        ]);
        $this->view->render();
    }
}