<?php

require_once 'model2.php';

class FoodTable extends ModelFactory
{
    public function __construct()
    {
        parent::__construct("food");
    }

    public function getFood()
    {
        $sql = "SELECT * FROM food";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}