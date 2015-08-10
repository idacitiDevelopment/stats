<?php
$redirect = 'login.php';

/**
 * Begin removing their existence.
 *
 * Good bye friend :(. Promise you'll come back?!
 */
if ($login == 'TRUE') :
	session_unset();
	session_destroy();
endif;
header('Location: ' . $redirect);
exit();