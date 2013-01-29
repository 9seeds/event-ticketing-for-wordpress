<h2><?php _e('Instructions', 'wpet'); ?></h2>

<div id="tabs">
	<ul>
		<?php
		foreach ($data['tabs'] as $tab_id => $tab) {
			echo "<li><a href='#tab-{$tab_id}'>{$tab}</a></li>\n";
		}
		?>
	</ul>

	<?php
	foreach ($data['instructions'] as $tab_id => $instructions) {

		if (!in_array($tab_id, array_keys($data['tabs'])))
			continue;

		echo "<div id='tab-{$tab_id}'>";

		foreach ($instructions AS $set) {
//		    echo "<h2>{$set['title']}</h2>";
			echo $set['text'];
		}

		echo "</div>\n";
	}
	?>
</div><!-- #tabs -->
