<?php
$classes            = array( 'input-text' );
$allowed_mime_types = array_keys( ! empty( $field['allowed_mime_types'] ) ? $field['allowed_mime_types'] : get_allowed_mime_types() );
$field_name         = isset( $field['name'] ) ? $field['name'] : $key;
$field_name         .= ! empty( $field['multiple'] ) ? '[]' : '';
$classes[]          = "file-" . esc_attr( $key );

if ( ! empty( $field['ajax'] ) ) {
	wp_enqueue_script( 'wp-job-manager-ajax-file-upload' );
	wp_enqueue_script( 'jmfe-file-upload' );
	$classes[] = 'wp-job-manager-file-upload';
	$classes[] = "ajax-file-" . esc_attr( $key );
}
?>

<?php if( ! empty( $field['multiple'] ) && ! empty( $field['max_uploads'] ) ): ?>

	<script type="text/javascript">
		jQuery( function ($) {
			var max_uploads = <?php echo $field[ 'max_uploads' ]; ?>;
			var max_alert = "<?php printf( __( 'The max allowed files is %s', 'wp-job-manager-field-editor' ), $field[ 'max_uploads' ] ); ?>";
	<?php if( ! empty( $field['ajax'] ) ){ ?>
			$( '#<?php echo $key; ?>' )
					.bind( 'fileuploadchange', {alert: max_alert, max: max_uploads}, jmfe_upload.ajax.checkMax )
					.bind( 'fileuploaddrop', {alert: max_alert, max: max_uploads}, jmfe_upload.ajax.checkMax )
					.on( 'click', {alert: max_alert, max: max_uploads - 1}, jmfe_upload.ajax.checkMax );
	<?php } else { ?>
			$( '#<?php echo $key; ?>' ).on( 'change', {alert: max_alert, max: max_uploads}, jmfe_upload.checkMax );
	<?php } ?>
		});
	</script>

<?php endif; ?>

<div class="job-manager-uploaded-files">
	<?php if ( ! empty( $field['value'] ) ) :
		if ( isset( $field['value'][0] ) && is_array( $field['value'][0] )) : ?>
			<?php foreach ( $field['value'][0] as $value ) : ?>
				<?php get_job_manager_template( 'form-fields/uploaded-file-html.php', array( 'key' => $key, 'name' => 'current_' . $field_name, 'value' => $value, 'field' => $field ) ); ?>
			<?php endforeach;
		elseif ( is_array( $field['value'] ) ) : ?>
			<?php foreach ( $field['value'] as $value ) : ?>
				<?php get_job_manager_template( 'form-fields/uploaded-file-html.php', array( 'key' => $key, 'name' => 'current_' . $field_name, 'value' => $value, 'field' => $field ) ); ?>
			<?php endforeach; ?>
		<?php elseif ( $value = $field['value'] ) : ?>
			<?php get_job_manager_template( 'form-fields/uploaded-file-html.php', array( 'key' => $key, 'name' => 'current_' . $field_name, 'value' => $value, 'field' => $field ) ); ?>
		<?php endif; ?>
	<?php endif; ?>
</div>

<input type="file" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" data-file_types="<?php echo esc_attr( implode( '|', $allowed_mime_types ) ); ?>" <?php if ( ! empty( $field['multiple'] ) ) echo 'multiple'; ?> name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?><?php if ( ! empty( $field['multiple'] ) ) echo '[]'; ?>" id="<?php echo esc_attr( $key ); ?>" placeholder="<?php echo empty( $field['placeholder'] ) ? '' : esc_attr( $field['placeholder'] ); ?>" />
<small class="description">
	<?php if ( ! empty( $field['description'] ) ) : ?>
		<?php echo $field['description']; ?>
	<?php else : ?>
		<?php
			if( ! empty( $field[ 'multiple' ] ) && ! empty( $field[ 'max_uploads' ] ) ) {
				printf( __( 'Maximum files: %s.', 'wp-job-manager-field-editor' ), $field[ 'max_uploads' ] );
				echo "<br />";
			}
		?>
		<?php printf( __( 'Maximum file size: %s.', 'wp-job-manager-field-editor' ), size_format( wp_max_upload_size() ) ); ?>
	<?php endif; ?>
</small>