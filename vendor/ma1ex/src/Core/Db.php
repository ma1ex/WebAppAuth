<?php

/**
 * Project: auth.local;
 * File: Db.php;
 * Developer: Matvienko Alexey (matvienko.alexey@gmail.com);
 * Date & Time: 23.11.2019, 2:08
 * Comment: XML DataBase Driver, Query builder
 */


namespace ma1ex\Core;


class Db {

    private static $instance = [];

    /**
     * Кэш запросов
     *
     * @var bool
     */
    public  $cache = true;

    /**
     * Абсолютный путь рабочей директории для БД
     *
     * @var string|null
     */
    private $dir = null;

    /**
     * Файл с БД (.xml) и полный путь к нему
     *
     * @var string|null
     */
    private $db = null;

    /**
     * Дескриптор '.lock' файла
     *
     * @var bool|resource|null
     */
    private $fh = null;

    /**
     * Файл '.lock' и полный путь к нему
     *
     * @var string|null
     */
    private $lock = null;

    /**
     * Экземпляр SimpleXMLElement с БД
     *
     * @var \SimpleXMLElement|null
     */
    private $xml = null;

    /**
     * Таблицы, колонки, запросы, агрегаторы и т.д. ...
     */
    private $table = null;
    private $query = null;
    private $bind = [];
    private $columns = [];
    private $sort = [];
    private $join_table = null;
    private $primary_key = null;
    private $foreign_key = null;
    private $limit = 0;
    private $affected_rows = 0;

    /**
     * Db constructor.
     * @param string $filePath
     */
    public function __construct(string $filePath = '') {
    //public function __construct(string $filePath) {

        $filePath = !empty($filePath) ? $filePath : DB_XML;

        $this->dir = realpath(dirname($filePath)) . DIRECTORY_SEPARATOR;
        $file = basename($filePath, '.xml');
        // Монопольный доступ
        $this->lock = $this->dir . $file . '.lock';

        // Если файл существует, значит его используют, ждем...
        while (true == file_exists($this->lock)) {
            usleep(10);
        }

        // Создание файла БД
        while (!$this->fh = @fopen($this->lock, 'w')) {
            usleep(10);
        }

        // Ожидание разблокировки файла
        while (!flock($this->fh, LOCK_EX)) {
            usleep(10);
        }

        // По окончанию работы убираем блокировки файлов
        register_shutdown_function([$this, '__unlock']);

        // Файл БД
        $this->db = $this->dir . $file . '.xml';

        if (!file_exists($this->db)) {
            // Если файл базы не существует, создаем его с базовой разметкой
            file_put_contents($this->db, '<?xml version="1.0" encoding="utf-8"?><database></database>');
            // Установка прав на файл для *NIX систем
            self::chmod($filePath);
        }

        // Чтение XML`я
        try {
            libxml_use_internal_errors(true);
            $this->xml = new \SimpleXMLElement(file_get_contents($this->db));
        } catch(Exception $e) {
            exit('Error: ' . $e->getMessage());
        }
    }

    /**
     * Разблокировка и удаление файла '.lock'
     */
    public function __unlock(): void {
        flock($this->fh, LOCK_UN);
        fclose($this->fh);
        @unlink($this->lock);
    }

    /**
     * @param string $database
     * @param int $permissions
     * @return bool
     */
    public static function chmod(string $database, int $permissions = 0644): bool {
        if (file_exists($database)) {
            return chmod($database, $permissions);
        }

        return false;
    }

    /**
     * @param $database
     * @return mixed
     */
    public static function connect(string $database) {
        if (!isset(static::$instance[$database])) {
            static::$instance[$database] = new self($database);
        }

        return static::$instance[$database];
    }

