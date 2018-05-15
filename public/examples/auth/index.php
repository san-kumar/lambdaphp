<?php
$client = require_once __DIR__ . '/cognito.php';

include_once __DIR__ . '/../../static/header.html';

if (!empty($user)) {
    printf("<h3>Welcome logged in user!</h3><hr><pre><code>%s</pre></code><a href='logout.php'>Logout</a>", var_export($user, true));
} else {
    printf('<h3>Sorry, you are not logged in!</h3><hr><p><a href="login.php">Login</a> | <a href="signup.php">Signup</a></p>');
}

include_once __DIR__ . '/../../static/footer.html';
