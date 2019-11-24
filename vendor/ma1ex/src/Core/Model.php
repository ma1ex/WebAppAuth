<?php

/**
 * Project: auth.local;
 * File: Model.php;
 * Developer: Matvienko Alexey (matvienko.alexey@gmail.com);
 * Date & Time: 24.11.2019, 22:47
 * Comment:
 */


namespace ma1ex\Core;


abstract class Model {

    /**
     * @var Db
     */
    protected $db;

    /**
     * Model constructor.
     * @param Db $db : Injection DataBase Driver
     */
    public function __construct(Db $db) {
        $this->db = $db;
    }
}