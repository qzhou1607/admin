<?php 
class Shop extends DBO {
    function __construct($db, $id = 0) {
        parent::__construct(array(
            'db' => $db,
            'table_name' => 'shops',
            'primary_key'=> 'shop_id',
            'columns'    => 'shop_id, first_name, last_name, distance_unit, weight_unit, address_1, address_2, city, state, country, postal_code, phone, email',
        ));
        $id = (int) $id;
        $this->select($id);
    }
    
    
}
?>