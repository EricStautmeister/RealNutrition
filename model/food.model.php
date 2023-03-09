<?php

require_once 'model2.php';

class FoodTable extends ModelFactory
{
    private $mandatory_table_elements = [];
    private $optional_table_elements = [];
    private $meta_table_elements = [];
    private $nonMetaTable = [];
    public function __construct()
    {
        parent::__construct("food");
        $this->mandatory_table_elements = [
            "id",
            "name",
            "description",
            "calories",
        ];
        $this->optional_table_elements = [
            "fat",
            "protein",
            "carbs",
            "saturated fat",
            "sodium",
            "sugar",
            "cholesterol",
            "fiber",
            "potassium",
        ];
        $this->meta_table_elements = [
            "created_at",
            "updated_at"
        ];
        $this->nonMetaTable = array_merge($this->mandatory_table_elements, $this->optional_table_elements);
        $entireTable = array_merge($this->mandatory_table_elements, $this->optional_table_elements, $this->meta_table_elements);

        $this->createTable(
            $entireTable,
            [
                "INT(11) NOT NULL AUTO_INCREMENT",
                "VARCHAR(255) NOT NULL",
                "VARCHAR(255) NOT NULL",
                "VARCHAR(255) NOT NULL",

                "VARCHAR(255)",
                "VARCHAR(255)",
                "VARCHAR(255)",
                "VARCHAR(255)",
                "VARCHAR(255)",
                "VARCHAR(255)",
                "VARCHAR(255)",
                "VARCHAR(255)",
                "VARCHAR(255)",

                "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP",
                "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
            ]
        );
    }

    public function getFood()
    {
        return $this->selectAll();
    }

    public function addFood(
        string $name,
        string $description,
        string $calories,
        string $fat = null,
        string $protein = null,
        string $carbs = null,
        string $saturated_fat = null,
        string $sodium = null,
        string $sugar = null,
        string $cholesterol = null,
        string $fiber = null,
        string $potassium = null,
    ) {
        $this->insert(
            $this->nonMetaTable,
            [
                $name,
                $description,
                $calories,
                $fat,
                $protein,
                $carbs,
                $saturated_fat,
                $sodium,
                $sugar,
                $cholesterol,
                $fiber,
                $potassium
            ]
        );
    }
}
