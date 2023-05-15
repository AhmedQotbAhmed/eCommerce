<?php

class MembersModel
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    public function getUserID()
    {
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        return $userid;
    }

    public function getAllMembers()
    {
        $query = '';

        if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
            $query = 'AND RegStatus = 0';
        }

        $stmt = $this->con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll();

        return $rows;
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

        return $errors;
    }

    // Insert Member Function
    public function insertMember($username, $password, $email, $fullName, $avatar)
    {
        $formErrors = $this->validateMemberForm($username, $password, $email, $fullName, $avatar);

        // Check if there are any errors
        if (!empty($formErrors)) {
            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">'.$error.'</div>';
            }
            $theMsg = "<div class='alert alert-danger'>".'0'.' Record Inserted</div>';
            redirectHome($theMsg, 'back');

            return false; // Return false to indicate an error occurred
        }

        // Encrypt the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the member data into the database
        $stmt = $this->con->prepare('INSERT INTO users(Username, Password, Email, FullName, Avatar, RegStatus, Date) 
                        VALUES (?, ?, ?, ?, ?, 0, now())');
        $stmt->execute([$username, $hashedPassword, $email, $fullName, $avatar]);

        // Check if the insertion was successful
        return $stmt;
    }

    // Edit Member Function
    public function editMember($userid)
    {
        $stmt = $this->con->prepare('SELECT * FROM users WHERE UserID = ? LIMIT 1');
        $stmt->execute([$userid]);
        $row = $stmt->fetch();

        return $row;
    }

    // Update Member Function
    public function updateMember($userID, $username, $email, $fullName, $password)
    {
        $stmt = $this->con->prepare('UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?');

        $stmt->execute([$username, $email, $fullName, $password, $userID]);

        return $stmt;
    }

    // Delete Member Function
    public function deleteMember($userID)
    {
        $stmt = $this->con->prepare('DELETE FROM users WHERE UserID = ?');
        $stmt->execute([$userID]);

        return   $stmt;
    }

    // Activate Member Function
    public function activateMember($userID)
    {
        $stmt = $this->con->prepare('UPDATE users SET RegStatus = 1 WHERE UserID = ?');
        $stmt->execute([$userID]);

        return   $stmt;
    }
}
