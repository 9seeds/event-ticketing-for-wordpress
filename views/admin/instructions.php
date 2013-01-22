instructions



<?php 

foreach( $data['instructions'] AS $inst ) {
    echo "<h2>{$inst['title']}</h2>";
    echo "<p>{$inst['text']}</p>";
}