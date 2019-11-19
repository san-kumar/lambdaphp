<?php
// Note: This file can run directly on AWS Lambda using LambdaPHP //

session_start();

include_once __DIR__ . '/../static/header.html';

if (!empty($_POST)) {
    $_SESSION['user'] = $_POST;
}

if (!empty($_SESSION)) {
    printf('<pre>Session info: <code>%s</code></pre>', var_export($_SESSION, true));
}
?>

<form action="" method="POST">
    Name: <input type="text" name="name" required="required" title="name"/><br/>
    Email: <input type="email" name="email" required="required" title="email"/><br/><br/>
    <input type="submit" value="Save data in session!"/>
</form>


<?php
include_once __DIR__ . '/../static/footer.html';
?>