    /**
     * @param $dbPath
     * @return bool
     */
    public static function dropDatabase($dbPath): bool {
        if (file_exists($file = $dbPath)) {

            static::clearCache(realpath(dirname($dbPath)));

            return unlink($file);
        }

        return false;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function addTable(string $name) {
        $table = $this->xml->xpath($name);

        // Проверка на остаточные DOM-хвосты после удаления крайней записи
        if (isset($table) && empty($table[0])) {
            $this->removeTable($name);
            $table = '';

        }

        if (empty($table)) {
            $this->xml->addChild($name)->addChild('row');
            return $this->store();
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public function removeTable(string $name) {
        foreach ($this->xml->xpath('//database/' . $name) as $row) {
            $node = dom_import_simplexml($row);
            $node->parentNode->removeChild($node);
        }

        return $this->store();
    }

    /**
     * @return array
     */
    public function getTables(): array {
        $rows = $this->xml->xpath('//database');

        if (empty($rows)) {
            return [];
        }

        $tables = [];

        foreach ($rows[0] as $table) {
            $tables[] = $table->getName();
        }

        return $tables;
    }

    /**
     * @param $table
     * @return $this
     */
    public function from(string $table) {
        $this->table = trim($table);

        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @return mixed
     */
    public function addColumn(string $name, string  $value = '') {
        if (!is_null($this->table)) {

            foreach ($this->xml->xpath('//database/' . $this->table . '/row') as $row) {
                if (isset($row->$name)) continue;
                $row->addChild($name, $value);
            }

            static::clearCache($this->dir);

            return $this->store();

        } else {
            trigger_error('Нельзя добавить столбец, пока не выбрана таблица! 
                    Используйте метод "from" перед этой командой.');
        }
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function removeColumn(string $name) {
        $table = $this->table;

        foreach($this->xml->$table as $row) {
            foreach($row as $i => $column) {
                unset($column->$name);
            }
        }

        return $this->store();
    }

    /**
     * @param string|null $table
     * @return array
     */
    public function getColumns(string $table = null) {

        $table = is_null($table) ? $this->table : $table;

        $rows = $this->xml->xpath('//database/' . $table . '/row[position()=1]');

        if (empty($rows)) {
            return [];
        }

        $columns = [];

        foreach($rows[0] as $column) {
            $columns[] = $column->getName();
        }

        return $columns;
    }

    /**
     * @param string $table
     * @param string $primary_key
     * @param string $foreign_key
     * @return $this
     */
    public function join(string $table, string $primary_key, string $foreign_key) {
        $this->join_table  = $table;
        $this->primary_key = $primary_key;
        $this->foreign_key = $foreign_key;

        array_push($this->columns, $primary_key);

        return $this;
    }

    /**
     * Выборка определенных столбцов (через запятую) таблицы.
     * Без аргументов выбираются все столбцы из указанной таблицы.
     *
     * @param $select
     * @return $this
     */
    public function select($select = '*') {
        $select = trim($select);

        if ($select != '*') {
            if (strpos($select, ',')) {
                $columns = explode(',', $select);
                $columns = array_map('trim', $columns);
            } else {
                $columns[] = $select;
            }
        } else {
            $columns = $this->getColumns();

            if ($this->join_table) {
                $columns = array_unique(array_merge($columns, $this->getColumns($this->join_table)));
            }
        }

        $this->columns = $columns;

        return $this;
    }

    /**
     * @param string $column
     * @param string $value
     * @param string $comparison_operator
     * @param string $logical_operator
     * @return $this
     */
    public function where(string $column, string $value,
                          string $comparison_operator = '=',
                          string $logical_operator = 'and'
    ) {
        switch (strtolower($comparison_operator)) {

            case 'contains':
                $comparison_operator = is_null($this->query) ? '' : $logical_operator;
                $this->query .= sprintf('%s contains(%s, "%s")', $comparison_operator, $column, $value);
                break;

            default:

                if(!is_null($this->query)) {
                    $column = " {$logical_operator} " . $column;
                }

                $this->query .= sprintf('%s %s "%s"', $column, $comparison_operator, $value);

        }

        return $this;
    }

    /**
     * @param string $column
     * @param string $value
     * @param string $comparison_operator
     * @return Db
     */
    public function or(string $column, string $value, string $comparison_operator = '=') {
        if (is_null($this->query)) {
            trigger_error('Пропущен опрератор "Where"!');
        }

        return $this->where($column, $value, $comparison_operator, ' or');
    }

    /**
     * @return array|bool|mixed
     */
    public function first() {
        return $this->get(1);
    }

    /**
     * @return array|bool|mixed
     */
    public function all() {
        return $this->get();
    }

    /**
     *
     *
     * @param int $results
     * @return array|bool|mixed
     */
    private function get(int $results = 0) {
        $data = $columns = [];

        // Проверка кэша
        if (file_exists($cache_file = $this->dir . md5($this->table . $this->join_table . $this->query . $this->limit) . '.cache') && $this->cache) {
            // и чтение
            $data = unserialize(file_get_contents($cache_file));
        } else {
            // Если конкретные столбцы не заданы, выбираем все из таблицы
            if (empty($this->columns)) {
                $this->columns = $this->getColumns($this->table);
            }

            // Если Join не пустой, соединяем
            if (!is_null($this->join_table)) {
                $jtable_columns = $this->getColumns($this->join_table);
            }

            foreach ($this->xml->xpath('//database/' . $this->table . '/row' . $this->getQuery()) as $i => $row) {
                foreach ($row->children() as $column) {
                    if (in_array($column->getName(), $this->columns)) {

                        // Join
                        if (!is_null($this->join_table)) {
                            if ($column->getName() == $this->primary_key) {
                                $jtable = $this->xml->xpath('//database/' . $this->join_table . '/row[' . $this->foreign_key . ' = ' . (string) $column . ']');

                                if (!empty($jtable)) {

                                    foreach ($jtable[0] as $jcolumn) {
                                        if (in_array($jcolumn->getName(), $this->columns)) {
                                            if ($jcolumn->getName() == 'id') {
                                                continue;
                                            }
                                            $columns[$jcolumn->getName()] = (string) $jcolumn;
                                        }
                                    }

                                } else {
                                    // Заполнение пустых значений
                                    foreach ($jtable_columns as $name) {
                                        if (in_array($name, $this->columns)) {
                                            if ($name == 'id') {
                                                continue;
                                            }
                                            $columns[$name] = '';
                                        }
                                    }
                                }
                            }
                        }

                        $columns[$column->getName()] = (string) $column;
                    }
                }

                $data[] = (object) $columns;

                // Проверка на кол-во возвращаемых значений
                if (++$i == $this->limit) {
                    break;
                }
            }

            // Кэш результата запроса, если включен
            if ($this->cache) {
                file_put_contents($cache_file, serialize($data));
            }
        }

        // Сортировка массива
        if (!empty($this->sort)) {
            $data = $this->sortArray($data, $this->sort[0], $this->sort[1]);
        }

        // Вернуть false, если результат выборки пустой
        if (count($data) === 0) {
            return false;
        }

        // Если аргумент === 1, вернуть только один результат...
        if ($results === 1) {
            return $data[0];
        }

        $this->clear();

        // ... или все
        return $data;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function update(array $data = []) {
        if (!empty($this->bind)) {
            $data = array_merge($this->bind, $data);
        }

        foreach ($this->xml->xpath('//database/' . $this->table . '/row' . $this->getQuery()) as $i => $row) {

            foreach ($row->children() as $column) {

                if (array_key_exists($column->getName(), $data)) {
                    $dom = dom_import_simplexml($column);
                    $dom->nodeValue = $data[$column->getName()];
                }
            }

            if (++$i == $this->limit) {
                break;
            }

            $this->affected_rows++;
        }

        return $this->clear()->store();
    }

    /**
     * @return mixed
     */
    public function delete() {

        foreach ($this->xml->xpath('//database/' . $this->table . '/row' . $this->getQuery()) as $i => $row) {

            $node = dom_import_simplexml($row);
            $node->parentNode->removeChild($node);

            if (++$i == $this->limit) {
                break;
            }

            $this->affected_rows++;
        }

        return $this->clear()->store();
    }

    /**
     * @return string
     */
    public function getQuery(): string {
        return is_null($this->query) ? '' : '[' . $this->query . ']';
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function bind(string $name, string $value) {
        if (strpos($this->query, ':' . $name)) {
            $this->query = str_replace(':' . $name, '"' . $value . '"', $this->query);
        } else {
            $this->bind[$name] = $value;
        }

        return $this;
    }

    /**
     * @param array $data
     * @return $this|mixed
     */
    public function insert(array $data = []) {

        if (!empty($this->bind)) {
            $data = array_merge($data, $this->bind);
        }

        $columns = $this->getColumns();

        // При первой вставке
        if (empty($columns)) {

            // Автовставка id, если не указано явно
            if (!array_key_exists('id', $data)) {
                $this->addColumn('id', 1);
            }

            foreach ($data as $name => $value) {
                $this->addColumn($name, $value);
            }

            return $this;
        }

        $row = $this->addRow();

        foreach ($columns as $column) {

            if ($column == 'id') {
                $row->addChild('id', $this->lastId() + 1); continue;
            }

            $value = isset($data[$column]) ? $data[$column] : '';
            $row->addChild($column, $value);

        }

        return $this->clear()->store();

    }

    /**
     * @param string $column
     * @param string $order
     * @return $this
     */
    public function orderBy(string $column, string $order = 'asc') {
        $direction = (strtolower($order) == 'desc') ? SORT_DESC : SORT_ASC;
        $this->sort = [$column, $direction];

        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit) {
        $this->limit = intval($limit);

        return $this;
    }

    /**
     * @return int
     */
    public function lastId(): int {
        $rows = $this->xml->xpath('//database/' . $this->table . '/row');

        $id = [];
        foreach($rows as $row) {
            $id[] = (int) $row->id;
        }
        return max($id);
    }

    /**
     * Количество строк после выполнения updated или deleted
     *
     * @return int
     */
    public function affectedRows(): int {
        $num = $this->affected_rows;
        $this->affected_rows = 0;

        return $num;
    }

    /**
     * @return \SimpleXMLElement
     */
    private function addRow() {
        $table = $this->xml->xpath('//database/' . $this->table);
        return $table[0]->addChild('row');
    }

    /**
     * @param $data
     * @param $column
     * @param $dir
     * @return mixed
     */
    private function sortArray(array $data, string $column, string $dir) {
        $sort = [];

        foreach ($data as $key => $row) {
            if (isset($row->$column)) {
                $sort[$key] = $row->$column;
            }
        }

        if (!empty($sort)) {
            array_multisort($sort, $dir, $data);
        }

        return $data;
    }

    /**
     * @return mixed
     */
    private function store() {
        static::clearCache($this->dir);

        return $this->xml->asXML($this->db);
    }

    /**
     * Очистка всех переменных запроса
     *
     * @return $this
     */
    private function clear() {
        $this->table = null;
        $this->query = null;
        $this->bind = [];
        $this->columns = [];
        $this->sort = [];
        $this->join_table = null;
        $this->primary_key = null;
        $this->foreign_key = null;
        $this->limit = 0;

        return $this;
    }

    /**
     * @param $path
     */
    public static function clearCache(string $path) {
        foreach (glob($path . '*.cache') as $file) {
            @unlink($file);
        }
    }

    /**
     * @return string
     */
    public function __toString() {
        return '<pre>' . htmlspecialchars($this->xml->asXML(), ENT_QUOTES) . '</pre>';
    }

}