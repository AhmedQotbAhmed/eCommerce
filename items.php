<?php

    /*
     Items Page
    */

    ob_start(); // Output Buffering Start
    session_start();
    include 'init.php';
    include_once 'model/ItemModel.php';
    include_once 'view/ItemView.php';

    class item
    {
        private $con;
        private $pageTitle;
        private $do;
        private $model;
        private $view;

        public function __construct($con)
        {
            $this->con = $con;
            $this->pageTitle = 'Items';
            $this->model = new ItemModel($con);
            $this->view = new ItemView();
            $this->run();
        }

        private function run()
        {
            if (isset($_SESSION['Username'])) {
                $this->do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

                switch ($this->do) {
                case 'Manage':
                    $this->manageItems();
                    break;
                case 'Add':
                    $this->view->addItem();
                    break;
                case 'Insert':

                        $this->insertItem();
                    break;
                case 'Edit':
                    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
                    $this->editItem($itemid);
                    break;
                case 'Update':
                    $this->updateItem();
                      break;
                case 'Delete':
                    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

                   // Select All Data Depend On This ID

                    $check = checkItem('userid', 'users', $itemid);
                    $check > 0 ? $this->deleteItem($itemid) : $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>'; redirectHome($theMsg);
                    break;
                case 'Approve':
                      // Check If Get Request Item ID Is Numeric & Get The Integer Value Of It

            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

            // Select All Data Depend On This ID

            $check = checkItem('Item_ID', 'items', $itemid);

                    $check > 0 ? $this->ApproveItem($itemid) : $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>'; redirectHome($theMsg);

                    break;
                default:
                    $this->manageItems();
            }
            } else {
                header('Location: login.php');
                exit();
            }
        }

        private function manageItems()
        {
            $rows = $this->model->getAllItems();

            $this->view->ManageItemsHTML($rows);
        }

        private function insertItem()
        {
            echo "<h1 class='text-center'>Insert Item</h1>";
            echo "<div class='container'>";
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $name = $_POST['name'];
                $desc = $_POST['description'];
                $price = $_POST['price'];
                $country = $_POST['country'];
                $status = $_POST['status'];
                $member = $_POST['member'];
                $cat = $_POST['category'];
                $tags = $_POST['tags'];

                $stmt = $this->model->insertItem($name, $desc, $price, $country,
                $status, $member, $cat, $tags);

                if ($stmt->rowCount() > 0) {
                    // Echo Success Message

                    $theMsg = "<div class='alert alert-success'>".$stmt->rowCount().' Record Inserted</div>';

                    redirectHome($theMsg, 'back');

                    return true;
                } else {
                    echo "<div class='container'>";

                    $theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

                    redirectHome($theMsg);

                    echo '</div>';

                    return false;
                }

                echo '</div>';

                echo '</div>';
            }

            return false;
        }

        public function editItem($itemid)
        {
            $item = $this->model->editItem($itemid);
            // If There's Such ID Show The Form
            $this->view->editItemHTML($item);
        }

        private function updateItem()
        {
            $id = $_POST['itemid'];
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $cat = $_POST['category'];
            $member = $_POST['member'];
            $tags = $_POST['tags'];

            echo "<h1 class='text-center'>Update Item</h1>";
            echo "<div class='container'>";

            $stmt = $this->model->updateItem($name, $desc, $price,
                $country, $status, $member, $cat, $tags, $id);

            // Echo Success Message
            if ($stmt->rowCount() > 0) {
                $theMsg = "<div class='alert alert-success'>".$stmt->rowCount().' Record Updated</div>';

                redirectHome($theMsg, 'back');

                return true;
            } else {
                $theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
                redirectHome($theMsg);

                return false;
            }

            echo '</div>';
        }

        private function deleteItem($itemid)
        {
            echo "<h1 class='text-center'>Delete Item</h1>";
            echo "<div class='container'>";

            // If There's Such ID Show The Form
            $stmt = $this->model->deleteItem($itemid);

            if ($stmt->rowCount() > 0) {
                $theMsg = "<div class='alert alert-success'>".$stmt->rowCount().' Record Deleted</div>';

                redirectHome($theMsg, 'back');
            } else {
                $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

                redirectHome($theMsg);
            }

            echo '</div>';
        }

        private function ApproveItem($itemid)
        {
            echo "<h1 class='text-center'>Approve Item</h1>";
            echo "<div class='container'>";

            // If There's Such ID Show The Form

            $stmt = $this->model->ApproveItem($itemid);
            $theMsg = "<div class='alert alert-success'>".$stmt->rowCount().' Record Updated</div>';

            redirectHome($theMsg, 'back');
        }
    }
    $item = new item($con);
    include $tpl.'footer.php';
