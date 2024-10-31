<div class="wrap">
	<h2><?php echo $this->plugin->displayName; ?> &raquo; <?php esc_html_e( 'Install Pixel', 'notification-panda-install-pixel' ); ?></h2>

	<?php
	if ( isset( $this->message ) ) {
		?>
		<div class="updated fade"><p><?php echo $this->message; ?></p></div>
		<?php
	}
	if ( isset( $this->errorMessage ) ) {
		?>
		<div class="error fade"><p><?php echo $this->errorMessage; ?></p></div>
		<?php
	}
	?>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">
					<div class="postbox">
						<h3 class="hndle"><?php esc_html_e( 'Install Pixel Code', 'notification-panda-install-pixel' ); ?></h3>

						<div class="inside">
							<form action="admin.php?page=notification-panda-install-pixel" method="post">
								<p>
									<label for="np_insert_header"><strong><?php esc_html_e( 'Paste your Pixel Code below:', 'notification-panda-install-pixel' ); ?></strong></label>
									<textarea name="np_insert_header" id="np_insert_header" class="widefat" rows="8" style="font-family:Courier New;"><?php echo $this->settings['np_insert_header']; ?></textarea>
								</p>
								<?php wp_nonce_field( $this->plugin->name, $this->plugin->name . '_nonce' ); ?>
								<p>
									<input name="submit" type="submit" name="Submit" class="button button-primary" value="<?php esc_attr_e( 'Save', 'notification-panda-install-pixel' ); ?>" />
								</p>
							</form>
						Note: If you can't see your notification after save, please make sure about your cache cleared.
						</div>
					</div>
				</div>
			</div>

			<div id="postbox-container-1" class="postbox-container">
				<?php require_once( $this->plugin->folder . '/views/sidebar.php' ); ?>
			</div>
		</div>
	</div>
</div>
