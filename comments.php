<?php
	include 'databaseConnect.php';
	include 'functions.php';
	session_start();
	template();
	$photoID = numbersValidation($_GET['photoID']);
	
	//print the photo
	$connection = new mysqli($servername, $dbusername, $dbpassword, $dbname);
	$query = "SELECT photoID, username, photo_new_name, title FROM photos WHERE photoID = '" . $photoID . "'";
	if($result = mysqli_query($connection, $query)){
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				echo "<div class='action'><h3>" . str_replace("/","&#x2F",htmlspecialchars($row['title'])) . "</h3>
					<div class='user'>uploaded by: " . $row['username'] . "</div><br>
					<img src='meme/" . $row['photo_new_name'] . "' width='550'><br>
					<form action='' method='post' onsubmit='return commentValidation()'>
						<textarea id='comment' name='comment' value='' placeholder='Type your comment here...' required></textarea><div id='commentError'></div>
						<input type='hidden' id='photoID' name='photoID' value='" . $row["photoID"] . "'>
						<input type='submit' value='submit comment'>
					</form>";
			}
		}
	}
	
	//add a comment
	if(isset($_POST['comment'])){
		if(isset($_SESSION['username'])){
			if($connection->connect_error){
				header('Location: error.php');
				die();
			}else{
				$newComment = $connection->prepare("INSERT INTO comments (commentID, photoID, username, comment) VALUES (DEFAULT, ?, ?, ?)");
				$newComment->bind_param('sss', $photoID, $_SESSION['username'], $_POST['comment']);
				if($newComment->execute()){
					echo "Your comment have been submited successfully.<br><br>";
				}else{
					header('Location: error.php');
						die();
				}
			}
		}else{
			echo "You have to be sign in to write a comment.";
			echo '<form action="signIn.php">
					<input type="submit" value="sign in" />
			</form>
			<form action="signUp.php">
					<input type="submit" value="sign up" />
			</form>';
		}
	}
	
	//print comments
	if($connection->connect_error){
		header('Location: error.php');
		die();
	}else{
		$query = "SELECT username, comment FROM comments WHERE photoID='" . $photoID . "' ORDER BY commentID DESC;";
		if($result = mysqli_query($connection, $query)){
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					echo $row['username'] . " says: <br><div class='comment'>" . str_replace("/","&#x2F",htmlspecialchars($row['comment'])) . "</div><br>";
				}
			}else{
				echo "There are no comments yet.";
			}
		}else{
			header('Location: error.php');
			die();
		}
	}
	
	echo '</div></div>';
?>