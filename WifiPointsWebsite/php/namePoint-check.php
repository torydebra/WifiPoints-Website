<?php
//vede se nome punto è disponibile

 require("connectDbLocalhost.php");

 if ($_SERVER["REQUEST_METHOD"] === "POST") {

	 $name = trim($_POST["name"]);
	 $name = htmlspecialchars($name);
	 mysqli_real_escape_string($conn,$name);

	 $preparedQuery = mysqli_prepare($conn, "SELECT 'nome_punto' FROM puntiWiFi WHERE nome_punto=?");
	 mysqli_stmt_bind_param($preparedQuery, 's', $name);

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
