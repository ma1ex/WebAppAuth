<?php

/**
 * Project: auth.local;
 * File: AuthController.php;
 * Developer: Matvienko Alexey (matvienko.alexey@gmail.com);
 * Date & Time: 25.11.2019, 0:07
 * Comment: Registration and authorization controller
 */


namespace app\Controllers\Auth;

use ma1ex\Core\Controller;
use ma1ex\Core\Router;
use app\Models\User;

class AuthController extends Controller {

    /**
     * @var User
     */
    private $user;

    public function __construct(array $params) {
        parent::__construct($params);

        $this->user = new User();

        $this->view->setView([
            'menu' => 'chanks' . DS . 'menu'
        ]);
        $this->view->add([
            'menu' => Router::buildMenu()
        ]);
    }

    /**
     * Page '/auth/register'
     */
    public function registerAction() {

        $this->view->addHeader(APP_HTTP_PATH . 'css/style.css', 'css');
        $this->view->addHeader(APP_HTTP_PATH . 'js/app.js');
        $this->view->addFooter(APP_HTTP_PATH . 'js/form.js');

        // Полный путь до подключаемого шаблона и перечень пеменных для вывода
        $this->view->setView([
            'auth' => 'auth' . DS . $this->params['action']
        ]);
        $this->view->add([
            'page_title' => 'Страница регистрации',
            'page_caption' => 'Введите данные для регистрации'
        ]);
        $this->view->render();
    }

    /**
     * Page '/auth/login'
     */
    public function loginAction() {
        // Полный путь до подключаемого шаблона и перечень пеменных для вывода
        $this->view->setView([
            'auth' => 'auth' . DS . $this->params['action']
        ]);
        $this->view->addHeader(APP_HTTP_PATH . 'css/style.css', 'css');
        $this->view->addFooter(APP_HTTP_PATH . 'js/form.js');
        $this->view->add([
            'page_title' => 'Страница входа',
            'page_caption' => 'Введите данные, чтобы войти'
        ]);
        $this->view->render();
    }

    /**
     * Logout user
     * Page '/auth/logout'
     */
    public function logoutAction() {
        $userName = (string) array_key_first($_SESSION['user']);
        if (isset($_SESSION['user']) && isset($_COOKIE['auth_local_autorize'])) {
            $this->user->setToken($userName, '0');
            setcookie('auth_local_autorize', '', time() - 3600, '/', 'auth.local');
            unset($_SESSION['user']);
            Router::redirect(APP_HTTP_PATH);
        }
    }

    /**
     * Add new user in DB
     *
     * @param array $data
     * @return bool
     */
    private function register(array $data): bool {

        $this->user->add([
            'login' => $data['login'],
            'password' => $data['password'],
            'email' => $data['email'],
            'name' => $data['name'],
            'token' => '0'
        ]);

        return true;
    }

    /**
     * Validator | Form data
     *
     * @param array $request
     * @param string $type
     * @return array
     */
    private function validate(array $request, string $type): array {
        $errors = [];
        // Если регистрируемся
        if ($type === 'register') {
            if (!isset($request['login']) || empty($request['login'])) {
                $errors[]['login'] = 'Псевдоним не указан!';
            } elseif ($this->isLoginAlreadyExists($request['login'])) {
                $errors[]['login'] = 'Такой логин уже используется!';
            }

            if (!isset($request['email']) || strlen($request['email']) == 0) {
                $errors[]['email'] = 'Email не указан!';
            } elseif (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[]['email'] = 'Неправильный формат email!';
            } elseif (strlen($request['email']) < 4) {
                $errors[]['email'] = 'Email должен быть больше 4х символов!';
            } elseif ($this->isEmailAlreadyExists($request['email'])) {
                $errors[]['email'] = 'Email уже используется!';
            }

            if (!isset($request['name']) || empty($request['name'])) {
                $errors[]['name'] = 'Имя не указано!';
            }

            if (!isset($request['password']) || empty($request['password'])) {
                $errors[]['password'] = 'Пароль не указан!';
            }
            if (!isset($request['confirm_password']) || empty($request['confirm_password'])) {
                $errors[]['confirm_password'] = 'Нужно повторить пароль!';
            } elseif ((isset($request['password']) && isset($request['confirm_password'])) && ($request['password'] !== $request['confirm_password'])) {
                $errors[]['confirm_password'] = 'Пароли не совпадают!';
            }
        }

        // Если авторизируемся
        if ($type === 'login') {
            if (!isset($request['login']) || empty($request['login'])) {
                $errors[]['login'] = 'Псевдоним должен быть указан!';
            }

            if (!isset($request['password']) || empty($request['password'])) {
                $errors[]['password'] = 'Пароль должен быть указан!';
            }
        }

        return $errors;
    }

    /**
     * Validator | Check unique email in DB
     *
     * @param string $email
     * @return bool
     */
    private function isEmailAlreadyExists(string $email): bool {
        $emails = $this->user->getAllEmails();

        if (!empty($emails)) {
            for ($i = 0; $i < sizeof($emails); $i++) {
                if ($emails[$i]->email === $email) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Validator | Check unique login in DB
     *
     * @param string $login
     * @return bool
     */
    private function isLoginAlreadyExists(string $login): bool {
        $logins = $this->user->getAllLogins();

        if (!empty($logins)) {
            for ($i = 0; $i < sizeof($logins); $i++) {
                if ($logins[$i]->login === $login) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Form action | Validate & add new user
     */
    public function addAction(): void {

        if (!empty($_POST)) {
            header('Content-Type: application/json');

            $errors = $this->validate($_POST, 'register');

            if (empty($errors)) {
                if ($this->register($_POST)) {
                    http_response_code(201);
                    echo json_encode(['success' => true]);
                    exit();
                }
                http_response_code(500);
                echo json_encode(['success' => false]);
                exit();
            }
            http_response_code(422);
            echo json_encode([
                'success' => false,
                'errors' => $errors
            ]);
            exit();
        }

        Router::errorCode(403);
    }

    /**
     * Form action | Validate & login user
     */
    public function signinAction() {

        if (!empty($_POST)) {
            header('Content-Type: application/json');

            $errors = $this->validate($_POST, 'login');

            if (empty($errors)) {
                if ($this->checkLogin($_POST)) {
                    http_response_code(201);
                    echo json_encode(['success' => true]);
                    exit();
                }
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'errors' => 'forbidden'
                ]);
                exit();
            }
            http_response_code(422);
            echo json_encode([
                'success' => false,
                'errors' => $errors
            ]);
            exit();
        }

        Router::errorCode(403);
    }

    /**
     * Validator | Check exist user in DB & set access right
     *
     * @param array $request
     * @return bool
     */
    private function checkLogin(array $request) {

        $user = $this->user->getUser('login', $request['login']);
        if ($user) {
            if (password_verify($request['password'], $user->password)) {
                $token = sha1($user->login);
                setcookie('auth_local_autorize', $token, 0, '/', 'auth.local');
                $_SESSION['user'][$user->login] = $token;
                $this->user->setToken($user->login, $token);
                return true;
            }
        }

        return false;
    }
}