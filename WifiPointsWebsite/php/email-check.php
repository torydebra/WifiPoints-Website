<?php
//vede se email già presa

 require("connectDbLocalhost.php");

 if ($_SERVER["REQUEST_METHOD"] === "POST") {

	 $email = trim($_POST["email"]);
	 $email = htmlspecialchars($email);
	 mysqli_real_escape_string($conn,$email);

	 $preparedQuery = mysqli_prepare($conn, "SELECT 'email' FROM users WHERE email=?");
	 mysqli_stmt_bind_param($preparedQuery, 's', $email);

	 $result = mysqli_stmt_execute($preparedQuery);
	 mysqli_stmt_store_result($preparedQuery);
	 $count = mysqli_stmt_num_rows($preparedQuery);

	 mysqli_stmt_close($preparedQuery);
	 mysqli_close($conn);

	 if($count>0) {
    	echo "taken";
	 } else {
		 echo "avaiable";
	 }
}
?>
