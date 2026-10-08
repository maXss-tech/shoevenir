<?php
/* =====================================================
   LOGOUT PAGE
   Destroys user session and redirects to home
   ===================================================== */

require_once 'config.php';
require_once 'auth.php';

// Logout the user
logoutUser();

// Redirect to homepage
redirect('index.php');
?>
