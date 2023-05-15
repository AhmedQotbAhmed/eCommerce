<?php

// Manage Members Page

ob_start(); // Output Buffering Start
session_start();
include 'init.php';
include_once 'model/MemberModel.php';

class MembersPage
{
    private $con;
    private $pageTitle;
    private $do;
    private $model;

    public function __construct($con)
    {
        $this->con = $con;
        $this->pageTitle = 'Members';
        $this->model = new MembersModel($con);
        $this->run();
    }

    public function run()
    {
        if (isset($_SESSION['Username'])) {
            $this->do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

            switch ($this->do) {
                case 'Manage':
                    $this->manageMembers();
                    break;
                case 'Add':
                    $this->addMember();
                    break;
                case 'Insert':
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $this->insertMember();
                    }
                    break;
                case 'Edit':
                    $this->editMember();
                    break;
                case 'Update':
                    $this->updateMember();
                    break;
                case 'Delete':
                    $userid = $this->getUserID();
                   // Select All Data Depend On This ID
                    $check = checkItem('userid', 'users', $userid);
                    $check > 0 ? $this->deleteMember($userid) : $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>'; redirectHome($theMsg);
                    break;
                case 'Activate':
                    $userid = $this->getUserID();
                    $check = checkItem('userid', 'users', $userid);

                    $check > 0 ? $this->activateMember($userid) : $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>'; redirectHome($theMsg);

                    break;
                default:
                    $this->manageMembers();
            }
        } else {
            header('Location: login.php');
            exit();
        }
    }

    private function manageMembers()
    {
        // call model class

        $row = $this->model->getAllMembers();
        $this->ManageMembersHTML($row);
    }

    private function ManageMembersHTML($rows)
    {
        if (!empty($rows)) {
            ?>

<h1 class="text-center">Manage Members</h1>
<div class="container">
    <div class="table-responsive">
        <table class="main-table manage-members text-center table table-bordered">
            <tr>
                <td>#ID</td>
                <td>Avatar</td>
                <td>Username</td>
                <td>Email</td>
                <td>Full Name</td>
                <td>Registered Date</td>
                <td>Control</td>
            </tr>
            <?php
                                        foreach ($rows as $row) {
                                            echo '<tr>';
                                            echo '<td>'.$row['UserID'].'</td>';
                                            echo '<td>';
                                            if (empty($row['avatar'])) {
                                                echo 'No Image';
                                            } else {
                                                echo "<img src='uploads/avatars/".$row['avatar']."' alt='' />";
                                            }
                                            echo '</td>';

                                            echo '<td>'.$row['Username'].'</td>';
                                            echo '<td>'.$row['Email'].'</td>';
                                            echo '<td>'.$row['FullName'].'</td>';
                                            echo '<td>'.$row['Date'].'</td>';
                                            echo "<td>
                                                    <a href='members.php?do=Edit&userid=".$row['UserID']."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                                    <a href='members.php?do=Delete&userid=".$row['UserID']."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                                            if ($row['RegStatus'] == 0) {
                                                echo "<a 
                                                                href='members.php?do=Activate&userid=".$row['UserID']."' 
                                                                class='btn btn-info activate'>
                                                                <i class='fa fa-check'></i> Activate</a>";
                                            }
                                            echo '</td>';
                                            echo '</tr>';
                                        } ?>
            <tr>
        </table>
    </div>
    <a href="members.php?do=Add" class="btn btn-primary">
        <i class="fa fa-plus"></i> New Member
    </a>
</div>

<?php
        } else {
            echo '<div class="container">';
            echo '<div class="nice-message">There\'s No Members To Show</div>';
            echo '<a href="members.php?do=Add" class="btn btn-primary">
							<i class="fa fa-plus"></i> New Member
						</a>';
            echo '</div>';
        } ?>

<?php

        // $content = ob_get_clean();
    }

    private function addMember()
    {
        ?>

<h1 class="text-center">Add New Member</h1>
<div class="container">
    <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
        <!-- Start Username Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Username</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="username" class="form-control" autocomplete="off" required="required"
                    placeholder="Username To Login Into Shop" />
            </div>
        </div>
        <!-- End Username Field -->
        <!-- Start Password Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Password</label>
            <div class="col-sm-10 col-md-6">
                <input type="password" name="password" class="password form-control" required="required"
                    autocomplete="new-password" placeholder="Password Must Be Hard & Complex" />
                <i class="show-pass fa fa-eye fa-2x"></i>
            </div>
        </div>
        <!-- End Password Field -->
        <!-- Start Email Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10 col-md-6">
                <input type="email" name="email" class="form-control" required="required"
                    placeholder="Email Must Be Valid" />
            </div>
        </div>
        <!-- End Email Field -->
        <!-- Start Full Name Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Full Name</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="full" class="form-control" required="required"
                    placeholder="Full Name Appear In Your Profile Page" />
            </div>
        </div>
        <!-- End Full Name Field -->
        <!-- Start Avatar Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">User Avatar</label>
            <div class="col-sm-10 col-md-6">
                <input type="file" name="avatar" class="form-control" />
            </div>
        </div>
        <!-- End Avatar Field -->
        <!-- Start Submit Field -->
        <div class="form-group form-group-lg">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value="Add Member" class="btn btn-primary btn-lg" />
            </div>
        </div>
        <!-- End Submit Field -->
    </form>
</div>

<?php
    }

    public function getAvatarName()
    {
        $avatarName = $_FILES['avatar']['name'];
        $avatarSize = $_FILES['avatar']['size'];
        $avatarTmp = $_FILES['avatar']['tmp_name'];
        $avatarType = $_FILES['avatar']['type'];

        $avatarAllowedExtension = ['jpeg', 'jpg', 'png', 'gif'];

        // Get Avatar Extension
        $avatarParts = explode('.', $avatarName);
        $avatar = strtolower(end($avatarParts));

        return $avatar;
    }

    // Insert Member Function
    public function insertMember()
    {
        echo "<h1 class='text-center'>Insert Member</h1>";
        echo "<div class='container'>";

        // Get Variables From The Form
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $fullName = $_POST['full'];
        $avatar = $this->getAvatarName();
        // call model class
        $stmt = $this->model->insertMember($username, $password, $email, $fullName, $avatar);

        // Check if the insertion was successful
        if ($stmt->rowCount() > 0) {
            $theMsg = "<div class='alert alert-success'>".$stmt->rowCount().' Record Inserted</div>';
            redirectHome($theMsg, 'back');

            return true; // Return true to indicate success
        } else {
            echo '<div class="alert alert-danger">Failed to insert the member.</div>';

            return false; // Return false to indicate an error occurred
        }
    }

    public function getUserID()
    {
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        return $userid;
    }

    // Edit Member Function
    public function editMember()
    {
        $userid = $this->getUserID();
        // call model class
        $row = $this->model->editMember($userid);

        if (!empty($row)) { ?>

<h1 class="text-center">Edit Member</h1>
<div class="container">
    <form class="form-horizontal" action="?do=Update" method="POST">
        <input type="hidden" name="userid" value="<?php echo $userid; ?>" />
        <!-- Start Username Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Username</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="username" class="form-control" value="<?php echo $row['Username']; ?>"
                    autocomplete="off" required="required" />
            </div>
        </div>
        <!-- End Username Field -->
        <!-- Start Password Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Password</label>
            <div class="col-sm-10 col-md-6">
                <input type="hidden" name="oldpassword" value="<?php echo $row['Password']; ?>" />
                <input type="password" name="newpassword" class="form-control" autocomplete="new-password"
                    placeholder="Leave Blank If You Dont Want To Change" />
            </div>
        </div>
        <!-- End Password Field -->
        <!-- Start Email Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10 col-md-6">
                <input type="email" name="email" value="<?php echo $row['Email']; ?>" class="form-control"
                    required="required" />
            </div>
        </div>
        <!-- End Email Field -->
        <!-- Start Full Name Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Full Name</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="full" value="<?php echo $row['FullName']; ?>" class="form-control"
                    required="required" />
            </div>
        </div>
        <!-- End Full Name Field -->
        <!-- Start Submit Field -->
        <div class="form-group form-group-lg">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value="Save" class="btn btn-primary btn-lg" />
            </div>
        </div>
        <!-- End Submit Field -->
    </form>
</div>

<?php

            // If There's No Such ID Show Error Message
            } else {
                echo "<div class='container'>";
                $theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';
                echo '</div>';
                redirectHome($theMsg, 'back');
                echo '</div>';

                return false;
            }
    }

    // Update Member Function
    public function updateMember()
    {
        echo "<h1 class='text-center'>Update Member</h1>";
        echo "<div class='container'>";

        $userID = $_POST['userid'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $fullName = $_POST['full'];
        $password = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

        // Update Member using model class
        $stmt = $this->model->updateMember($userID, $username, $email, $fullName, $password);

        $theMsg = "<div class='alert alert-success'>".$stmt->rowCount().' Record Updated</div>';

        redirectHome($theMsg, 'back');

        return true;
    }

    // Delete Member Function
    public function deleteMember($userID)
    {
        echo "<h1 class='text-center'>Delete Member</h1>";
        echo "<div class='container'>";

        // Delete Member using model class
        $stmt = $this->model->deleteMember($userID);
        $theMsg = "<div class='alert alert-success'>".$stmt->rowCount().' Record Updated</div>';

        // call model class

        redirectHome($theMsg, 'back');

        return $stmt->rowCount();
    }

    // Activate Member Function
    public function activateMember($userID)
    {
        echo "<h1 class='text-center'>Activate Member</h1>";
        echo "<div class='container'>";

        // Activate Member using model class
        $stmt = $this->model->activateMember($userID);
        $theMsg = "<div class='alert alert-success'>".$stmt->rowCount().' Record Updated</div>';

        redirectHome($theMsg, 'back');

        return $stmt->rowCount();
    }
}
$member = new MembersPage($con);
include $tpl.'footer.php';
