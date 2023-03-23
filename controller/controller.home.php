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
            $food = $this->foodModel->getFoodData($_POST["food"]);
            $amount = $_POST["amount"];
            $this->displayPage(["data" => $food]);
        }
    }
