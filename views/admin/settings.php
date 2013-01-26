<div class="wrap">
	<a href="http://9seeds.com/" target="_blank"><div id="seeds-icon"></div></a>
	<h2><?php _e('Event Settings', 'wpet'); ?></h2>

	<form action="" method="post">
	<div id="tabs">
	<ul>
		<?php
		foreach( $data['tabs'] as $tab_id => $tab ) {
			echo "<li><a href='#tab-{$tab_id}'>{$tab}</a></li>\n";
 		}
		?>
    </ul>

	<?php
	foreach( $data['settings'] as $tab_id => $settings ) {
	    
	    if( !in_array( $tab_id, array_keys ($data['tabs'] ) ) ) continue;
	    
		echo "<div id='tab-{$tab_id}'>";
		
		foreach( $settings AS $set ) {
		    echo "<h2>{$set['title']}</h2>";
		    echo $set['text'];
		}
		
		echo "</div>\n";
	}
	?>
	</div><!-- #tabs -->
	<p class="submit">
	 	<?php echo $data['nonce'] ?>
		<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save All Settings', 'wpet'); ?>" />
	</p>
	</form>
</div><!-- .wrap -->