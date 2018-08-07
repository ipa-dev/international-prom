<?php 
	global $wpdb;
	
	delete_option("wptgg_info") ;
	
	$table_arr=array($wpdb->prefix . "trigger", $wpdb->prefix . "trigger_history");

	foreach($table_arr as $table_name)
	{
	   $sql = "DROP TABLE ". $table_name;
		$wpdb->query($sql);
	}
	
?>