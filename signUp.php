<?php
	include 'functions.php';
	session_start();
	template();
	echo "<div class='action'>";
	if(isset($_SESSION['username'])){
		echo "<div class='error'>You have already signed up. <br>You are signed in as " . $_SESSION['username'] . ".</div>";
		echo "<form action='logOut.php'>
				<input type='submit' value='log out' />
			</form>";
	}else{
		if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['repassword'])){
			include 'databaseConnect.php';
			$connection = new mysqli($servername, $dbusername, $dbpassword, $dbname);
			$username = $_POST['username'];
			$password = $_POST['password'];
			$repassword = $_POST['repassword'];
			$error = 0;
			$allowedChars = '/[^a-zA-Z0-9_]/';
			if(preg_match($allowedChars, $username)){
				echo "<div class='error'>The username should only contains a-z, A-Z, 0-9 and _ .</div>";
				$error = 1;
			}else{
				$query = "SELECT username FROM users WHERE username = '" . $username . "';";
				if($connection->connect_error){
					header("Location: error.php");
						die();
				}else{
					if($result = mysqli_query($connection, $query)){
						if ($result->num_rows > 0){
							echo '<div class="error">This username is already taken. Choose another one.</div><br>';
							$error = 1;
						}
					}
				}
			}
			
			if( !(preg_match('/[a-z]/', $password) && preg_match('/[A-Z]/', $password) && preg_match('/[0-9]/', $password) )){
				echo "<div class='error'>The password should contain at least one small letter, one big letter and one number.</div>";
				$error = 1;
			}
			
			if(strlen($password) < 8 || strlen($password) > 70){
				echo "<div class='error'>The password should be 8 to 70 characters long.</div>";
				$error = 1;
			}
			
			if( !($password === $repassword) ){
				echo "<div class='error'>The passwords are not the same.</div>";
				$error = 1;
			}
			
			if($error == 0){
					if($connection->connect_error){
						header("Location: error.php");
						die();
					}else{
						$hash = password_hash($password, PASSWORD_BCRYPT);
						$newUser = "INSERT INTO users (userID, username, password) VALUES (DEFAULT,'" . $username . "','" . $hash . "')"; //pre cos tam queryes? po co one?
						if(mysqli_query($connection, $newUser)){
							echo 'You have signed up successfully. Sign in here...<br>';
							echo '<form action="signIn.php">
							<input type="submit" value="sign in" />
							</form><div>';
						}else{
							header("Location: error.php");
							die();
						}
					}
			}else{
				echo "<form action='signUp.php'>
					<input type='submit' value='Try again' />
				</form>";
			}
		}else{
			echo '<form action="" method="post" onsubmit="return signUpValidation()">
					<label for="username">username: </label>
					<input type="text" name="username" id="username" onblur="usernameValidation()" maxlength="30" required><br><div id="usernameError"></div>
					<label for="password">password: </label>
					<input type="password" name="password" id="password" onblur="passwordValidation()" maxlength="70" required><br><div id="passwordError"></div>
					<label for="repassword">repeat password: </label>
					<input type="password" name="repassword" id="repassword" onchange="repasswordValidation()" onblur="repasswordValidation()" maxlength="70" required><br><div id="repasswordError"></div> <!-- onblur vs onchange? -->
					<input type="submit" value="sign up">
				</form>';
		}
	}
	echo '</div></div>';
?>