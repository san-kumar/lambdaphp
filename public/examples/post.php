<?php
include_once __DIR__ . '/../static/header.html';

if (isset($_POST['data'])) {
    printf("You POST DATA:<br/><pre><code>%s</code></pre><hr/>", var_export($_POST, true));
}

?>

    <div id="container">
        <form method="POST" action="">
            <textarea name="data" placeholder="Paste some data here">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</textarea><br/>
            <input type="submit" value="Submit"/>
        </form>
    </div>

<?php
include_once __DIR__ . '/../static/footer.html';
?>