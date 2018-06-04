<h1>Import CSV</h1>
<form method="POST" action="" enctype="multipart/form-data">
	Choose a file from your computer: (Maximum size: 32 MB) <input type="file" name="csv">
	<br/>
	<input type="submit" name="csv_upload" class="button button-primary button-large" value="Submit">
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a class="button button-primary button-large" href="<?php bloginfo('template_directory'); ?>/Global-Events.csv">Download sample CSV file</a>
</form>
<?php
if(isset($_POST['csv_upload'])) {
	$file1 = $_FILES['csv'];
	if (($file1["size"] < 4000000)){
		$date = date('Ymd');
		$time = time();
		$file_name = preg_replace("/[\s]+/", "", $file1['name']);
		$filename = $date.'_'.$time.'_'.($file_name);
		$upload_dir = wp_upload_dir();
		$uploaddir = $upload_dir['basedir'].'/csv/';
		$file = $uploaddir . $date.'_'.$time.'_'.($file_name);
		if (move_uploaded_file($file1['tmp_name'], $file)) {
			$csv = readCSV($file);
			//print_r($csv);
			$i = 0;
			if(!empty($csv)) {
				$k = 0;
				foreach ( $csv as $row ) {
					if ( $i != 0 ) {
						if ( ! empty( $row ) ) {
							$post           = array(
								'post_title'   => $row[0],
								'post_content' => $row[1],
								'post_type'    => 'global_event',
								'post_status'  => 'publish'
							);
							$new_event = wp_insert_post( $post );
							$newDate = date( "Y-m-d", strtotime( $row[3] ) );
							add_post_meta( $new_event, 'event_date', $newDate );
							add_post_meta( $new_event, 'type', $row[2] );
							$newTime = date( "g:i A", strtotime( $row[4] ) );
							add_post_meta( $new_event, 'event_time', $newTime );
							$category = get_term_by('name', $row[5], 'global_event_category');
							wp_set_post_terms( $new_event, array($category->term_id) , 'global_event_category' );
						}
					}
					$i++;
					$k++;
				}
			}
		}
	}
	echo '<p>Items imported successfully</p>';
}
?>