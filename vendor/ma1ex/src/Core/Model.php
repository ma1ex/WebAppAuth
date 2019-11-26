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
     * Injection DataBase Driver
     *
     * Model constructor.
     * @param Db $db
     */
    public function __construct(Db $db = null) {
        $this->db = !is_null($db) ?: new Db();
    }
}