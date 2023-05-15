<?php

    /*
    Category Page
    */

ob_start(); // Output Buffering Start
session_start();
include 'init.php';
include_once 'model/CategoryModel.php';
include_once 'view/CategoryView.php';
$category = new category($con);
include $tpl.'footer.php';

class category
{
    private $con;
    private $pageTitle;
    private $do;
    private $model;
    private $view;

    public function __construct($con)
    {
        $this->con = $con;
        $this->pageTitle = 'Categories';
        $this->model = new CategoryModel($con);
        $this->view = new CategoryView();
        $this->run();
    }

    public function run()
    {
        if (isset($_SESSION['Username'])) {
            $this->do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

            switch ($this->do) {
            case 'Manage':
                $this->manageCategories();
                break;
            case 'Add':
                $this->view->addCategorie();
                break;
            case 'Insert':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $this->insertCategorie();
                }
                break;
            case 'Edit':
                $this->editCategorie();
                break;
            case 'Update':

                $this->updateCategorie();
                break;
            case 'Delete':
             $this->deleteCategorie();
             break;

            default:
                $this->manageCategories();
        }
        } else {
            header('Location: login.php');
            exit();
        }
    }

    private function manageCategories()
    {
        $catSort = $this->model->getAllCategories();
        $this->view->manageCategoriesHTML($catSort[0], $catSort[1]);
    }

    public function insertCategorie()
    {
        // ($name, $desc, $parent, $order, $visible, $comment, $ads)

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Insert Category</h1>";
            echo "<div class='container'>";

            // Get Variables From The Form

            $name = $_POST['name'];
            $desc = $_POST['description'];
            $parent = $_POST['parent'];
            $order = $_POST['ordering'];
            $visible = $_POST['visibility'];
            $comment = $_POST['commenting'];
            $ads = $_POST['ads'];

            // Check If Category Exist in Database

            $check = checkItem('Name', 'categories', $name);

            if ($check == 1) {
                $theMsg = '<div class="alert alert-danger">Sorry This Category Is Exist</div>';

                redirectHome($theMsg, 'back');
            } else {
                // Insert Category Info In Database
                $stmt = $this->model->insertCategorie($name, $desc, $parent, $order, $visible, $comment, $ads);
                // Echo Success Message

                $theMsg = "<div class='alert alert-success'>".$stmt->rowCount().' Record Inserted</div>';

                redirectHome($theMsg, 'back');
            }
        } else {
            echo "<div class='container'>";

            $theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

            redirectHome($theMsg, 'back');

            echo '</div>';
        }

        echo '</div>';
    }

    public function editCategorie()
    {
        // Check If Get Request catid Is Numeric & Get Its Integer Value

        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

        $cat = $this->model->editCategorie($catid);

        // If There's Such ID Show The Form
        $this->view->editCategorieHTML($cat, $catid);
    }

    public function updateCategorie()
    {
        echo "<h1 class='text-center'>Update Category</h1>";
        echo "<div class='container'>";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get Variables From The Form

            $id = $_POST['catid'];
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $order = $_POST['ordering'];
            $parent = $_POST['parent'];
            $visible = $_POST['visibility'];
            $comment = $_POST['commenting'];
            $ads = $_POST['ads'];

            // Update The Database With This Info
            $stmt = $this->model->updateCategorie($id, $name, $desc, $order, $parent, $visible, $comment, $ads);

            // Echo Success Message

            $theMsg = "<div class='alert alert-success'>".$stmt->rowCount().' Record Updated</div>';

            redirectHome($theMsg, 'back');
        } else {
            $theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

            redirectHome($theMsg);
        }

        echo '</div>';
    }

    public function deleteCategorie()
    {
        echo "<h1 class='text-center'>Delete Category</h1>";
        echo "<div class='container'>";

        // Check If Get Request Catid Is Numeric & Get The Integer Value Of It

        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

        // Select All Data Depend On This ID

        $check = checkItem('ID', 'categories', $catid);

        // If There's Such ID Show The Form

        if ($check > 0) {
            $stmt = $this->model->deleteCategorie($catid);
            $theMsg = "<div class='alert alert-success'>".$stmt->rowCount().' Record Deleted</div>';

            redirectHome($theMsg, 'back');
        } else {
            $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

            redirectHome($theMsg);
        }

        echo '</div>';
    }
}

    ob_end_flush(); // Release The Output
