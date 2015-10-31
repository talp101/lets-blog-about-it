<?php

/**
 * Created by PhpStorm.
 * User: Tal
 * Date: 31/10/2015
 * Time: 21:34
 */
class MySqlAdapter implements DataBaseAdapterInterface
{

    protected $config = array();
    protected $connection;
    protected $result;

    public function __construct(array $config){
        if(count($config) !== 4){
            throw new InvalidArgumentException('Invalid number of connection parameters');
        }

        $this->config = $config;
    }

    function connect()
    {
        if(!$this->connection){
            list($host,$user,$password,$database) = $this->config;
            if(!$this->connection = @mysqli_connect($host, $user, $password, $database)){
                throw new RuntimeException('Error connecting to the server:' . mysqli_connect_error());
            }
            unset($host, $user, $password, $database);
        }
        return $this->connection;
    }

    function disconnect()
    {
        if ($this->connection === null) {
            return false;
        }
        mysqli_close($this->connection);
        $this->_link = null;
        return true;
    }

    function query($query)
    {
        if(!is_string($query) || empty($query)){
            throw new InvalidArgumentException('The specified query is not valid.');
        }

        $this->connect();
        if(!$this->result = mysqli_query($this->connection, $query)){
            throw new RuntimeException('Error executing the specified query' . $query . mysqli_error($this->connection));
        }

        return $this->result;
    }

    function fetch()
    {
        if($this->result !== null){
            if (($row = mysqli_fetch_array($this->result, MYSQLI_ASSOC)) === false){
                $this->freeResult();
            }
            return $row;
        }
        return false;
    }

    function select($table, $where = '', $fields = '*', $order = '', $limit = null, $offset = null)
    {
        $query = 'SELECT' .$fields . 'FROM' .$table
            .(($where) ? ' WHERE ' .$where : '')
            .(($limit) ? ' LIMIT ' .$limit : '')
            .(($offset && $limit) ? ' OFFSET ' .$offset : '')
            .(($order) ? ' ORDER BY ' . $order : '');
        $this->query($query);
        return $this->countRows();
    }

    function insert($table, array $data)
    {
        $fields = implode(',', array_keys($data));
        $values = implode(',', array_map(array($this, 'quoteValue'), array_values($data)));
        $query = 'INSERT INTO ' .$table .'('.$fields.')'.'VALUES('.$values.')';
        $this->query($query);
        return $this->getInsertId();
    }

    function update($table, array $data, $where)
    {
        $set = array();
        foreach($data as $field => $value){
            $set[] = $field. '=' .$this->quoteValue($value);
        }

        $set = implode(',', $set);
        $query = 'UPDATE ' .$table . ' SET ' .$set
            .(($where) ? ' WHERE ' .$where : '');
        $this->query($query);
        return $this->getAffectedRows();
    }

    function delete($table, $where)
    {
        $query = 'DELETE FROM ' .$table
            .(($where) ? ' WHERE ' .$where : '');
        $this->query($query);
        return $this->getAffectedRows();
    }

    function getInsertId()
    {
        return $this->connection !== null ? mysqli_insert_id($this->connection) : null;
    }

    function countRows()
    {
        return $this->result !== null ? mysqli_num_rows($this->result) : 0;
    }

    function getAffectedRows()
    {
        return $this->connection !== null ? mysqli_affected_rows($this->connection) : 0;
    }

    private function freeResult()
    {
        if ($this->result === null) {
            return false;
        }
        mysqli_free_result($this->result);
        return true;
    }

    private function quoteValue($value)
    {
        $this->connect();
        if ($value === null) {
            $value = 'NULL';
        }
        else if (!is_numeric($value)) {
            $value = "'" . mysqli_real_escape_string($this->connection, $value) . "'";
        }
        return $value;
    }
}