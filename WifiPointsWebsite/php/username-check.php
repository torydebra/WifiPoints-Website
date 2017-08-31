<?php
//vede se username è disponibile

 require("connectDbLocalhost.php");

 if ($_SERVER["REQUEST_METHOD"] === "POST") {

	 $username = trim($_POST["username"]);
	 $username = htmlspecialchars($username);
	 mysqli_real_escape_string($conn,$username);

	 $preparedQuery = mysqli_prepare($conn, "SELECT 'username' FROM users WHERE username=?");
	 mysqli_stmt_bind_param($preparedQuery, 's', $username);

	 $result = mysqli_stmt_execute($preparedQuery);
	 mysqli_stmt_store_result($preparedQuery);
	 $count = mysqli_stmt_num_rows($preparedQuery);

	 mysqli_stmt_close($preparedQuery);
	 mysqli_close($conn);

	 if($count>0) {
    	echo ("taken");
	 } else {
		echo ("avaiable");
	 }
}
?>
