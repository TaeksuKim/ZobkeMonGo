<?php
session_start();

include('redirect.inc');

if (!$_SESSION["userId"]) {
    Redirect('/poke/login_form.html', false);
} else {
    Redirect('/poke/list.php', false);
}
?>
