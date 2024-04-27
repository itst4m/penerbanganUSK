<?php
setcookie('id','', time()-3600);
setcookie('key', '', time()-3600);

header("Location: login.php");
exit;
?>