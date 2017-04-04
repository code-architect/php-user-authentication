<?php

require_once('../includes/init.php');


Auth::getInstance()->logout();
redirect('login.php');

