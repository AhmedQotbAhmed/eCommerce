<?php

    class ItemView
    {
        public function ManageItemsHTML($items)
        {
            if (!empty($items)) {
                ?>


<h1 class="text-center">Manage Items</h1>
<div class="container">
    <div class="table-responsive">
        <table class="main-table text-center table table-bordered">
            <tr>
                <td>#ID</td>
                <td>Item Name</td>
                <td>Description</td>
                <td>Price</td>
                <td>Adding Date</td>
                <td>Category</td>
                <td>Username</td>
                <td>Control</td>
            </tr>
            <?php
                            foreach ($items as $item) {
                                echo '<tr>';
                                echo '<td>'.$item['Item_ID'].'</td>';
                                echo '<td>'.$item['Name'].'</td>';
                                echo '<td>'.$item['Description'].'</td>';
                                echo '<td>'.$item['Price'].'</td>';
                                echo '<td>'.$item['Add_Date'].'</td>';
                                echo '<td>'.$item['category_name'].'</td>';
                                echo '<td>'.$item['Username'].'</td>';
                                echo "<td>
										<a href='items.php?do=Edit&itemid=".$item['Item_ID']."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
										<a href='items.php?do=Delete&itemid=".$item['Item_ID']."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                                if ($item['Approve'] == 0) {
                                    echo "<a 
													href='items.php?do=Approve&itemid=".$item['Item_ID']."' 
													class='btn btn-info activate'>
													<i class='fa fa-check'></i> Approve</a>";
                                }
                                echo '</td>';
                                echo '</tr>';
                            } ?>
            <tr>
        </table>
    </div>
    <a href="items.php?do=Add" class="btn btn-sm btn-primary">
        <i class="fa fa-plus"></i> New Item
    </a>
</div>

<?php
            } else {
                echo '<div class="container">';
                echo '<div class="nice-message">There\'s No Items To Show</div>';
                echo '<a href="items.php?do=Add" class="btn btn-sm btn-primary">
							<i class="fa fa-plus"></i> New Item
						</a>';
                echo '</div>';
            } ?>

<?php
        }

        public function addItem()
        { ?>

<h1 class="text-center">Add New Item</h1>
<div class="container">
    <form class="form-horizontal" action="?do=Insert" method="POST">
        <!-- Start Name Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Name</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="name" class="form-control" required="required"
                    placeholder="Name of The Item" />
            </div>
        </div>
        <!-- End Name Field -->
        <!-- Start Description Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Description</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="description" class="form-control" required="required"
                    placeholder="Description of The Item" />
            </div>
        </div>
        <!-- End Description Field -->
        <!-- Start Price Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Price</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="price" class="form-control" required="required"
                    placeholder="Price of The Item" />
            </div>
        </div>
        <!-- End Price Field -->
        <!-- Start Country Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Country</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="country" class="form-control" required="required"
                    placeholder="Country of Made" />
            </div>
        </div>
        <!-- End Country Field -->
        <!-- Start Status Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Status</label>
            <div class="col-sm-10 col-md-6">
                <select name="status">
                    <option value="0">...</option>
                    <option value="1">New</option>
                    <option value="2">Like New</option>
                    <option value="3">Used</option>
                    <option value="4">Very Old</option>
                </select>
            </div>
        </div>
        <!-- End Status Field -->
        <!-- Start Members Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Member</label>
            <div class="col-sm-10 col-md-6">
                <select name="member">
                    <option value="0">...</option>
                    <?php
                                    $allMembers = getAllFrom('*', 'users', '', '', 'UserID');
                                    foreach ($allMembers as $user) {
                                        echo "<option value='".$user['UserID']."'>".$user['Username'].'</option>';
                                    }
                                ?>
                </select>
            </div>
        </div>
        <!-- End Members Field -->
        <!-- Start Categories Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Category</label>
            <div class="col-sm-10 col-md-6">
                <select name="category">
                    <option value="0">...</option>
                    <?php
                                    $allCats = getAllFrom('*', 'categories', 'where parent = 0', '', 'ID');
                                    foreach ($allCats as $cat) {
                                        echo "<option value='".$cat['ID']."'>".$cat['Name'].'</option>';
                                        $childCats = getAllFrom('*', 'categories', "where parent = {$cat['ID']}", '', 'ID');
                                        foreach ($childCats as $child) {
                                            echo "<option value='".$child['ID']."'>--- ".$child['Name'].'</option>';
                                        }
                                    }
                                ?>
                </select>
            </div>
        </div>
        <!-- End Categories Field -->
        <!-- Start Tags Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Tags</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="tags" class="form-control" placeholder="Separate Tags With Comma (,)" />
            </div>
        </div>
        <!-- End Tags Field -->
        <!-- Start Submit Field -->
        <div class="form-group form-group-lg">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value="Add Item" class="btn btn-primary btn-sm" />
            </div>
        </div>
        <!-- End Submit Field -->
    </form>
</div>

<?php

        }

        public function editItemHTML($item, $itemid, $rows)
        {
            if (!empty($item)) { ?>

<h1 class="text-center">Edit Item</h1>
<div class="container">
    <form class="form-horizontal" action="?do=Update" method="POST">
        <input type="hidden" name="itemid" value="<?php echo $itemid; ?>" />
        <!-- Start Name Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Name</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="name" class="form-control" required="required" placeholder="Name of The Item"
                    value="<?php echo $item['Name']; ?>" />
            </div>
        </div>
        <!-- End Name Field -->
        <!-- Start Description Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Description</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="description" class="form-control" required="required"
                    placeholder="Description of The Item" value="<?php echo $item['Description']; ?>" />
            </div>
        </div>
        <!-- End Description Field -->
        <!-- Start Price Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Price</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="price" class="form-control" required="required" placeholder="Price of The Item"
                    value="<?php echo $item['Price']; ?>" />
            </div>
        </div>
        <!-- End Price Field -->
        <!-- Start Country Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Country</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="country" class="form-control" required="required" placeholder="Country of Made"
                    value="<?php echo $item['Country_Made']; ?>" />
            </div>
        </div>
        <!-- End Country Field -->
        <!-- Start Status Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Status</label>
            <div class="col-sm-10 col-md-6">
                <select name="status">
                    <option value="1" <?php if ($item['Status'] == 1) {
                echo 'selected';
            } ?>>New</option>
                    <option value="2" <?php if ($item['Status'] == 2) {
                echo 'selected';
            } ?>>Like New</option>
                    <option value="3" <?php if ($item['Status'] == 3) {
                echo 'selected';
            } ?>>Used</option>
                    <option value="4" <?php if ($item['Status'] == 4) {
                echo 'selected';
            } ?>>Very Old</option>
                </select>
            </div>
        </div>
        <!-- End Status Field -->
        <!-- Start Members Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Member</label>
            <div class="col-sm-10 col-md-6">
                <select name="member">
                    <?php
                                        $allMembers = getAllFrom('*', 'users', '', '', 'UserID');
                                        foreach ($allMembers as $user) {
                                            echo "<option value='".$user['UserID']."'";
                                            if ($item['Member_ID'] == $user['UserID']) {
                                                echo 'selected';
                                            }
                                            echo '>'.$user['Username'].'</option>';
                                        }
                                    ?>
                </select>
            </div>
        </div>
        <!-- End Members Field -->
        <!-- Start Categories Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Category</label>
            <div class="col-sm-10 col-md-6">
                <select name="category">
                    <?php
                                        $allCats = getAllFrom('*', 'categories', 'where parent = 0', '', 'ID');
                                        foreach ($allCats as $cat) {
                                            echo "<option value='".$cat['ID']."'";
                                            if ($item['Cat_ID'] == $cat['ID']) {
                                                echo ' selected';
                                            }
                                            echo '>'.$cat['Name'].'</option>';
                                            $childCats = getAllFrom('*', 'categories', "where parent = {$cat['ID']}", '', 'ID');
                                            foreach ($childCats as $child) {
                                                echo "<option value='".$child['ID']."'";
                                                if ($item['Cat_ID'] == $child['ID']) {
                                                    echo ' selected';
                                                }
                                                echo '>--- '.$child['Name'].'</option>';
                                            }
                                        }
                                    ?>
                </select>
            </div>
        </div>
        <!-- End Categories Field -->
        <!-- Start Tags Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Tags</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="tags" class="form-control" placeholder="Separate Tags With Comma (,)"
                    value="<?php echo $item['tags']; ?>" />
            </div>
        </div>
        <!-- End Tags Field -->
        <!-- Start Submit Field -->
        <div class="form-group form-group-lg">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value="Save Item" class="btn btn-primary btn-sm" />
            </div>
        </div>
        <!-- End Submit Field -->
    </form>

    <?php
                    if (!empty($rows)) {
                        ?>
    <h1 class="text-center">Manage [ <?php echo $item['Name']; ?> ] Comments</h1>
    <div class="table-responsive">
        <table class="main-table text-center table table-bordered">
            <tr>
                <td>Comment</td>
                <td>User Name</td>
                <td>Added Date</td>
                <td>Control</td>
            </tr>
            <?php
                                foreach ($rows as $row) {
                                    echo '<tr>';
                                    echo '<td>'.$row['comment'].'</td>';
                                    echo '<td>'.$row['Member'].'</td>';
                                    echo '<td>'.$row['comment_date'].'</td>';
                                    echo "<td>
											<a href='comments.php?do=Edit&comid=".$row['c_id']."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
											<a href='comments.php?do=Delete&comid=".$row['c_id']."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                                    if ($row['status'] == 0) {
                                        echo "<a href='comments.php?do=Approve&comid=".$row['c_id']."' 
														class='btn btn-info activate'>
														<i class='fa fa-check'></i> Approve</a>";
                                    }
                                    echo '</td>';
                                    echo '</tr>';
                                } ?>
            <tr>
        </table>
    </div>
    <?php
                    } ?>
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
