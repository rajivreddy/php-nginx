<div class="wrap aws-main">

	<h2><?php echo ( isset( $page_title ) ) ? $page_title : __( 'DeSMan&#0153; Connection Options', 'desman-connector' ); ?></h2>
	<?php if ( isset( $_GET['updated'] ) ) : ?>
		<div class="updated"><p><?php _e( 'Settings saved.', 'dsman' ); ?></p></div>
	<?php endif; ?>


	<div class="dsman-content dsman-settings">
        <?php if ( $this->are_key_constants_set() ) : ?>
	<?php
                $access_key = $this->get_option('id');
                $access_sec = $this->get_option('secret');
                $dbuser = getenv("DESMAN_MYSQL_DB_USERNAME");
                $dbpass = getenv("DESMAN_MYSQL_DB_PASSWORD");
                $dbname = getenv("DESMAN_MYSQL_DB_NAME");
                $dbhost = getenv("DESMAN_MYSQL_DB_HOST");
                $domain = WP_SITEURL;
	endif;
	# we only want to display this information for devel and stage
	if ( preg_match("/$baseurl/",$domain ) && preg_match("/(devel|stage)/",$domain) ): ?>

		<div class="form-control">
			<h3>MySQL Credential Information</h3>
			<form class="form">
				<div class="form-control-group">
				<label for="dbuser"><strong><?php echo __("Username: "); ?></strong>
					<input name="dbuser" class="disabled form-control" type="text" value="<?php echo $dbuser; ?>" size="50" disabled="disabled" />
				</label><br /><label for="dbpass"><strong><?php echo __("Password: "); ?></strong>
					<input name="dbpass" class="disabled form-control" type="text" value="<?php echo $dbpass; ?>" size="50" disabled="disabled" />
				</label><br /><label for="dbname"><strong><?php echo __("Database: "); ?></strong>
					<input name="dbname" class="disabled form-control" type="text" value="<?php echo $dbname; ?>" size="50" disabled="disabled" />
				</label><br /><label for="dbhost"><strong><?php echo __("Hostname: "); ?></strong>
					<input name="dbhost" class="disabled form-control" type="text" value="<?php echo $dbhost; ?>" size="50" disabled="disabled" />
				</label>
				</div>
			</form>
		</div>
		<div class="form-control">
			<h3>S3 Object Storage Access Keys</h3>
			<form class="form">
				<?php wp_nonce_field( 'dsman-save-settings' ) ?>
				<div class="form-control-group">
				<label for="s3_host"><strong><?php _e( 'S3 Base URL:', 'desman-connector' ); ?></strong>
					<input type="text" name="s3_host" value="<?php echo S3_BASE_URL; ?>" size="50" disabled="disabled" />
				</label><br /><label for "access_key_id"><strong><?php _e( 'Access Key ID:', 'desman-connector' ); ?></strong>
					<input type="text" name="access_key_id" value="<?php echo $this->get_option("id"); ?>" size="50" disabled="disabled" />
				</label><br /><label for="secret_access_key"><strong><?php _e( 'Secret Access Key:', 'desman-connector' ); ?></strong>
					<input type="text" name="secret_access_key" value="<?php echo $this->get_option("secret") ?: 'Not defined'; ?>" size="50" disabled="disabled" />
				</label>
				</div>
			</form>
		</div>

        <?php endif; ?>

	<?php
	$buckets = $this->getBuckets();

	if ( is_wp_error( $buckets ) ) :
		?>
		<div class="error">
			<p>
				<?php _e( 'Error retrieving a list of your S3 buckets from DeSMan&#0153 OBS:', 'dsman' ); ?>
				<?php echo $buckets->get_error_message(); ?>
			</p>
		</div>
		<?php
	endif;

	?>

	<div class="form-control">
	<form method="post">
	<input type="hidden" name="action" value="save" />
	<?php wp_nonce_field( 'dsman-save-settings' ) ?>

	<table class="form-table">
	<tr valign="top">
		<td>
			<h3><?php _e( 'S3 Bucket Settings', 'dsman' ); ?></h3>

			<select name="bucket" class="bucket">
			<option>-- <?php _e( 'Select an S3 Bucket', 'dsman' ); ?> --</option>
			<?php if ( is_array( $buckets ) ) foreach ( $buckets as $bucket ): ?>
			    <option value="<?php echo esc_attr( $bucket['Name'] ); ?>" <?php if ( $bucket['Name'] == $this->get_option( 'bucket' ) ) echo 'selected="selected"'; ?> ><?php echo esc_html( $bucket['Name'] ); ?>	</option>
			<?php endforeach;?>
			<option value="new"><?php _e( 'Create a new bucket...', 'dsman' ); ?></option>
			</select><br />

			<input type="checkbox" name="expiration-header" value="1" id="expiration-header" <?php echo $this->get_option( 'expiration-header' ) ? 'checked="checked" ' : ''; ?> />
			<label for="expires"> <?php printf( __( 'Set a <a href="%s" target="_blank">far future HTTP expiration header</a> for uploaded files <em>(recommended)</em>', 'dsman' ), 'http://developer.yahoo.com/performance/rules.html#expires' ); ?></label>
		</td>
	</tr>

	<tr valign="top">
		<td>
			<label><?php _e( 'Object Path:', 'dsman' ); ?></label>&nbsp;&nbsp;
			<input type="text" name="prefix" value="<?php echo esc_attr( $this->get_option( 'prefix' ) ); ?>" size="30" />
			<label><?php echo trailingslashit( $this->get_dynamic_prefix() ); ?></label>
		</td>
	</tr>

	<tr valign="top">
		<td>
			<h3><?php _e( 'Public (web) access settings', 'dsman' ); ?></h3>

			<label><?php _e( 'Domain Name', 'dsman' ); ?></label><br />
			<?php $cfurl = esc_attr( $this->get_option('cloudfront') ) ?: esc_attr( $this->get_option('bucket') ) .".". parse_url(S3_BASE_URL,PHP_URL_HOST); ?>
			<input type="text" name="bucket-url" value="<?php echo $cfurl; ?>" size="50" disabled="disabled" />
			<br />

			<input type="checkbox" name="object-versioning" value="1" id="object-versioning" <?php echo $this->get_option( 'object-versioning' ) ? 'checked="checked" ' : ''; ?> />
			<label for="object-versioning"> <?php printf( __( 'Implement <a href="%s">object versioning</a> by appending a timestamp to the S3 file path', 'dsman' ), 'http://docs.dsman.amazon.com/AmazonCloudFront/latest/DeveloperGuide/ReplacingObjects.html' ); ?></label>
		</td>
	</tr>

	<tr valign="top">
		<td>
			<h3><?php _e( 'Other Settings', 'dsman' ); ?></h3>

			<input type="checkbox" name="copy-to-s3" value="1" id="copy-to-s3" <?php echo $this->get_option( 'copy-to-s3' ) ? 'checked="checked" ' : ''; ?> />
			<label for="copy-to-s3"> <?php _e( 'Copy files to Object Storage as they are uploaded to the Media Library', 'dsman' ); ?></label>
			<br />

			<input type="checkbox" name="serve-from-s3" value="1" id="serve-from-s3" <?php echo $this->get_option( 'serve-from-s3' ) ? 'checked="checked" ' : ''; ?> />
			<label for="serve-from-s3"> <?php _e( 'Point file URLs to public OBS URLs for files that have been copied to Object Storage', 'dsman' ); ?></label>
			<br />

			<input type="checkbox" name="remove-local-file" value="1" id="remove-local-file" <?php echo $this->get_option( 'remove-local-copy' ) ? 'checked="checked" ' : ''; ?> />
			<label for="remove-local-file"> <?php _e( 'Remove uploaded file from local filesystem once it has been copied to Object Storage', 'dsman' ); ?></label>
			<br />

			<input type="checkbox" name="force-ssl" value="1" id="force-ssl" <?php echo $this->get_option( 'force-ssl' ) ? 'checked="checked" ' : ''; ?> />
			<label for="force-ssl"> <?php _e( 'Always serve files over https (SSL)', 'dsman' ); ?></label>

		</td>
	</tr>
	<tr valign="top">
		<td>
			<button type="submit" class="button button-primary"><?php _e( 'Save Changes', 'desman-connector' ); ?></button>
		</td>
	</tr>
	</table>

	</form>

	</div>

	</div>
</div>
