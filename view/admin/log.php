
<div class="ogulo" id="ogulo_settings" style="background-image:url(<?php echo $args['image_path'].'/bg_01.svg';?>)">

	<div class="<?php echo ($args['key'] ? '': 'ogulo-hidden');?>">
		<div class="ogulo-block ogulo-tour-list">
			<div>
				<h2><?php _e('Logs','Logs');?></h2>
				
				<div>
				
				<?php
				
					if(!empty($_GET["reset"])) {
						global $wpdb;
						$wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` LIKE '%_transient_ogulo_access_%'" );
					}
					else
					{
						global $wpdb;
						$sql = "SELECT `option_name` AS `name`, `option_value` AS `value`
								FROM  $wpdb->options
								WHERE `option_name` LIKE '%_transient_ogulo_access_%'
								ORDER BY `option_name`";

						$results = $wpdb->get_results( $sql );
					}	
				
					if(!empty($results)) {
						echo '<table style="width:100%;">';
							echo '<tr>';
								echo '<td style="color: #07ADAD !important; padding:10px 20px; Font-weight:600; border-bottom: 1px solid #07ADAD !important;">Name</td>';
								echo '<td style="color: #07ADAD !important; padding:10px 20px; Font-weight:600; border-bottom: 1px solid #07ADAD !important;">Quelle</td>';
							echo '</tr>';
						
							foreach($results as $row) {
								echo "<tr>";
									echo '<td style="Padding:20px; text-align:left; width: 100%; max-width:300px;"><span style=" width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block;">'.home_url().'/ogulo_access/'.$row->name.'</span></td>';
									echo '<td style="Padding:20px; text-align:left; width: 100%; max-width:300px;">'.$row->value.'</td>';
								echo "</tr>";
							}
						echo "</table>";
					}
					else
					{
						echo "<p>Keine Einträge vorhanden.</p>";
					}	
						
					
					echo '<a style="text-transform: none; letter-spacing: normal; text-align: center;font-size: 1.1rem !important; font-weight: 400; display:inline-block; color: #fff; padding: 0 27px; text-decoration:none; height: 44px;line-height: 48px; position: relative;margin: 0px;box-shadow: none;border-radius: 40px !important;border: 0!important;min-width: 228px;background: #07ADAD !important;z-index: 2;-webkit-font-smoothing: auto;transition: all 0.4s ease;-moz-transition: all 0.4s ease;-webkit-transition: all 0.4s ease; margin-top:0px;" href="'.get_permalink().'admin.php?page=ogulo-360-tour-log&reset=all">Transition log leeren</a>';
				?>
				
					
				</div>
			</div>
		</div>

	</div>

</div>



