    <?php

    class HomeController {
        private $foodModel;

        public function __construct() {
            $this->foodModel = new FoodModelWrapper();
        }
        public function handleRequest() {
            if (empty($_POST)) {
                $this->displayPage();
            } else {
                $this->handlePost();
            }
        }

        private function displayPage($args = []) {
            extract($args);
            $foods = $this->foodModel->getFoodNames();
            include "./view/home.php";
        }

        private function handlePost() {
            try {
                $this->checkInput();
                $fooddata = $this->foodModel->getFoodData($_POST["food"])[0];
            } catch (Exception $err) {
                $this->displayPage(["err" => $err]);
                return;
            }

            $food = array();
            foreach ($fooddata as $foodie) {
                if (is_numeric($foodie)) {
                    array_push($food, $_POST["amount"] / 100 * $foodie);
                } else {
                    array_push($food, $foodie);
                }
            }
            $this->displayPage(["datarr" => $food]);
        }

        private function checkInput() {
            if (empty($_POST["food"])) {
                throw new Exception("Enter a food item");
            }
            if (empty($_POST["amount"])) {
                $_POST["amount"] = 100;
            }
            if (!is_numeric($_POST["amount"])) {
                throw new Exception("Enter a valid number");
            }
        }
    }
