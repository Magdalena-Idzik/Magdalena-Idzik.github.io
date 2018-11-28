<?php
	include 'functions.php';
	session_start();
	template();
	echo '<div class="action">';
	if(!isset($_SESSION['username'])){
		echo 'You have to log in to upload a picture.<br>';
		echo '<form action="signIn.php">
					<input type="submit" value="sign in" />
			</form>
			<form action="signUp.php">
					<input type="submit" value="sign up" />
			</form>';
	}else{
		echo '<form method="post" enctype="multipart/form-data" action="" onsubmit="return fileUploadValidation()">
				Write a title: <input type="text" name="title" id="title" value="title" maxlength="40" required><div class="error" id="fileError"></div>
				<br>Choose a file: 
				<input type="file" id="meme" name="meme" id="meme" required><div class="error" id="fileError"></div>
				<br><input type="submit" value="upload meme"/>
			</form>';
		if(isset($_POST['title'])){	
			$username = $_SESSION['username'];
			$error = 0;
			define('KB',1024);
			$accepted_extensions = array('jpg','jpeg','png','gif');
			
			$name = $_FILES['meme']['name'];
			$size = $_FILES['meme']['size'];
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$extension_mime = strtolower(substr(finfo_file($finfo, $_FILES['meme']['tmp_name']), strpos(finfo_file($finfo, $_FILES['meme']['tmp_name']),'/')+1));
			$extension = strtolower(substr($name, strpos($name,'.')+1));
				
			if(strlen($_POST['title']) > 40){
				echo "<div class='error'>The filename is too long. It should not exceed 40 characters.</div>";
				$error = 1;
			}
			
			if($size > 1500*KB){
				echo "<div class='error'> The file size is too big. It should not exceed 1500KB.</div>";
				$error = 1;
			}
			
			if(substr_count($name, ".") != 1 || !in_array($extension, $accepted_extensions) || !in_array($extension_mime,$accepted_extensions)){
				echo "<div class='error'>The filetype of an image is not accepted. Accepted filetypes are jpg, jpeg, png, gif.</div>";
				$error = 1;
			}
			
			if(!preg_match('/[a-zA-Z0-9]{1,90}\.[a-zA-Z]{3,4}/', $name)){
				echo "<div class='error'>The filename should contain only a-z, A-Z and _ .</div>";
				$error = 1;
			}
			
			if($error == 0){
				include 'databaseConnect.php';
				$title = $_POST["title"];
				$new_filename = microtime() . mt_rand() . '.' . $extension;
				move_uploaded_file($_FILES['meme']['tmp_name'], 'meme/' . $new_filename); 
				$connection = new mysqli($servername, $dbusername, $dbpassword, $dbname);
				if($connection->connect_error){
					header('Location: error.php');
					die();
				}else{
					$newPhoto = $connection->prepare("INSERT INTO photos (photoID, username, photo_name, photo_new_name, title) VALUES (DEFAULT, ?, ?, ?, ?)");
					$newPhoto->bind_param('ssss', $username, $name, $new_filename, $title);
					if($newPhoto->execute()){
						echo 'Your photo was uploaded successfully.<br>See it here...';
						echo '<form action="memeBase.php">
								<input type="submit" value="view memes" />
							</form>';
					}else{
						header('Location: error.php');
						die();
					}
				}
			}
		}		
	}			
    echo '</div></div>';
?>