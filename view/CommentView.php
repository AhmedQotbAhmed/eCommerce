<?php

class CommentView
{
    public function ManageAllCommentsHTML($comments)
    {
        if (!empty($comments)) {
            ?>

<h1 class="text-center">Manage Comments</h1>
<div class="container">
    <div class="table-responsive">
        <table class="main-table text-center table table-bordered">
            <tr>
                <td>ID</td>
                <td>Comment</td>
                <td>Item Name</td>
                <td>User Name</td>
                <td>Added Date</td>
                <td>Control</td>
            </tr>
            <?php
                            foreach ($comments as $comment) {
                                echo '<tr>';
                                echo '<td>'.$comment['c_id'].'</td>';
                                echo '<td>'.$comment['comment'].'</td>';
                                echo '<td>'.$comment['Item_Name'].'</td>';
                                echo '<td>'.$comment['Member'].'</td>';
                                echo '<td>'.$comment['comment_date'].'</td>';
                                echo "<td>
										<a href='comments.php?do=Edit&comid=".$comment['c_id']."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
										<a href='comments.php?do=Delete&comid=".$comment['c_id']."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                                if ($comment['status'] == 0) {
                                    echo "<a href='comments.php?do=Approve&comid="
                                                     .$comment['c_id']."' 
													class='btn btn-info activate'>
													<i class='fa fa-check'></i> Approve</a>";
                                }
                                echo '</td>';
                                echo '</tr>';
                            } ?>
            <tr>
        </table>
    </div>
</div>

<?php
        } else {
            echo '<div class="container">';
            echo '<div class="nice-message">There\'s No Comments To Show</div>';
            echo '</div>';
        } ?>

<?php
    }

    public function ManageEditHTML($row, $comid)
    {
        if (!empty($row)) { ?>

<h1 class="text-center">Edit Comment</h1>
<div class="container">
    <form class="form-horizontal" action="?do=Update" method="POST">
        <input type="hidden" name="comid" value="<?php echo $comid; ?>" />
        <!-- Start Comment Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Comment</label>
            <div class="col-sm-10 col-md-6">
                <textarea class="form-control" name="comment"><?php echo $row['comment']; ?></textarea>
            </div>
        </div>
        <!-- End Comment Field -->
        <!-- Start Submit Field -->
        <div class="form-group form-group-lg">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value="Save" class="btn btn-primary btn-sm" />
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
            }
    }
}
