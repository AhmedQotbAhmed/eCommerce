<?php

	/*
	 Items Page
	*/

	ob_start(); // Output Buffering Start

	session_start();

	$pageTitle = 'Items';

	if (isset($_SESSION['Username'])) {

		include 'init.php';
 
		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage') {
            echo'wellcome'


		} elseif ($do == 'Add') {
 

		} elseif ($do == 'Insert') {


		} elseif ($do == 'Edit') {


		} elseif ($do == 'Update') {


		} elseif ($do == 'Delete') {


		} elseif ($do == 'Approve') {


		}

		include $tpl . 'footer.php';

	} else {

		header('Location: index.php');

		exit();
	}

	ob_end_flush(); // Release The Output

?>