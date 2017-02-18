<?php

session_start();

session_destroy();

include('redirect.inc');
Redirect('/poke/login_form.html', false);
?>
