<?php
require_once "model.php";
require_once "food.model.php";

// TODO: Rewrite this class to use the new model class.
// TODO: Add all necessary CRUD methods.
class UserModelWrapper extends ModelFactory {
    private $uid;
    private $FoodTable;
    public function __construct(string $uid) {
        parent::__construct($uid);
        $this->uid = $uid;
        $this->FoodTable = new FoodModelWrapper();
        $this->createTable(
            ["id","jdoc","timestamp"],
            ["INT NOT NULL AUTO_INCREMENT",
            "JSON",
            "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP"]
        );
    }
    public function getUid() {
        return $this->uid;
    }

    public function getLastTwoDays(string $uid): array|null {
        $query = "SELECT * FROM `$uid` WHERE timestamp > DATE_SUB(NOW(), INTERVAL 2 DAY)";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return count($result) > 0 ? $result : null;
    }

    public function createNewDailyEntry() {
        $query = "INSERT INTO `$this->uid` (jdoc) VALUES (
            JSON_OBJECT(
                'breakfast', JSON_ARRAY(),
                'lunch', JSON_ARRAY(),
                'dinner', JSON_ARRAY(),
                'snacks', JSON_ARRAY()
            ))";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
    }
    public function addFoodToMeal(string $foodName, string $meal) {
        // $foodData = $this->FoodTable->getFoodData($foodName);
        $foodData = [
            "calories" => 100,
            "fat" => 100,
            "protein" => 100,
            "carbs" => 100,
            "saturated_fat" => 100,
            "sodium" => 100,
            "sugar" => 100,
            "cholesterol" => 100,
            "fiber" => 100,
            "potassium" => 100
        ];
        print "<pre>";
        print_r($foodData);
        print "</pre>";
        $sqlRes = $this->getLastTwoDays($this->uid);
        print "<pre>";
        print_r($sqlRes);
        print "</pre>";
        if ($sqlRes == null) $this->createNewDailyEntry();
        else if (count($sqlRes) == 1){
            $this->createNewDailyEntry();
            $nextId = $sqlRes[0]['id'] + 1;
            $query = new Query;
            $query->string = 
            "UPDATE `$this->uid` 
            SET jdoc = JSON_ARRAY_APPEND(jdoc, '$.{$meal}', 
                JSON_OBJECT(
                    'name', '{$foodName}',
                    'meta', JSON_OBJECT(
                        'calories', {$foodData['calories']},
                        'fat', {$foodData['fat']},
                        'protein', {$foodData['protein']},
                        'carbs', {$foodData['carbs']},
                        'saturated_fat', {$foodData['saturated_fat']},
                        'sodium', {$foodData['sodium']},
                        'sugar', {$foodData['sugar']},
                        'cholesterol', {$foodData['cholesterol']},
                        'fiber', {$foodData['fiber']},
                        'potassium', {$foodData['potassium']}
                    )))
            WHERE id = {$nextId};";
            $this->db->exec($query->string);

        } else {
            $current_entryID = $sqlRes[0]['id'] > $sqlRes[1]['id'] ? $sqlRes[0]['id'] : $sqlRes[1]['id'];
            $query = new Query;
            $query->string = 
            "UPDATE `$this->uid` SET jdoc = JSON_ARRAY_APPEND(jdoc, '$.{$meal}', 
                JSON_OBJECT(
                    'name', '{$foodName}',
                    'meta', JSON_OBJECT(
                        'calories', {$foodData['calories']},
                        'fat', {$foodData['fat']},
                        'protein', {$foodData['protein']},
                        'carbs', {$foodData['carbs']},
                        'saturated_fat', {$foodData['saturated_fat']},
                        'sodium', {$foodData['sodium']},
                        'sugar', {$foodData['sugar']},
                        'cholesterol', {$foodData['cholesterol']},
                        'fiber', {$foodData['fiber']},
                        'potassium', {$foodData['potassium']}
                    ))) 
            WHERE id = {$current_entryID};";
            $this->db->exec($query->string);
        }
        return;
    }

    /**
     * Get all user data from the database.
     * The data comes as an array of associative arrays.
     * To access the data, use the following syntax:
     * $data = $this->getUserData();
     * $data[0] // This will return the first entry.
     * $data[0]['timestamp'] // This will return the first entry's timestamp.
     * $data[0]['jdoc'] // This will return the first entry's json document.
     * $data[0]['jdoc']['breakfast'] // This will return the first entry's breakfast array.
     * $data[0]['jdoc']['breakfast'][0] // This will return the first entry's first breakfast item.
     * $data[0]['jdoc']['breakfast'][0]['name'] // This will return the first entry's first breakfast item's name.
     * $data[0]['jdoc']['breakfast'][0]['meta'] // This will return the first entry's first breakfast item's meta data.
     * $data[0]['jdoc']['breakfast'][0]['meta']['calories'] // This will return the first entry's first breakfast item's calories.
     * 
     * @return array|null
     */
    public function getUserData(): array|null {
        $query = "SELECT * FROM `$this->uid`";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}