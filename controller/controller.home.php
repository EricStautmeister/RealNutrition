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
            $fooddata = $this->foodModel->getFoodData($_POST["food"])[0];
            $amount = $_POST["amount"];
            $food["name"] = $fooddata["name"];
            $food["calories"] = $amount / 100 * $fooddata["calories"];

            foreach ($fooddata as $foodie) {
                if ($foodie == null || is_string($foodie)) {
                    array_push($food, 0);
                } else {
                    array_push($food, $amount / 100 * $foodie);
                }
            }
            $this->displayPage(["datarr" => $food]);
        }
    }
