
<div class="ogulo" id="ogulo_settings" style="background-image:url(<?php echo $args['image_path'].'/bg_01.svg';?>)">

	<div class="<?php echo ($args['key'] ? '': 'ogulo-hidden');?>">
		<div class="ogulo-block ogulo-tour-list">
			<div>
				<h2><?php _e('My Tours','ogulo-360-tour');?></h2>
				
				<div class="my_tours hidden">
					<img  class="" src="<?php echo $args['image_path'].'/loading.gif';?>">
				</div>
			</div>
		
	
			<h3><?php _e('How to embed Tours','ogulo-360-tour');?></h2>
			<p><?php _e('The following videos show how to use Ogulo tours in your Wordpress quickly and easily.','ogulo-360-tour');?></p>
			
			<div class="ogulo-howto">
				<a href="https://www.youtube.com/watch?v=Pq8ir1YOK1s&feature=youtu.be" target="_blank"  class="ogulo-help" style="background-size: cover; width: 30%;height: 182px;margin: 0;box-sizing: border-box;border: 0;display: block;border-radius: 20px;max-height: 353px;box-shadow: 0 0 20px 0 rgba(0,0,0,.1);background-image:url(<?php echo $args['image_path']; ?>/url.jpg)" ></a>
				<a href="https://www.youtube.com/watch?v=KfP3eOWocsc&feature=youtu.be" target="_blank"  class="ogulo-help" style="background-size: cover; width: 30%;height: 182px;margin: 0;box-sizing: border-box;border: 0;display: block;border-radius: 20px;max-height: 353px;box-shadow: 0 0 20px 0 rgba(0,0,0,.1);background-image:url(<?php echo $args['image_path']; ?>/widget.jpg)" ></a>	
				<a href="https://www.youtube.com/watch?v=OsMTVtAveqk&feature=youtu.be" target="_blank"  class="ogulo-help" style="background-size: cover; width: 30%;height: 182px;margin: 0;box-sizing: border-box;border: 0;display: block;border-radius: 20px;max-height: 353px;box-shadow: 0 0 20px 0 rgba(0,0,0,.1);background-image:url(<?php echo $args['image_path']; ?>/shortcode.jpg)" ></a>
			</div>
			
		</div>

	</div>
			
	
	
	<div style="min-height: 70vh; margin-top:10vh;">
		<div class="ogulo-block ogulo-activition">
			<div class="ogulo-key">
				<h2><?php _e('Activation','ogulo-360-tour');?></h2>
				<div>
					<p><?php _e('Please enter your Ogulo API key to activate the plugin and connect it to your ogulo alpha account.','ogulo-360-tour');?></p>
					<div class="licence_field">
						<input type="password" value="<?php echo ($args['key'] ?: '');?>" placeholder="<?php _e('Your API key','ogulo-360-tour');?>">
						<img class="hidden" style="position: absolute; height: 35px; margin: 2px 0 0 6px;" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNTEyLjAwMSA1MTIuMDAxIiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA1MTIuMDAxIDUxMi4wMDE7IiB4bWw6c3BhY2U9InByZXNlcnZlIiB3aWR0aD0iNTEyIiBoZWlnaHQ9IjUxMiI+PGc+PGc+Cgk8Zz4KCQk8cGF0aCBkPSJNNTAzLjgzOSwzOTUuMzc5bC0xOTUuNy0zMzguOTYyQzI5Ny4yNTcsMzcuNTY5LDI3Ny43NjYsMjYuMzE1LDI1NiwyNi4zMTVjLTIxLjc2NSwwLTQxLjI1NywxMS4yNTQtNTIuMTM5LDMwLjEwMiAgICBMOC4xNjIsMzk1LjM3OGMtMTAuODgzLDE4Ljg1LTEwLjg4Myw0MS4zNTYsMCw2MC4yMDVjMTAuODgzLDE4Ljg0OSwzMC4zNzMsMzAuMTAyLDUyLjEzOSwzMC4xMDJoMzkxLjM5OCAgICBjMjEuNzY1LDAsNDEuMjU2LTExLjI1NCw1Mi4xNC0zMC4xMDFDNTE0LjcyMiw0MzYuNzM0LDUxNC43MjIsNDE0LjIyOCw1MDMuODM5LDM5NS4zNzl6IE00NzcuODYxLDQ0MC41ODYgICAgYy01LjQ2MSw5LjQ1OC0xNS4yNDEsMTUuMTA0LTI2LjE2MiwxNS4xMDRINjAuMzAxYy0xMC45MjIsMC0yMC43MDItNS42NDYtMjYuMTYyLTE1LjEwNGMtNS40Ni05LjQ1OC01LjQ2LTIwLjc1LDAtMzAuMjA4ICAgIEwyMjkuODQsNzEuNDE2YzUuNDYtOS40NTgsMTUuMjQtMTUuMTA0LDI2LjE2MS0xNS4xMDRjMTAuOTIsMCwyMC43MDEsNS42NDYsMjYuMTYxLDE1LjEwNGwxOTUuNywzMzguOTYyICAgIEM0ODMuMzIxLDQxOS44MzYsNDgzLjMyMSw0MzEuMTI4LDQ3Ny44NjEsNDQwLjU4NnoiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDAiIGNsYXNzPSJhY3RpdmUtcGF0aCIgc3R5bGU9ImZpbGw6IzA3QURBRCIgZGF0YS1vbGRfY29sb3I9IiMwMDAwMDAiPjwvcGF0aD4KCTwvZz4KPC9nPjxnPgoJPGc+CgkJPHJlY3QgeD0iMjQxLjAwMSIgeT0iMTc2LjAxIiB3aWR0aD0iMjkuOTk2IiBoZWlnaHQ9IjE0OS45ODIiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDAiIGNsYXNzPSJhY3RpdmUtcGF0aCIgc3R5bGU9ImZpbGw6IzA3QURBRCIgZGF0YS1vbGRfY29sb3I9IiMwMDAwMDAiPjwvcmVjdD4KCTwvZz4KPC9nPjxnPgoJPGc+CgkJPHBhdGggZD0iTTI1NiwzNTUuOTljLTExLjAyNywwLTE5Ljk5OCw4Ljk3MS0xOS45OTgsMTkuOTk4czguOTcxLDE5Ljk5OCwxOS45OTgsMTkuOTk4YzExLjAyNiwwLDE5Ljk5OC04Ljk3MSwxOS45OTgtMTkuOTk4ICAgIFMyNjcuMDI3LDM1NS45OSwyNTYsMzU1Ljk5eiIgZGF0YS1vcmlnaW5hbD0iIzAwMDAwMCIgY2xhc3M9ImFjdGl2ZS1wYXRoIiBzdHlsZT0iZmlsbDojMDdBREFEIiBkYXRhLW9sZF9jb2xvcj0iIzAwMDAwMCI+PC9wYXRoPgoJPC9nPgo8L2c+PC9nPiA8L3N2Zz4=" />
						<span class="ogulo_verified dashicons dashicons-yes <?php echo ($args['key'] ? '': 'hidden');?>" style="font-size: 43px;color: green;"></span>
					</div>
					<div style="margin-top: 20px;">
						<button id="" class="button button-large button-primary activate_ogulo button-ogulo"><?php _e('Activate','ogulo-360-tour');?></button>
						<a id="" class="button-ogulo hidden " disabled="disabled"><?php _e('Wait...','ogulo-360-tour');?></a>
					</div>
				</div>
			</div>
		
			<div class="ogulo-help">
				<a href="https://www.youtube.com/watch?v=vTCeF2rCFg8&feature=youtu.be" target="_blank"  class="ogulo-help" style="background-size: cover;width: 100%;height: 110%;margin: 0;box-sizing: border-box;border: 0;display: block;border-radius: 20px;max-height: 353px;box-shadow: 0 0 20px 0 rgba(0,0,0,.1);background-image:url(<?php echo $args['image_path']; ?>/activate.jpg)" ></a>
			</div>
		</div>
		
		<div class="ogulo-block ogulo-notice notice hidden">
			<div>
				<a class="clear"><span class="dashicons dashicons-dismiss"></span></a>
				<h3><?php _e('Important Notice','ogulo-360-tour');?></h3>
				<p><?php _e('Here follows a text and pictures as well as a button to order the subdoman extension if the customer does not have this.','ogulo-360-tour');?></p>
			</div>
		</div>
		
	<div>
</div>

