<?php

/**
 * Generate a random string, using a cryptographically secure
 * pseudorandom number generator (random_int)
 *
 * For PHP 7, random_int is a PHP core function
 * For PHP 5.x, depends on https://github.com/paragonie/random_compat
 *
 * @param int $length      How many characters do we want?
 * @param string $keyspace A string of all possible characters
 *                         to select from
 * @return string
 */
function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1; //lunghezza $keyspace, 8bit è codifica della stringa
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}



$value = random_str(32); #work with php7

$hashedValue = password_hash($value, PASSWORD_BCRYPT);

require("connectDbLocalhost.php");
//tutti valori creati dal server sono sicuri
//id_user è inizializzato nelle funzioni chiamanti (actionlogin e actionregister) di questa
$preparedQuery = mysqli_prepare($conn, "INSERT INTO cookie VALUES (NULL, ?, ?)");
mysqli_stmt_bind_param($preparedQuery, 'ss', $id_user, $hashedValue);

mysqli_stmt_execute($preparedQuery);
mysqli_stmt_close($preparedQuery);
mysqli_close($conn);

$expires = time()+(60*60*24*30); //30 giorni
$path = "/"; //per rendere il cookie disponibile in tutto il sito
setcookie("logRemember", $value, $expires, $path);


?>
