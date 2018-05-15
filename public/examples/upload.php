<?php

include_once __DIR__ . '/../static/header.html';

/** @var \Aws\S3\S3Client $s3Client ($s3Client is provided by lambdaphp) */
$command = $s3Client->getCommand('PutObject', ['Bucket' => 'sanchit-uploads', 'Key' => 'last-upload.png']); //use your own bucket name here
$signedUrl = $s3Client->createPresignedRequest($command, '+30 minutes')->getUri();

?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <h3>Upload any JPG file</h3>

    <form enctype="multipart/form-data">
        <input name="file" type="file" id="file"/>
    </form>

    <script>
        let s3presignedUrl = '<?php echo $signedUrl ?>';

        $('#file').on('change', function () {
            var theFormFile = $('#file').get()[0].files[0];

            if (/jp/.test(theFormFile.type)) {
                $.ajax({
                    url: s3presignedUrl,
                    type: 'PUT',
                    contentType: 'image/png',
                    processData: false,
                    data: theFormFile,
                }).done(function () {
                    top.location = 'https://s3.amazonaws.com/sanchit-uploads/last-upload.png';
                }).fail(function () {
                    alert('failed :(');
                });
            } else {
                alert('ah ah ah ah only jpg files allowed!');
            }
        });
    </script>

<?php
include_once __DIR__ . '/../static/footer.html';
?>