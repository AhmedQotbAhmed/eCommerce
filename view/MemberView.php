<?php

class MemberView
{
    public function ManageMembersHTML($rows)
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

    public function addMember()
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

    public function editMemberHTML($row)
    {
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
}
