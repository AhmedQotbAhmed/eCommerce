<?php
class CategoryView
{
    public function editCategorieHTML($cat, $catid)
    {
        if (!empty($cat)) { ?>

<h1 class="text-center">Edit Category</h1>
<div class="container">
    <form class="form-horizontal" action="?do=Update" method="POST">
        <input type="hidden" name="catid" value="<?php echo $catid; ?>" />
        <!-- Start Name Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Name</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="name" class="form-control" required="required"
                    placeholder="Name Of The Category" value="<?php echo $cat['Name']; ?>" />
            </div>
        </div>
        <!-- End Name Field -->
        <!-- Start Description Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Description</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="description" class="form-control" placeholder="Describe The Category"
                    value="<?php echo $cat['Description']; ?>" />
            </div>
        </div>
        <!-- End Description Field -->
        <!-- Start Ordering Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Ordering</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="ordering" class="form-control" placeholder="Number To Arrange The Categories"
                    value="<?php echo $cat['Ordering']; ?>" />
            </div>
        </div>
        <!-- End Ordering Field -->
        <!-- Start Category Type -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Parent?</label>
            <div class="col-sm-10 col-md-6">
                <select name="parent">
                    <option value="0">None</option>
                    <?php
                                        $allCats = getAllFrom('*', 'categories', 'where parent = 0', '', 'ID', 'ASC');
                                        foreach ($allCats as $c) {
                                            echo "<option value='".$c['ID']."'";
                                            if ($cat['parent'] == $c['ID']) {
                                                echo ' selected';
                                            }
                                            echo '>'.$c['Name'].'</option>';
                                        }
                                    ?>
                </select>
            </div>
        </div>
        <!-- End Category Type -->
        <!-- Start Visibility Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Visible</label>
            <div class="col-sm-10 col-md-6">
                <div>
                    <input id="vis-yes" type="radio" name="visibility" value="0" <?php if ($cat['Visibility'] == 0) {
                                        echo 'checked';
                                    } ?> />
                    <label for="vis-yes">Yes</label>
                </div>
                <div>
                    <input id="vis-no" type="radio" name="visibility" value="1" <?php if ($cat['Visibility'] == 1) {
                                        echo 'checked';
                                    } ?> />
                    <label for="vis-no">No</label>
                </div>
            </div>
        </div>
        <!-- End Visibility Field -->
        <!-- Start Commenting Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Allow Commenting</label>
            <div class="col-sm-10 col-md-6">
                <div>
                    <input id="com-yes" type="radio" name="commenting" value="0" <?php if ($cat['Allow_Comment'] == 0) {
                                        echo 'checked';
                                    } ?> />
                    <label for="com-yes">Yes</label>
                </div>
                <div>
                    <input id="com-no" type="radio" name="commenting" value="1" <?php if ($cat['Allow_Comment'] == 1) {
                                        echo 'checked';
                                    } ?> />
                    <label for="com-no">No</label>
                </div>
            </div>
        </div>
        <!-- End Commenting Field -->
        <!-- Start Ads Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Allow Ads</label>
            <div class="col-sm-10 col-md-6">
                <div>
                    <input id="ads-yes" type="radio" name="ads" value="0" <?php if ($cat['Allow_Ads'] == 0) {
                                        echo 'checked';
                                    } ?> />
                    <label for="ads-yes">Yes</label>
                </div>
                <div>
                    <input id="ads-no" type="radio" name="ads" value="1" <?php if ($cat['Allow_Ads'] == 1) {
                                        echo 'checked';
                                    } ?> />
                    <label for="ads-no">No</label>
                </div>
            </div>
        </div>
        <!-- End Ads Field -->
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
            }
    }

