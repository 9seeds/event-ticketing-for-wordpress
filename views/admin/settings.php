settings

<?php 

foreach( $data['settings'] AS $inst ) {
    echo "<h2>{$inst['title']}</h2>";
    echo "<p>{$inst['text']}</p>";
}