<?php

require_once 'fcu.php';

if (isset($_POST['contact'])) {
    sendmail($_POST['sender'], 'none', $_POST['message'], 'contact');
    errorRedirector('contact');
} else {
    errorRedirector('fox');
}