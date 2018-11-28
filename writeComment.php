<?php
	include 'databaseConnect.php';
	include 'functions.php';
	session_start();
	template();
	if(isset($_SESSION['username'])){
		echo "<h3>" . $_POST['title'] . "</h3> uploaded by: " . $_POST['username'] . "<br>
			<img src='" . $_POST['photo_new_name'] . "' alt='" . $_POST['title'] . "' width='700'><br>
			<form action='' method='post' onsubmit='return commentValidation()'>
				<textarea id='comment' name='comment' value='' placeholder='Type your comment here...' rows='3' cols='90'></textarea><div id='commentError'></div>
				<input type='hidden' id='title' name='title' value='" . $_POST["title"] . "'>
				<input type='hidden' id='photo_new_name' name='photo_new_name' value='" . $_POST["photo_new_name"] . "'>
				<input type='hidden' id='photoID' name='photoID' value='" . $_POST["photoID"] . "'>
				<input type='hidden' id='username' name='username' value='" . $_POST["username"] . "'>
				<input type='submit' value='submit comment'>
			</form>";
		
		if(isset($_POST['comment'])){
			$connection = new mysqli($servername, $dbusername, $dbpassword, $dbname);
			if($connection->connect_error){
				die("Connection failed: " . $connection->connect_error);
			}else{
				$query = "INSERT INTO comments (commentID, photoID, username, comment) VALUES (DEFAULT, '" . $_POST['photoID'] . "','" . $_SESSION['username'] . "','" . $_POST['comment'] . "');";
				if(mysqli_query($connection, $query)){
						echo 'comment ok';
					}else{
						echo "Database error.";
					}
			}
		}
	}else{
		echo "You have to be sign in to write a comment.";
		echo '<form action="signIn.html">
					<input type="submit" value="sign in" />
			</form>
			<form action="signUp.html">
					<input type="submit" value="sign up" />
			</form>';
	}
	
	echo '</div>';
	
?>