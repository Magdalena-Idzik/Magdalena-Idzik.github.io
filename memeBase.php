<?php
	include 'databaseConnect.php';
	include 'functions.php';
	session_start();
	template();
	//decide which page to show
	isset($_GET['page']) ? $page = numbersValidation($_GET['page']) : $page = 1;
	$connection = new mysqli($servername, $dbusername, $dbpassword, $dbname);
	if($connection->connect_error){
		header("Location: error.php");
		die();
	}else{
		//fetch pictures
		$query = "SELECT photoID from photos ORDER BY photoID DESC LIMIT 1";
		$lastPhotoId = $connection->query($query)->fetch_object()->photoID; 
		$lastPhotoId <= 10 ? $whichPhoto = $lastPhotoId : $whichPhoto = $lastPhotoId - (($page-1) * 10);
		$fetchPhotos = "SELECT photoID, username, photo_new_name, title FROM photos WHERE photoID <= " . $whichPhoto . " ORDER BY photoID DESC LIMIT 10";
		
		//count the number of comments from fetched photos
		for($i = $whichPhoto; $i > $whichPhoto-10; $i--){
			$query = "SELECT photoID FROM comments WHERE photoID='" . $i . "';";
			$commentsCount = mysqli_query($connection, $query)->num_rows;
			$commentsArray[$i] = $commentsCount;
		}
		
		//print the photos
		if($result = mysqli_query($connection, $fetchPhotos)){
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					echo "<div class='obraz'>
						<h3>" . str_replace("/","&#x2F",htmlspecialchars($row['title'])) . "</h3>
						<div class='user'>uploaded by: " . $row["username"] . "</div><br>
						<img src='meme/" . $row['photo_new_name'] . "' width='550'><br>
						<div class='comments'>comments: " . $commentsArray[$row['photoID']] . "</div>
						<form action='comments.php' method='get'>
							<input type='hidden' id='photoID' name='photoID' value='" . $row["photoID"] . "'>
							<input type='submit' value='show comments' />
						</form><br></div>";
				}
			}
		}else{
			header("Location: error.php");
			die();
		}
	}
	
	//pagination
	if(!isset($_GET['page']) || numbersValidation($_GET['page'])==1){
		$page = 1;
		$lastPage = round($lastPhotoId/10);
		$lastPage%10 > 0 ? $lastPage++ : $lastPage = $lastPage;
		echo '<div class="center">
			<div class="pagination">
				<a href="memeBase.php?page=1" class="active">1</a>';
			if($lastPage < 7){	
				for($i=++$page; $i<=$lastPage; $i++){
					echo '<a href="memeBase.php?page=' . $i . '">' . $i . '</a>';
				}
			}else{
				for($i=++$page; $i<=5; $i++){
					echo '<a href="memeBase.php?page=' . $i . '">' . $i . '</a>';
				}
			}
		echo '<a href="memeBase.php?page=' . $lastPage . '">&raquo;</a></div></div>';
	}else{
		$page = numbersValidation($_GET['page']);
		$lastPage = round($lastPhotoId/10);
		$lastPage%10 > 0 ? $lastPage++ : $lastPage;
		echo '<div class="center">
			<div class="pagination">
				<a href="memeBase.php?page=1">&laquo;</a>
				<a href="memeBase.php?page=' . ($page-1) . '">&lsaquo;</a>
				<a href="memeBase.php?page=' . $page . '" class="active">' . $page . '</a>';
			if($lastPage < 7){	
				for($i=++$page; $i<=$lastPage; $i++){
					echo '<a href="memeBase.php?page=' . $i . '">' . $i . '</a>';
				}
			}else{
				for($i=$page; $i<=7-$page; $i++){
					echo '<a href="memeBase.php?page=' . $i . '">' . $i . '</a>';
				}
			}
		echo '<a href="memeBase.php?page=' . ($lastPage) . '">&raquo;</a></div></div>';
	}
	
	echo '</div>';
?>