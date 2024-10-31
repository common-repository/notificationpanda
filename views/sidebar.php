<div class="postbox"><h3 class="hndle"><span><?php esc_html_e( 'Do you need support?', 'notification-panda' ); ?></span></h3>
<div class="inside">
		<p>
			<?php
			printf(
				esc_html__( 'Is something wrong? You can reach our support. %s for contact us.', 'notification-panda' ),
				sprintf(
					'<a href="https://notificationpanda.com/contact-us" target="_blank">%s</a>',
					esc_html__( 'Click here', 'notification-panda' )
				)
			);
			?>
		</p>
	</div>
</div>