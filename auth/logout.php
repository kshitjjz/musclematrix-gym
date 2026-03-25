
<?php
session_start();
session_unset();
session_destroy();

// Clear cookies
setcookie("user_id", "", time() - 3600, "/");
setcookie("username", "", time() - 3600, "/");

header("Location: ../index.php");
exit();
?>
