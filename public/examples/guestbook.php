<?php
include_once __DIR__ . '/../static/header.html';

$bucket = 'sanchit-uploads';//change this to your own S3 bucket
$target = sprintf('s3://%s/guestbook.txt', $bucket);

if (isset($_POST['data'])) {
    file_put_contents($target, $_POST['data'] . "\n", FILE_APPEND);
}

?>

    <div id="container">
        <p>
            You can directly read and write data to your S3 buckets using <code>file_get_contents</code> and <code>file_put_contents</code> (instead of HDD).
        </p>

        <form method="POST" action="">
            <textarea name="data" placeholder="Paste some data here">Say hi!</textarea><br/>
            <input type="submit" value="Submit"/>
        </form>
    </div>

    <hr>

    <h3>What users are saying!</h3>

    <a href="https://s3.amazonaws.com/sanchit-uploads/guestbook.txt">Download all messages!</a>

<?php
include_once __DIR__ . '/../static/footer.html';
?>