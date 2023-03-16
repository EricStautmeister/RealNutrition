<?php

require_once 'model.php';

class FoodModelWrapper extends ModelFactory
{
    private $mandatory_table_elements = [];
    private $optional_table_elements = [];
    private $meta_table_elements = [];
    private $nonMetaTable = [];
    public $data = [];
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
            "saturated_fat",
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

    public function loadData()
    {
        return $this->selectAll();
    }

    // TODO: Add a function to get specific food data, filtered by data and column


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
        $food_data = [
            $name,
            $description,
            $calories,
        ];
        $opt_food_data = [
            $fat,
            $protein,
            $carbs,
            $saturated_fat,
            $sodium,
            $sugar,
            $cholesterol,
            $fiber,
            $potassium
        ];
        $cols = $this->mandatory_table_elements;
        $cols = array_splice($cols, 1, 3);
        // echo nl2br("cols: " . print_r($cols, true) . PHP_EOL);
        // echo nl2br("food data: " . print_r($food_data, true) . "optional food data: " . PHP_EOL . print_r($opt_food_data, true) . PHP_EOL);
        for ($i = 0; $i < count($opt_food_data); $i++) {
            // echo nl2br("i={$i}: " . $this->optional_table_elements[$i] . $opt_food_data[$i] . PHP_EOL);
            if ($opt_food_data[$i] != null && $opt_food_data[$i] !== "" && $opt_food_data[$i] !== " " && $opt_food_data[$i] !== "0") {
                array_push($food_data, $opt_food_data[$i]);
                array_push($cols, $this->optional_table_elements[$i]);
            }
        }
        $this->insert($cols, $food_data);
        // for ($i = 0;$i < count($food_data); $i++) {
        //     echo nl2br("{$cols[$i]}: " . $food_data[$i] . PHP_EOL);
        // }
        // echo nl2br("\n\n") . print_r($cols, true) . nl2br("\n");
        // echo print_r($food_data, true) . nl2br("\n\n");

        // echo nl2br("\n final cols: " . print_r($cols, true) . "\nfinal food data: ||". var_export($food_data) . PHP_EOL);
    }

    // TODO: Test the delete function
    // TODO: Check if there are delete requirements
    public function deleteFood(string $control_column, string $control_value)
    {
        $this->delete($control_column, $control_value);
    }
    // TODO: Test the update function
    public function updateFood(
        string $constrol_column,
        string $control_value,
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
        $food_data = [
            $name,
            $description,
            $calories,
        ];
        $opt_food_data = [
            $fat,
            $protein,
            $carbs,
            $saturated_fat,
            $sodium,
            $sugar,
            $cholesterol,
            $fiber,
            $potassium
        ];
        $cols = array_splice($this->mandatory_table_elements, 1, -1);
        for ($i = 3; $i < count($opt_food_data); $i++) {
            if ($opt_food_data[$i] != null)
                array_push($food_data, $opt_food_data[$i]);
            array_push($cols, $this->optional_table_elements[$i]);
        }
        $this->update($constrol_column, $control_value, $cols, $food_data);
    }

    public function save()
    {
        foreach ($this->data as $food) {
            $this->insert($food[0], $food[1]);
        }
        $this->execute();
    }
}
