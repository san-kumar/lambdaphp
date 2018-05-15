<?php
/** @var \Aws\CognitoIdentityProvider\CognitoIdentityProviderClient $client */
$client = require_once __DIR__ . '/cognito.php';
$clientId = '11g23pofph8tmc9sgriojro5v0'; //Visit  https://console.aws.amazon.com/cognito/signin/ to get your own UserPoolId and ClientId
$confirm = !empty($_GET['confirm']) ? 'Verification' : false;

include_once __DIR__ . '/../../static/header.html';

if (!empty($_POST['email'])) {
    try {
        if (($code = $_POST['code'] ?? '') && ($mode = $_POST['mode'] ?? '')) { //forgot password or new signup
            if ($mode == 'Verification') {
                $retryLogin = $client->confirmSignUp(['ClientId' => $clientId, 'ConfirmationCode' => $code, 'Username' => $_POST['email']]);
            } elseif ($mode == 'Password reset') {
                $retryLogin = $client->confirmForgotPassword(['ClientId' => $clientId, 'ConfirmationCode' => $code, 'Username' => $_POST['email'], 'Password' => $_POST['password']]);
            }
        }

        if (!empty($_POST['login']) || !empty($retryLogin)) {
            $result = $client->adminInitiateAuth([
                'AuthFlow' => 'ADMIN_NO_SRP_AUTH',
                'UserPoolId' => 'us-east-1_eFvaNKHEb',
                'ClientId' => $clientId,
                'AuthParameters' => [
                    'USERNAME' => $_POST['email'],
                    'PASSWORD' => $_POST['password'],
                ],
            ]);

            $accessToken = $result->get('AuthenticationResult')['AccessToken'];
            $_SESSION['user.accessToken'] = $accessToken;

            header("Location: index.php?success=true");
        } elseif (!empty($_POST['forgot'])) {
            $client->forgotPassword([
                'ClientId' => $clientId,
                'Username' => $_POST['email'],
            ]);

            $confirm = 'Password reset';
        }
    } catch (\Aws\Exception\AwsException $e) {
        if (preg_match('/(confirm|verif)/', $e->getAwsErrorMessage())) {
            try {
                $client->resendConfirmationCode(['ClientId' => $clientId, 'Username' => $_POST['email']]);
                $confirm = 'Verification';
            } catch (\Aws\Exception\AwsException $e) {
                $confirm = false;
            }
        }

        printf('<pre><code>%s</code></pre>', $e->getAwsErrorMessage());
    }
}

if (!empty($confirm)) {
    printf('<pre><code>Please enter the %s code sent to your email to continue..</code></pre>', $confirm);
}

?>

    <div id="container">
        <h3>Login form</h3>

        <form method="POST" action="login.php">
            Your email: <input type="email" name="email" required="" title="Email"
                               value="<?= $_REQUEST['email'] ?>"/><br/>
            <?= $confirm == 'Password reset' ? 'Create new' : 'Your' ?>
            Password: <input type="password" name="password" title="Password"/><br/>

            <?php if (!empty($confirm)) {
                printf('<b>%s code</b> (check email):', $confirm);
                printf('<input type="hidden" name="mode" value="%s" />', $confirm);
                printf('<input type="password" name="code" title="Code" required=""/><br/>');
            } ?>

            <br/>

            <input type="submit" name="login" value="Login!"/>
            <input type="submit" name="forgot" value="Forgot password?"/><br/><br/>
            <small><a href="signup.php">Need an account?</a></small>
        </form>
    </div>

<?php
include_once __DIR__ . '/../../static/footer.html';
?>