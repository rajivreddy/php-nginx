<?php 
$attachments = $this->get_attachments();
$count = count($attachments);
?>
<div class="wp-desman-storage dsman-media-import-existing">

<p>
This page is intended to help with migrating an existing wordpress site to the DeSMan system. No media should be included in the project repo, but there may be existing database records for uploaded media that need extra metadata in order to be served from DeSMan Object Storage. <strong>There are <?php echo $count; ?> media objects in the database which may not have the necessary metadata. </strong>Click the button below to perform a onetime batch operation to add the meta data to all objects.</p>

<form method="POST">
<?php wp_nonce_field( 'dsman-update-metanonce' ) ?>
<input type="hidden" name="action" value="update-metadata" />
<button type="submit" class="button button-primary"><?php echo __("Update all existing media"); ?></button>
</form>
</div>