    public function addCategorie()
 { ?>

<h1 class="text-center">Add New Category</h1>
<div class="container">
    <form class="form-horizontal" action="?do=Insert" method="POST">
        <!-- Start Name Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Name</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="name" class="form-control" autocomplete="off" required="required"
                    placeholder="Name Of The Category" />
            </div>
        </div>
        <!-- End Name Field -->
        <!-- Start Description Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Description</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="description" class="form-control" placeholder="Describe The Category" />
            </div>
        </div>
        <!-- End Description Field -->
        <!-- Start Ordering Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Ordering</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="ordering" class="form-control"
                    placeholder="Number To Arrange The Categories" />
            </div>
        </div>
        <!-- End Ordering Field -->
        <!-- Start Category Type -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Parent?</label>
            <div class="col-sm-10 col-md-6">
                <select name="parent">
                    <option value="0">None</option>
                    <?php
                                    $allCats = getAllFrom('*', 'categories', 'where parent = 0', '', 'ID', 'ASC');
                                    foreach ($allCats as $cat) {
                                        echo "<option value='".$cat['ID']."'>".$cat['Name'].'</option>';
                                    }
                                ?>
                </select>
            </div>
        </div>
        <!-- End Category Type -->
        <!-- Start Visibility Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Visible</label>
            <div class="col-sm-10 col-md-6">
                <div>
                    <input id="vis-yes" type="radio" name="visibility" value="0" checked />
                    <label for="vis-yes">Yes</label>
                </div>
                <div>
                    <input id="vis-no" type="radio" name="visibility" value="1" />
                    <label for="vis-no">No</label>
                </div>
            </div>
        </div>
        <!-- End Visibility Field -->
        <!-- Start Commenting Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Allow Commenting</label>
            <div class="col-sm-10 col-md-6">
                <div>
                    <input id="com-yes" type="radio" name="commenting" value="0" checked />
                    <label for="com-yes">Yes</label>
                </div>
                <div>
                    <input id="com-no" type="radio" name="commenting" value="1" />
                    <label for="com-no">No</label>
                </div>
            </div>
        </div>
        <!-- End Commenting Field -->
        <!-- Start Ads Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Allow Ads</label>
            <div class="col-sm-10 col-md-6">
                <div>
                    <input id="ads-yes" type="radio" name="ads" value="0" checked />
                    <label for="ads-yes">Yes</label>
                </div>
                <div>
                    <input id="ads-no" type="radio" name="ads" value="1" />
                    <label for="ads-no">No</label>
                </div>
            </div>
        </div>
        <!-- End Ads Field -->
        <!-- Start Submit Field -->
        <div class="form-group form-group-lg">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value="Add Category" class="btn btn-primary btn-lg" />
            </div>
        </div>
        <!-- End Submit Field -->
    </form>
</div>

<?php

        }

    public function manageCategoriesHTML($cats, $sort)
    {
        if (!empty($cats)) {
            ?>

<h1 class="text-center">Manage Categories</h1>
<div class="container categories">
    <div class="panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-edit"></i> Manage Categories
            <div class="option pull-right">
                <i class="fa fa-sort"></i> Ordering: [
                <a class="<?php if ($sort == 'asc') {
                echo 'active';
            } ?>" href="?sort=asc">Asc</a> |
                <a class="<?php if ($sort == 'desc') {
                echo 'active';
            } ?>" href="?sort=desc">Desc</a> ]
                <i class="fa fa-eye"></i> View: [
                <span class="active" data-view="full">Full</span> |
                <span data-view="classic">Classic</span> ]
            </div>
        </div>
        <div class="panel-body">
            <?php
                            foreach ($cats as $cat) {
                                echo "<div class='cat'>";
                                echo "<div class='hidden-buttons'>";
                                echo "<a href='categories.php?do=Edit&catid=".$cat['ID']."' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
                                echo "<a href='categories.php?do=Delete&catid=".$cat['ID']."' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete</a>";
                                echo '</div>';
                                echo '<h3>'.$cat['Name'].'</h3>';
                                echo "<div class='full-view'>";
                                echo '<p>';
                                if ($cat['Description'] == '') {
                                    echo 'This category has no description';
                                } else {
                                    echo $cat['Description'];
                                }
                                echo '</p>';
                                if ($cat['Visibility'] == 1) {
                                    echo '<span class="visibility cat-span"><i class="fa fa-eye"></i> Hidden</span>';
                                }
                                if ($cat['Allow_Comment'] == 1) {
                                    echo '<span class="commenting cat-span"><i class="fa fa-close"></i> Comment Disabled</span>';
                                }
                                if ($cat['Allow_Ads'] == 1) {
                                    echo '<span class="advertises cat-span"><i class="fa fa-close"></i> Ads Disabled</span>';
                                }
                                echo '</div>';

                                // Get Child Categories
                                $childCats = getAllFrom('*', 'categories', "where parent = {$cat['ID']}", '', 'ID', 'ASC');
                                if (!empty($childCats)) {
                                    echo "<h4 class='child-head'>Child Categories</h4>";
                                    echo "<ul class='list-unstyled child-cats'>";
                                    foreach ($childCats as $c) {
                                        echo "<li class='child-link'>
												<a href='categories.php?do=Edit&catid=".$c['ID']."'>".$c['Name']."</a>
												<a href='categories.php?do=Delete&catid=".$c['ID']."' class='show-delete confirm'> Delete</a>
											</li>";
                                    }
                                    echo '</ul>';
                                }

                                echo '</div>';
                                echo '<hr>';
                            } ?>
        </div>
    </div>
    <a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> Add New Category</a>
</div>

<?php
        } else {
            echo '<div class="container">';
            echo '<div class="nice-message">There\'s No Categories To Show</div>';
            echo '<a href="categories.php?do=Add" class="btn btn-primary">
							<i class="fa fa-plus"></i> New Category
						</a>';
            echo '</div>';
        } ?>

<?php
    }
}