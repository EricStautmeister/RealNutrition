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
                $response = $this->foodModel->getFoodData($_POST["food"]);
                if ($response[0]) {
                    $fooddata = $response[0];
                } else {
                }
            } catch (Exception $err) {
                $this->displayPage(["err" => $err->getMessage()]);
            }
            if (isset($fooddata)) {
                $food = array();
                foreach ($fooddata as $foodvalue) {
                    if (is_numeric($foodvalue)) {
                        array_push($food, $_POST["amount"] / 100 * $foodvalue);
                    } else if ($foodvalue == null) {
                        array_push($food, 0);
                    } else {
                        array_push($food, $foodvalue);
                    }
                }
                array_shift($food);
                DashboardController::logoutUser();
                $this->displayPage(["fooddata" => $food]);
            }
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
