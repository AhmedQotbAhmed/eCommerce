<?php

// Manage Members Page

ob_start(); // Output Buffering Start
session_start();
include 'init.php';
$member = new MembersPage($con);
class MembersPage
{
    private $con;
    private $pageTitle;
    private $do;

    public function __construct($con)
    {
        $this->con = $con;
        $this->pageTitle = 'Members';
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
                        echo "<h1 class='text-center'>Insert Member</h1>";
                        echo "<div class='container'>";

                        $avatarName = $_FILES['avatar']['name'];
                        $avatarSize = $_FILES['avatar']['size'];
                        $avatarTmp = $_FILES['avatar']['tmp_name'];
                        $avatarType = $_FILES['avatar']['type'];

                        $avatarAllowedExtension = ['jpeg', 'jpg', 'png', 'gif'];

                        // Get Avatar Extension
                        $avatarParts = explode('.', $avatarName);
                        $avatarExtension = strtolower(end($avatarParts));

                        // Get Variables From The Form

                        $username = $_POST['username'];
                        $pass = $_POST['password'];
                        $email = $_POST['email'];
                        $name = $_POST['full'];
                        $this->insertMember($username, $pass, $email, $name, $avatarExtension);
                    }
                    break;
                case 'Edit':
                    $this->editMember($userid);
                    break;
                case 'Update':
                    echo "<h1 class='text-center'>Update Member</h1>";
                    echo "<div class='container'>";
                    $userID = $_POST['userid'];
                    $username = $_POST['username'];
                    $email = $_POST['email'];
                    $fullName = $_POST['full'];
                    $password = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

                    $this->updateMember($userID, $username, $password, $email, $fullName);
                    break;
                case 'Delete':
                    $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

                   // Select All Data Depend On This ID

                    $check = checkItem('userid', 'users', $userid);
                    $check > 0 ? $this->deleteMember($userid) : $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>'; redirectHome($theMsg);
                    break;
                case 'Activate':
                    $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
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
        $query = '';

        if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
            $query = 'AND RegStatus = 0';
        }

        $stmt = $this->con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $this->ManageMembersHTML($rows);
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
    { ?>

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
                <input type="file" name="avatar" class="form-control" required="required" />
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

    // Insert Member Function
    public function insertMember($username, $password, $email, $fullName, $avatar)
    {
        // Validate the form data
        $formErrors = $this->validateMemberForm($username, $password, $email, $fullName, $avatar);

        // Check if there are any errors
        if (!empty($formErrors)) {
            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">'.$error.'</div>';
            }

            return false; // Return false to indicate an error occurred
        }

        // Encrypt the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the member data into the database
        $stmt = $this->con->prepare('INSERT INTO users(Username, Password, Email, FullName, Avatar, RegStatus, Date) 
                        VALUES (?, ?, ?, ?, ?, 0, now())');
        $stmt->execute([$username, $hashedPassword, $email, $fullName, $avatar]);

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

    // Validate Member Form Function
    public function validateMemberForm($username, $password, $email, $fullName, $avatar)
    {
        $errors = [];

        // Validate username
        if (empty($username)) {
            $errors[] = 'Username is required.';
        } elseif (strlen($username) < 3) {
            $errors[] = 'Username must be at least 3 characters long.';
        }

        // Validate password
        if (empty($password)) {
            $errors[] = 'Password is required.';
        } elseif (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters long.';
        }

        // Validate email
        if (empty($email)) {
            $errors[] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format.';
        }

        // Validate fullName
        if (empty($fullName)) {
            $errors[] = 'Full name is required.';
        } elseif (strlen($fullName) < 3) {
            $errors[] = 'Full name must be at least 3 characters long.';
        }

        // Validate avatar
        if (empty($avatar)) {
            $errors[] = 'Avatar is required.';
        }

        return $errors;
    }

    // Edit Member Function
    public function editMember($userid)
    {
        $stmt = $this->con->prepare('SELECT * FROM users WHERE UserID = ? LIMIT 1');
        $stmt->execute([$userid]);
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) { ?>

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

                redirectHome($theMsg);

                echo '</div>';

                return $stmt->fetch();
            }
    }

    // Update Member Function
    public function updateMember($userID, $username, $password, $email, $fullName)
    {
        $stmt = $this->con->prepare('UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?');

        $stmt->execute([$username, $email, $fullName, $password, $userID]);
        $theMsg = "<div class='alert alert-success'>".$stmt->rowCount().' Record Updated</div>';

        redirectHome($theMsg, 'back');

        return $stmt->rowCount();
    }

    // Delete Member Function
    public function deleteMember($userID)
    {
        echo "<h1 class='text-center'>Delete Member</h1>";
        echo "<div class='container'>";
        $stmt = $this->con->prepare('DELETE FROM users WHERE UserID = ?');
        $stmt->execute([$userID]);
        $theMsg = "<div class='alert alert-success'>".$stmt->rowCount().' Record Updated</div>';

        redirectHome($theMsg);

        return $stmt->rowCount();
    }

    // Activate Member Function
    public function activateMember($userID)
    {
        echo "<h1 class='text-center'>Activate Member</h1>";
        echo "<div class='container'>";
        $stmt = $this->con->prepare('UPDATE users SET RegStatus = 1 WHERE UserID = ?');
        $stmt->execute([$userID]);
        $theMsg = "<div class='alert alert-success'>".$stmt->rowCount().' Record Updated</div>';

        redirectHome($theMsg);

        return $stmt->rowCount();
    }
}
