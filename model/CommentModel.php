<?php

class CommentModel
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    public function getAllComments()
    {
        $query = '';

        if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
            $query = 'AND RegStatus = 0';
        }

        $stmt = $this->con->prepare('SELECT 
										comments.*, items.Name AS Item_Name, users.Username AS Member  
									FROM 
										comments
									INNER JOIN 
										items 
									ON 
										items.Item_ID = comments.item_id
									INNER JOIN 
										users 
									ON 
										users.UserID = comments.user_id
									ORDER BY 
										c_id DESC');

        // Execute The Statement

        $stmt->execute();

        $comments = $stmt->fetchAll();

        return $comments;
    }

    public function editComment($comid)
    {
        $stmt = $this->con->prepare('SELECT * FROM comments WHERE c_id = ?');

        // Execute Query

        $stmt->execute([$comid]);

        // Fetch The Data

        $row = $stmt->fetch();

        // If There's Such ID Show The Form
        return $row;
    }

    public function updateComment($comid, $comment)
    {
        // Update The Database With This Info

        $stmt = $this->con->prepare('UPDATE comments SET comment = ? WHERE c_id = ?');

        $stmt->execute([$comment, $comid]);

        // Echo Success Message
        return $stmt;
    }

    public function deleteComment($comid)
    {
        $stmt = $this->con->prepare('DELETE FROM comments WHERE c_id = :zid');

        $stmt->bindParam(':zid', $comid);

        $stmt->execute();

        return $stmt;
    }

    public function approveComment($comid)
    {
        $stmt = $this->con->prepare('UPDATE comments SET status = 1 WHERE c_id = ?');

        $stmt->execute([$comid]);

        return $stmt;
    }
}
