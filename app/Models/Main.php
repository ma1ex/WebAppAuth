<?php

/**
 * Project: auth.local;
 * File: Main.php;
 * Developer: Matvienko Alexey (matvienko.alexey@gmail.com);
 * Date & Time: 26.11.2019, 0:04
 * Comment:
 */


namespace app\Models;

use ma1ex\Core\Model;

class Main extends Model {

    //
    //private $user;

    /**
     * @return array|bool|mixed
     */
    public function getData() {
        $user = new User();
        return $user->getAll();
    }
}