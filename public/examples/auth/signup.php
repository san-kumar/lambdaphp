<?php
// Note: This file can run directly on AWS Lambda using LambdaPHP //

$client = require_once __DIR__ . '/cognito.php';

include_once __DIR__ . '/../../static/header.html';

if (!empty($_POST['email'])) {
    if (!empty($_POST['signup'])) {
        //Visit  https://console.aws.amazon.com/cognito/signin/ to get your own UserPoolId and ClientId
        try {
            $result = $client->signUp([
                'ClientId' => '11g23pofph8tmc9sgriojro5v0',
                'Username' => $_POST['email'],
                'Password' => $_POST['password'],
                'UserAttributes' => [[
                    'Name' => 'email',
                    'Value' => $_POST['email'],
                ]],
            ]);

            header("Location: login.php?confirm=true&email=" . urlencode($_POST['email']));
        } catch (\Aws\Exception\AwsException $e) {
            printf('<pre><code>%s</code></pre>', $e->getAwsErrorMessage());
        }
    }
}
?>

<div id="container">
    <h3>Signup form</h3>

    <form method="POST" action="">
        Your email: <input type="email" name="email" required="" title="Email"/><br/>
        Password: <input type="password" name="password" title="Password" minlength="6"/><br/><br/>

        <input type="submit" name="signup" value="Signup!"/><br/><br/>
        <small><a href="login.php">Already registered?</a></small>
    </form>
</div>

<?php
include_once __DIR__ . '/../../static/footer.html';
?>
