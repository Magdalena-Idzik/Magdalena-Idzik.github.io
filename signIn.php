<?php
	include 'functions.php';
	session_start();
	template();
	echo "<div class='action'>";
	if(isset($_SESSION['username'])){
		echo "<div class='error'>You are already signed in as " . $_SESSION['username'] . ".</div>";
		echo "<form action='logOut.php'>
				<input type='submit' value='log out' />
			</form>";
	}else{
		if(isset($_POST['username']) && isset($_POST['password'])){
			include 'databaseConnect.php';
			$username = $_POST['username'];
			$password = $_POST['password'];
			$error = 0;
			$allowedChars = '/[^a-zA-Z0-9_]/';
			if(preg_match($allowedChars, $username)){
				$error = 1;
			}
			
			if( !(preg_match('/[a-z]/', $password) && preg_match('/[A-Z]/', $password) && preg_match('/[0-9]/', $password) )){
				$error = 1;
			}
			
			if(strlen($password) < 8 || strlen($password) > 70){
				$error = 1;
			}
			
			if($error == 0){
				$connection = new mysqli($servername, $dbusername, $dbpassword, $dbname);
				if($connection->connect_error){
					header('Location: error.php');
					die();
				}else{
					$getHash = "SELECT password FROM users WHERE username='" . $username . "';";
					$dbHash = $connection->query($getHash)->fetch_object()->password; 
					if($dbHash != null){
						if(password_verify($password, $dbHash)){
							session_start();
							session_unset();
							session_destroy();
							unset($_COOKIE['PHPSESSID']);
							setcookie('PHPSESSID','',time() - 3600);
							session_start();
							$_SESSION['username'] = $username;
							header('Location: memeBase.php');
							die();
						}else{
							echo "<div class='error'>Wrong username or password.</div>";
							echo "<form action='signIn.php'>
									<input type='submit' value='Try again' />
								</form>";
						}
					}else{
						echo "<div class='error'>Wrong username or password.</div>";
						echo "<form action='signIn.php'>
								<input type='submit' value='Try again' />
							</form>";
					}
				}
			}else{
				echo "<div class='error'>Wrong username or password.</div>";
				echo "<form action='signIn.php'>
						<input type='submit' value='Try again' />
					</form>";
			}
		}else{
			echo '<form action="" method="post" onsubmit="return signInValidation()">
					<div id="signInError"></div><br>
					<label for="username">username: </label>
					<input type="text" name="username" id="username" maxlength="30" required><br>
					<label for="password">password: </label>
					<input type="password" name="password" id="password" maxlength="70" required><br>
					<input type="submit" value="sign in">
				</form>';
		}	
	}
	echo '</div></div>';
?>