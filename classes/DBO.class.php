<?php 
class DBO {
    protected $db;//databse
    protected $data = array();//data of the object;
    protected $table_name;
    protected $primary_key;
    protected $columns;
    function __construct($params) {
        $this->db = $params['db'];
        $this->table_name = $params['table_name'];
        $this->primary_key = $params['primary_key'];
        $this->columns = $params['columns'];
    }
    
    function select($id) {
        $sql = sprintf('SELECT * FROM %s WHERE %s = %s', $this->table_name, $this->primary_key, $id);
        $result = $this->db->query($sql);
        $pairs = $result->fetch_assoc();
        if (count($pairs)) {
            foreach($pairs as $name => $value) {
//                echo $name;
//                echo $value;
                $this->data[$name] = $value;
            }
        }
    }
    
    function __get($key) {
        return $this->data[$key];
    }
}
?>