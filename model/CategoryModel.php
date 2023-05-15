<?php

class CategoryModel
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    public function getAllCategories()
    {
        $query = '';

        if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
            $query = 'AND RegStatus = 0';
        }

        $sort = 'asc';

        $sort_array = ['asc', 'desc'];

        if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
            $sort = $_GET['sort'];
        }

        $stmt2 = $this->con->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY Ordering $sort");

        $stmt2->execute();

        $cats = $stmt2->fetchAll();
        $out = [$cats, $sort];

        return $out;
    }

    public function insertCategorie($name, $desc, $parent, $order, $visible, $comment, $ads)
    {
        // Insert Category Info In Database

        $stmt = $this->con->prepare('INSERT INTO 

						categories(Name, Description, parent, Ordering, Visibility, Allow_Comment, Allow_Ads)

					VALUES(:zname, :zdesc, :zparent, :zorder, :zvisible, :zcomment, :zads)');

        $stmt->execute([
                        'zname' => $name,
                        'zdesc' => $desc,
                        'zparent' => $parent,
                        'zorder' => $order,
                        'zvisible' => $visible,
                        'zcomment' => $comment,
                        'zads' => $ads,
                    ]);

        // Echo Success Message
        return $stmt;
    }

    public function editCategorie($catid)
    {
        // Check If Get Request catid Is Numeric & Get Its Integer Value

        $stmt = $this->con->prepare('SELECT * FROM categories WHERE ID = ?');

        $stmt->execute([$catid]);

        $cat = $stmt->fetch();

        return  $cat;
    }

    public function updateCategorie($id, $name, $desc, $order, $parent, $visible, $comment, $ads)
    {
        // Update The Database With This Info

        $stmt = $this->con->prepare('UPDATE 
											categories 
										SET 
											Name = ?, 
											Description = ?, 
											Ordering = ?, 
											parent = ?,
											Visibility = ?,
											Allow_Comment = ?,
											Allow_Ads = ? 
										WHERE 
											ID = ?');

        $stmt->execute([$name, $desc, $order, $parent, $visible, $comment, $ads, $id]);

        // Echo Success Message
        return $stmt;
    }

    public function deleteCategorie($catid)
    {
        // Select All Data Depend On This ID

        $stmt = $this->con->prepare('DELETE FROM categories WHERE ID = :zid');

        $stmt->bindParam(':zid', $catid);

        $stmt->execute();

        return $stmt;
    }
}
