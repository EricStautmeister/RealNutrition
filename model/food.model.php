<?php

require_once 'model.php';

interface FoodModel {
    public function getFoodNames(): array;
    public function getFoodData(string $foodName): array;
    public function getFoodDataByColumn(string $column, string $data): array;
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
        string $potassium = null
    ): void;
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
        string $potassium = null
    ): void;
    public function deleteFood(string $control_column, string $control_value): void;

    public function load(): array;
    public function save(): void;
}

/**
 * This class implements the FoodModel interface and
 * and extends the ModelFactory class to interact with the food database.
 * 
 * The methods implemented in this class are:
 * 1. getFoodNames(): Gets the names of all the foods in the database.
 * 2. getFoodData(): Gets the data of a specific food in the database.
 * 3. getFoodDataByColumn(): Gets the data of a specific food in the database by a specific column.
 * 4. addFood(): Adds a food to the database.
 * 5. updateFood(): Updates a food in the database.
 * 6. deleteFood(): Deletes a food from the database.
 * 
 * @implements FoodModel
 * @extends ModelFactory
 */
class FoodModelWrapper extends ModelFactory implements FoodModel {
    /**
     * @var array $mandatory_table_elements The mandatory elements of the food table.
     */
    private $mandatory_table_elements = [];

    /**
     * @var array $optional_table_elements The optional elements of the food table.
     */
    private $optional_table_elements = [];

    /**
     * @var array $meta_table_elements The meta elements that are auto generated of the food table.
     */
    private $meta_table_elements = [];

    /**
     * @var array $nonMetaTable The non meta elements of the food table.
     */
    private $nonMetaTable = [];

    /**
     * @var array $data The data of the food table, all the food.
     */
    public $data = [];

    public function __construct() {
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
                "VARCHAR(255)",
                "VARCHAR(255) NOT NULL",

                "FLOAT(20)",
                "FLOAT(20)",
                "FLOAT(20)",
                "FLOAT(20)",
                "FLOAT(20)",
                "FLOAT(20)",
                "FLOAT(20)",
                "FLOAT(20)",
                "FLOAT(20)",

                "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP",
                "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
            ]
        );
    }

    /**
     * Load all the data from the database.
     */
    //TODO: Add the data to the data property
    public function load(): array {
        return $this->selectAll();
    }

    /**
     * Adds a food to the database.
     * @param string $name
     * @param string $description
     * @param string $calories
     * @param string|null $fat
     * @param string|null $protein
     * @param string|null $carbs
     * @param string|null $saturated_fat
     * @param string|null $sodium
     * @param string|null $sugar
     * @param string|null $cholesterol
     * @param string|null $fiber
     * @param string|null $potassium
     * @return void
     */
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
    ): void {
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
        for ($i = 0; $i < count($opt_food_data); $i++) {
            if ($opt_food_data[$i] != null && $opt_food_data[$i] !== "" && $opt_food_data[$i] !== " " && $opt_food_data[$i] !== "0") {
                array_push($food_data, $opt_food_data[$i]);
                array_push($cols, $this->optional_table_elements[$i]);
            }
        }
        $this->insert($cols, $food_data)->execute();
    }

    /**
     * Get only the food names of all foods in the database.  
     * @return array
     */
    //TODO: Use the data property instead of the database, after the data has been loaded
    public function getFoodNames(): array {
        $food_names = [];
        $food_data = $this->selectAll();
        foreach ($food_data as $food) {
            array_push($food_names, $food["name"]);
        }
        return $food_names;
    }

    /**
     * Get the food data of a specific food.
     * @param string $foodName
     * @return array
     */
    public function getFoodData(string $foodName): array {
        $food_data = $this->select("name", $foodName);
        return $food_data;
    }

    /**
     * Get the food data of a specific food, filtered by any column.
     * @param string $column
     * @param string $data
     * @return array
     */
    public function getFoodDataByColumn(string $column, string $data): array {
        $food_data = $this->select($column, $data);
        return $food_data;
    }
    /**
     * Deletes a food from the database.
     * @param string $control_column
     * @param string $control_value
     * @return void
     */
    // TODO: Test the delete function
    // TODO: Check if there are delete requirements
    public function deleteFood(string $control_column, string $control_value): void {
        $this->delete($control_column, $control_value);
    }

    // TODO: Test the update function
    /**
     * Updates a food in the database.
     * @param string $control_column
     * @param string $control_value
     * @param string $name
     * @param string $description
     * @param string $calories
     * @param string|null $fat
     * @param string|null $protein
     * @param string|null $carbs
     * @param string|null $saturated_fat
     * @param string|null $sodium
     * @param string|null $sugar
     * @param string|null $cholesterol
     * @param string|null $fiber
     * @param string|null $potassium
     * @return void
     */
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
    ): void {
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

    /**
     * Saves the data to the database.
     * @return void
     */
    public function save(): void {
        foreach ($this->data as $food) {
            $this->insert($food[0], $food[1]);
        }
        $this->execute();
    }
}
