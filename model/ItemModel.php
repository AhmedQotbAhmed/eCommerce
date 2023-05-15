<?php

    class ItemModel
    {
        private $con;

        public function __construct($con)
        {
            $this->con = $con;
        }

        public function getAllItems()
        {
            $query = '';

            if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
                $query = 'AND RegStatus = 0';
            }

            $stmt = $this->con->prepare('SELECT 
                    items.*, 
                    categories.Name AS category_name, 
                    users.Username 
                    FROM items
                    INNER JOIN 
                    categories ON categories.ID = items.Cat_ID 
                    INNER JOIN 
                        users 
                    ON 
                        users.UserID = items.Member_ID
                    ORDER BY 
                        Item_ID DESC');

            $stmt->execute();
            $rows = $stmt->fetchAll();

            return$rows;
        }

        public function insertItem($name, $desc, $price, $country, $status, $member, $cat, $tags)
        {
            // Get Variables From The Form

            $formErrors = $this->validateItemForm($name, $desc, $price, $country, $status, $member, $cat, $tags);

            // Loop Into Errors Array And Echo It
            if (!empty($formErrors)) {
                foreach ($formErrors as $error) {
                    echo '<div class="alert alert-danger">'.$error.'</div>';
                }
                $theMsg = '<div class="alert alert-danger">0</div>';

                redirectHome($theMsg, 'back');

                return false;
            }

            // Check If There's No Error Proceed The Update Operation

            // Insert Iteminfo In Database

            $stmt = $this->con->prepare('INSERT INTO 

						items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID, tags)

						VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember, :ztags)');

            $stmt->execute([
                        'zname' => $name,
                        'zdesc' => $desc,
                        'zprice' => $price,
                        'zcountry' => $country,
                        'zstatus' => $status,
                        'zcat' => $cat,
                        'zmember' => $member,
                        'ztags' => $tags,
                    ]);

            return $stmt;
        }

        public function validateItemForm($name, $desc, $price, $country, $status, $member, $cat, $tags)
        {
            $formErrors = [];
            // Validate The Form

            if (empty($name)) {
                $formErrors[] = 'Name Can\'t be <strong>Empty</strong>';
            }

            if (empty($desc)) {
                $formErrors[] = 'Description Can\'t be <strong>Empty</strong>';
            }

            if (empty($price)) {
                $formErrors[] = 'Price Can\'t be <strong>Empty</strong>';
            }

            if (empty($country)) {
                $formErrors[] = 'Country Can\'t be <strong>Empty</strong>';
            }

            if ($status == 0) {
                $formErrors[] = 'You Must Choose the <strong>Status</strong>';
            }

            if ($member == 0) {
                $formErrors[] = 'You Must Choose the <strong>Member</strong>';
            }

            if ($cat == 0) {
                $formErrors[] = 'You Must Choose the <strong>Category</strong>';
            }

            return $formErrors;
        }

        public function editItem($itemid)
        {
            // Select All Data Depend On This ID

            $stmt = $this->con->prepare('SELECT * FROM items WHERE Item_ID = ?');

            // Execute Query

            $stmt->execute([$itemid]);

            // Fetch The Data

            $item = $stmt->fetch();

            return $item;
        }

        public function updateItem($name, $desc, $price,
        $country, $status, $member, $cat, $tags, $id)
        {
            $formErrors = $this->validateItemForm($name, $desc, $price, $country, $status, $member, $cat, $tags);

            // Loop Into Errors Array And Echo It
            if (!empty($formErrors)) {
                foreach ($formErrors as $error) {
                    echo '<div class="alert alert-danger">'.$error.'</div>';
                }
                $theMsg = '<div class="alert alert-danger">0</div>';

                redirectHome($theMsg, 'back');

                return false;
            }

            // Update The Database With This Info

            $stmt = $this->con->prepare('UPDATE 
												items 
											SET 
												Name = ?, 
												Description = ?, 
												Price = ?, 
												Country_Made = ?,
												Status = ?,
												Cat_ID = ?,
												Member_ID = ?,
												tags = ?
											WHERE 
												Item_ID = ?');

            $stmt->execute([$name, $desc, $price, $country, $status, $cat, $member, $tags, $id]);

            return $stmt;
        }

        public function deleteItem($itemid)
        {
            // If There's Such ID Show The Form
            $stmt = $this->con->prepare('DELETE FROM items WHERE Item_ID = :zid');

            $stmt->bindParam(':zid', $itemid);

            $stmt->execute();

            return $stmt;
        }

        public function ApproveItem($itemid)
        {
            $stmt = $this->con->prepare('UPDATE items SET Approve = 1 WHERE Item_ID = ?');
            $stmt->execute([$itemid]);

            return $stmt;
        }
    }
