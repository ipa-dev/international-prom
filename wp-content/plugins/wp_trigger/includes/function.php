<?php



if( !empty($_POST["hi_trigger_info"]) ) { wptggBackEnd::trigger_save(); }



if( !empty($_GET["page"]) && !empty($_GET["delete"])) {
	if( $_GET["page"] == "wp-trigger" && $_GET["delete"] ) { wptggBackEnd::trigger_delete() ; }
}

if( !empty($_GET["page"]) && !empty($_GET["duplicate"])) {
	if( $_GET["page"] == "wp-trigger" && $_GET["duplicate"] ) { wptggBackEnd::trigger_duplicate(); }
}


if( !empty($_POST["trigger_import"])) {
	if( $_POST["trigger_import"] ) { wptggBackEnd::trigger_import(); }
}



/*************************************/



/*      Trigger basic function       */



/*************************************/ 



class wptggAction


{


	function insert_to_table($table, $data)


	{


		global $wpdb;


		$result = $wpdb->insert($table, $data);


		if( $result ){	


			$row = $wpdb->get_row("SELECT max(ID) as maxid FROM $table");	



			if($row)


				return $row->maxid ;


			else


				return false;


		}else{


			return false;


		}


	}	



	static function get_trigger($param = null, $getform = "rows")


	{


		global $wpdb;


		$chgdata = array(


						"name" => "box_name",


						"date" => "create_datetime"


					) ;


		
		//$orderby = ( $_GET['orderby'] ) ? " ORDER BY {$chgdata[$_GET['orderby']]} {$_GET['order']}" : "  ORDER BY ID" ;


		
		if($param["ID"])


			$result = $wpdb->get_row( "SELECT * FROM " . WPTGG_TABLE . " WHERE ID = {$param["ID"]}" );


		else 


			$result = $wpdb->get_results( "SELECT * FROM " . WPTGG_TABLE . " ORDER BY ID" );
		return $result;


	}
	
	static function get_trigger_history($param=null, $getform='')
	{
		global $wpdb;
		$w = (!empty($param["ID"])) ? " A.ID = " . $param["ID"] : 1 ;
		$w .= (!empty($param["trigger"])) ? " AND A.trigger = '" . $param["trigger"] ."'" : "" ;
		$w .= (!empty($param["valid_status"])) ? " AND A.valid_status = '" . $param["valid_status"] ."'" : "" ;
		$w .= (!empty($param["trigger_box_id"])) ? " AND A.trigger_box_id = '" . $param["trigger_box_id"] ."'" : "" ;
		$w .= (!empty($param["date_range_start"])) ? " AND DATE(A.create_datetime) >= DATE('" . $param["date_range_start"] ."')" : "" ;
		$w .= (!empty($param["date_range_end"])) ? " AND DATE(A.create_datetime) <= DATE('" . $param["date_range_end"] ."')" : "" ;
						
		$orderby = "ORDER BY A.ID desc" ;
		
		if( !empty($param["orderby"]) ){
			$order = ($param["order"]) ? $param["order"] : "asc" ;  
			$orderby = ($param["orderby"] == "box_name") ? "ORDER BY B.{$param["orderby"]} {$order}" : "ORDER BY A.{$param["orderby"]} {$order}" ;
		}
		
		if(!empty($param["ID"]))$getform = "row" ;
		
		$sql = "SELECT A.*, B.box_name as box_name FROM " . WPTGG_HISTORY_TABLE . " AS A LEFT JOIN " . WPTGG_TABLE . " AS B ON A.trigger_box_id = B.ID WHERE {$w} {$orderby}" ; 
		
		if($getform == "row")
			$result = $wpdb->get_row( $sql );
		else 
			$result = $wpdb->get_results( $sql );
					
		return $result;
	}
	
	static function get_hitory_min_date($param)
	{
		global $wpdb ;
		$w = (!empty($param["ID"])) ? " A.ID = " . $param["ID"] : 1 ;
		$w .= (!empty($param["trigger_box_id"])) ? " AND trigger_box_id = " . $param["trigger_box_id"] : "" ;
		$row = $wpdb->get_row("SELECT min(create_datetime) as min_date FROM " . WPTGG_HISTORY_TABLE . " WHERE {$w}") ;
		if( $row )return $row->min_date ;
		else return "" ;
	}



}







class wptggBackEnd extends wptggAction



{

	
	


	static function trigger_save()



	{



		$chked = ($_POST["hide_trigger_chk"]) ? $_POST["hide_trigger_chk"] : "unchecked" ;
		$only_number_trigger_chked = ($_POST["only_number_trigger_chk"]) ? $_POST["only_number_trigger_chk"] : "unchecked" ;
		$scroll_to_trigger_chked = ($_POST["scroll_to_trigger_chk"]) ? $_POST["scroll_to_trigger_chk"] : "unchecked" ;
		$shake_trigger_chked = ($_POST["shake_trigger_chk"]) ? $_POST["shake_trigger_chk"] : "unchecked" ;


		$data = array(



					"box_name" => $_POST["triggerbox_name"],



					"box_info" => stripcslashes($_POST["hi_trigger_info"]),



					"no_found" => stripcslashes($_POST["hi_no_found_data"]),
					"placeholder" => stripcslashes($_POST["placeholder"]),
                                        "show_chk" => $chked,
                                        "only_number_chk" => $only_number_trigger_chked,
                                        "scroll_to_chk" => $scroll_to_trigger_chked,
                                        "shake_chk" => $shake_trigger_chked,
                                        "btnactivate" => $_POST["hi_button_info"],
                                        "placement" => $_POST["hi_button_placement"],
                                        "apply_custom_styles" => ($_POST["apply_custom_styles"] ? $_POST["apply_custom_styles"] : ""),
                                        "box_custom_style" => stripcslashes($_POST["hi_box_custom_style"]),
                                        "btn_value" => isset($_POST["btnPlace"]) ? $_POST["btnPlace"] : '',
                                        "apply_button_custom_style" => $_POST["btnCustomStyle"],
                                        "option" => stripcslashes($_POST["hi_button_option"])
					) ;



		
                

		$wptrigger_id = 0;

		if( $_POST["hi_trigger_id"] ){
                    
                        global $wpdb ;
			$wpdb->update(WPTGG_TABLE, $data, array("ID" => $_POST["hi_trigger_id"])) ;

			$wptrigger_id = $_POST["hi_trigger_id"];

			// Message to admin about succesfully saving
			wptggBackEnd::wp_trigger_admin_message('updated', 'Your settings have been saved. To embed this Trigger Box use the shortcode [wptrigger id='.$wptrigger_id.']');

		}else{



			$data["create_datetime"] = current_time("mysql") ;
                        $wptrigger_id = wptggBackEnd::insert_to_table(WPTGG_TABLE, $data) ;

			// Message to admin about succesfully saving
			//wptggBackEnd::wp_trigger_admin_message('updated', 'Trigger Box have been created. To embed this Trigger Box use the shortcode [wptrigger id='.$wptrigger_id.']');

			wp_redirect(admin_url("admin.php?page=wp-trigger&trigger_id=".$wptrigger_id."&wp-trigger-status=just_created")) ;
                 }




		//wp_redirect(admin_url("admin.php?page=wp-trigger")) ;



		//exit() ;



	}


	static function trigger_import() {
		global $wpdb;

		$file_import_error = "";

		switch ($_FILES['trigger_import_file']['error']) {
			case UPLOAD_ERR_OK:
				break;
			case UPLOAD_ERR_NO_FILE:
				$file_import_error = __('No file sent.');
				break;
			case UPLOAD_ERR_INI_SIZE:
				break;
			case UPLOAD_ERR_FORM_SIZE:
				$file_import_error = __('Exceeded filesize limit.');
				break;
			default:
				break;
		}
		$filepath = $_FILES["trigger_import_file"]["tmp_name"];

		if($file_import_error == "") {
			$file_import_data = file_get_contents($filepath);
			$file_import_data = json_decode($file_import_data);

			$file_import_box_name = $file_import_data->box_name;


			// Check if trigger with the same name is exists

			$file_import_searched_triggers_count = count($wpdb->get_results( "SELECT * FROM " . WPTGG_TABLE . " WHERE UPPER(box_name) = '".$file_import_box_name."'" ));

			if($file_import_searched_triggers_count > 0) {
				$file_import_box_name .= " COPY";

				$file_import_searched_triggers_count = count($wpdb->get_results( "SELECT * FROM " . WPTGG_TABLE . " WHERE UPPER(box_name) = '".$file_import_box_name."'" ));

				if($file_import_searched_triggers_count > 0) {
					$file_import_copy_count = 1;
					$file_import_box_name_additional = "";

					while($file_import_searched_triggers_count > 0) {
						$file_import_searched_triggers_count = count($wpdb->get_results( "SELECT * FROM " . WPTGG_TABLE . " WHERE UPPER(box_name) = '".$file_import_box_name." ".$file_import_copy_count."'" ));

						$file_import_box_name_additional = $file_import_box_name." ".$file_import_copy_count;

						$file_import_copy_count++;
					}

					if($file_import_box_name_additional != "") {
						$file_import_box_name = $file_import_box_name_additional;
					}
				}
			}


			$data = array(
				"box_name" => $file_import_box_name,
				"box_info" => $file_import_data->box_info,
				"no_found" => $file_import_data->no_found,
				"placeholder" => $file_import_data->placeholder,
				"show_chk" => $file_import_data->show_chk,
				"only_number_chk" => $file_import_data->only_number_chk,
				"scroll_to_chk" => $file_import_data->scroll_to_chk,
				"shake_chk" => $file_import_data->shake_chk,
				"btnactivate" => $file_import_data->btnactivate,
				"placement" => $file_import_data->placement,
				"apply_custom_styles" => $file_import_data->apply_custom_styles,
				"box_custom_style" => $file_import_data->box_custom_style,
				"btn_value" => $file_import_data->btn_value,
				"apply_button_custom_style" => $file_import_data->apply_button_custom_style,
				"option" => $file_import_data->option
			);

			$data["create_datetime"] = current_time("mysql");
                        wptggBackEnd::insert_to_table(WPTGG_TABLE, $data);



			wp_redirect(admin_url("admin.php?page=wp-trigger"));

			exit();
		} else {
			echo "<script type='text/javascript'>";
			echo "var file_import_error = '".$file_import_error."';";
			echo "</script>";
		}
	}

	static function trigger_duplicate() {
		global $wpdb;

		$duplicated_trigger_id = $_GET["duplicate"];


		if($duplicated_trigger_id) {
			$duplicated_trigger = $wpdb->get_results( "SELECT * FROM " . WPTGG_TABLE . " WHERE ID = ".$duplicated_trigger_id );

			$duplicated_trigger_box_name = $duplicated_trigger[0]->box_name;

                        
			// Check if trigger with the same name is exists

			$duplicated_searched_triggers_count = 1;

			if($duplicated_searched_triggers_count > 0) {
				$duplicated_trigger_box_name .= " COPY";

				$duplicated_searched_triggers_count = count($wpdb->get_results( "SELECT * FROM " . WPTGG_TABLE . " WHERE UPPER(box_name) = '".$duplicated_trigger_box_name."'" ));

				if($duplicated_searched_triggers_count > 0) {
					$duplicated_trigger_copy_count = 1;
					$duplicated_trigger_box_name_additional = "";

					while($duplicated_searched_triggers_count > 0) {
						$duplicated_searched_triggers_count = count($wpdb->get_results( "SELECT * FROM " . WPTGG_TABLE . " WHERE UPPER(box_name) = '".$duplicated_trigger_box_name." ".$duplicated_trigger_copy_count."'" ));

						$duplicated_trigger_box_name_additional = $duplicated_trigger_box_name." ".$duplicated_trigger_copy_count;

						$duplicated_trigger_copy_count++;
					}

					if($duplicated_trigger_box_name_additional != "") {
						$duplicated_trigger_box_name = $duplicated_trigger_box_name_additional;
					}
				}
			}


			$data = array(
				"box_name" => $duplicated_trigger_box_name,
				"box_info" => $duplicated_trigger[0]->box_info,
				"no_found" => $duplicated_trigger[0]->no_found,
				"placeholder" => $duplicated_trigger[0]->placeholder,
				"show_chk" => $duplicated_trigger[0]->show_chk,
				"only_number_chk" => $duplicated_trigger[0]->only_number_chk,
				"scroll_to_chk" => $duplicated_trigger[0]->scroll_to_chk,
				"shake_chk" => $duplicated_trigger[0]->shake_chk,
				"btnactivate" => $duplicated_trigger[0]->btnactivate,
				"placement" => $duplicated_trigger[0]->placement,
				"apply_custom_styles" => $duplicated_trigger[0]->apply_custom_styles,
				"box_custom_style" => $duplicated_trigger[0]->box_custom_style,
				"btn_value" => $duplicated_trigger[0]->btn_value,
				"apply_button_custom_style" => $duplicated_trigger[0]->apply_button_custom_style,
				"option" => $duplicated_trigger[0]->option
			);

			$data["create_datetime"] = current_time("mysql");
                        wptggBackEnd::insert_to_table(WPTGG_TABLE, $data);



			wp_redirect(admin_url("admin.php?page=wp-trigger"));

			exit();
		}
	}


	



	static function trigger_delete()



	{



		global $wpdb ;



		$iid = $_GET["delete"] ;



		$wpdb->get_results("DELETE FROM " . WPTGG_TABLE . " WHERE ID = {$iid}") ;



		wp_redirect(admin_url("admin.php?page=wp-trigger")) ;		



	}



	



	static function get_page_link($count_posts,$pagenum,$per_page=15)



	{



		$allpages=ceil($count_posts / $per_page);



		$base= add_query_arg( 'paged', '%#%' );



		$page_links = paginate_links( array(



			'base' => $base,



			'format' => '',



			'prev_text' => __('&laquo;'),



			'next_text' => __('&raquo;'),



			'total' => $allpages,



			'current' => $pagenum



		));



		$page_links_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s' ) . '</span>%s',



				number_format_i18n( ( $pagenum - 1 ) * $per_page + 1 ),



				number_format_i18n( min( $pagenum * $per_page, $count_posts ) ),



				number_format_i18n( $count_posts ),



				$page_links



				);



		echo $page_links_text;



	}



	



	static function get_table_header()


	{


		$order = (!empty($_GET["order"]) ? (( $_GET["order"] == "desc" ) ? "asc" : "desc" ) : "");

		$bn_order_class = "";
		$cd_order_class = "";


		if(!empty($_GET["orderby"])) {
			if( $_GET["orderby"] == "name" ) {
				$bn_order_class = $_GET["order"] ;
			} else {
				$bn_order_class = "" ;
			}



			if( $_GET["orderby"] == "date" ) {
				$cd_order_class = $_GET["order"] ;
			} else {
				$cd_order_class = "" ;
			}
		}



		echo "<tr>";


		//echo "<th scope='col' class='manage-column check-column' ><input type='checkbox'></th>";


		echo "<th width='50px'>ID</th>";


		echo "<th width='30%' class='sorted $bn_order_class'>


					<a href='admin.php?page=wp-trigger&orderby=name&order=$order'>


						<span>". __("Name") . "</span>


						<span class='sorting-indicator'></span>


					</a>


				</th>";


		echo "<th>Shortcode</th>";

		echo "<th>Bookmark</th>";


		echo "<th class='sorted $cd_order_class'>


					<a href='admin.php?page=wp-trigger&orderby=date&order=$order'>


						<span>". __("Date Added") . "</span>


						<span class='sorting-indicator'></span>


					</a>


				</th>";


		echo "</tr>";


	}



	static function get_hostory_table_header()
	{
		$link = "page=trigger-history" ;
		if( !empty($_GET["triggerbox"]) )$link .= "&triggerbox=" . $_GET["triggerbox"] ;

		$order = "desc";
		if(!empty($_GET["order"])) {
			$order = ( $_GET["order"] == "desc" ) ? "asc" : "desc" ;
		}

		$trr_order_class = "";
		if(!empty($_GET["orderby"])) {
			if( $_GET["orderby"] == "trigger" )
				$trr_order_class = $_GET["order"] ;
			else
				$trr_order_class = "" ;
		}

		$vld_order_class = "";
		if(!empty($_GET["orderby"])) {
			if( $_GET["orderby"] == "valid" )
				$vld_order_class = $_GET["order"] ;
			else
				$vld_order_class = "" ;
		}

		$bnm_order_class = "";
		if(!empty($_GET["orderby"])) {
			if( $_GET["orderby"] == "boxname" )
				$bnm_order_class = $_GET["order"] ;
			else
				$bnm_order_class = "" ;
		}

		$cd_order_class = "";
		if(!empty($_GET["orderby"])) {
			if( $_GET["orderby"] == "date" )
				$cd_order_class = $_GET["order"] ;
			else
				$cd_order_class = "" ;
		}
	
		echo "<tr>";
		echo "<th scope='col' class='manage-column' style='width:2px' ></th>";		
		echo "<th class='check-column'>
                          <input id='tgg-select-all-1' type='checkbox'>
                      </th>";
		echo "<th class='sorted $trr_order_class'>		
				<a href='admin.php?{$link}&orderby=trigger&order=$order'>
					<span>". __("Trigger") . "</span>
					<span class='sorting-indicator'></span>
				</a>
			</th>";
		echo "<th class='sorted $vld_order_class'>
					<a href='admin.php?{$link}&orderby=valid&order=$order'>
						<span>". __("Valid") . "</span>
						<span class='sorting-indicator'></span>
					</a>
				</th>";		
		echo "<th class='sorted $bnm_order_class'>
					<a href='admin.php?{$link}&orderby=boxname&order=$order'>
						<span>". __("Trigger Box Name") . "</span>
						<span class='sorting-indicator'></span>
					</a>
				</th>";		
		echo "<th class='sorted $cd_order_class'>
					<a href='admin.php?{$link}&orderby=date&order=$order'>
						<span>". __("Submitted on") . "</span>
						<span class='sorting-indicator'></span>
					</a>
				</th>";
		echo "</tr>";
		
	}



	static function get_trigger_one_set($num=1, $ddata=null)



	{

		$typetxt = "";

		if(isset($ddata->type_txt)) {
			if( $ddata->type_txt && is_array($ddata->type_txt)) {
				foreach ($ddata->type_txt as $v) {
					$typetxt .= $v . "\n" ;
				}
			}
		}
		
		$trgg_valid_css = "checked" ;
		$typetxtcss = "" ;
		$email_valid_css = "" ;
		$html_valid_css = "checked" ;
		$displaytxt_css = "" ;
		$shortcode_cont_css = "" ;
		$shortcodechkcss = (isset($ddata->shortcode_chk) ? $ddata->shortcode_chk : "");
		$url_valid_css = "" ;
		$redirect_url_css = "disabled=true style='opacity:0.4;'" ;

		if(isset($ddata->trigger_email_valid)) {
			if( $ddata->trigger_email_valid == "email" ){
				$trgg_valid_css = "" ;
				$typetxtcss = "disabled=true style='opacity:0.4;'" ;
				$email_valid_css = "checked" ;
			}
		}

		if(isset($ddata->html_redirect)) {
			if( $ddata->html_redirect == "redirect" ){
				$html_valid_css = "" ;
				$displaytxt_css = "disabled=true style='opacity:0.4;'" ;
				$url_valid_css = "checked" ;
				$shortcode_cont_css = "style='opacity:0.4';" ;
				$redirect_url_css = "" ;			
			}
		}



		$requ = "" ;


		if( $num == 1 )$requ = " (REQUIRED)" ;


		$str = '<fieldset class="trigger_one_set" style="background-color:#fbfbfb;">


					<legend style="font-size:14px;">' .  __("Trigger Set #") . $num . $requ . '</legend>	


					<div class="mymessage"></div>' ;


		if( $num > 1 )$str .= '<a href="#" class="trigger_set_remove">Remove</a>' ;


		$str .='	<div class="one_set_content">


						<table width="100%">


							<tr> 


								<td width="50%">


									<div class="txtarea_div">


										<span class="wptgg_span_1">If the visitor types in...</span><br style="line-height:25px;"/>
										<span class="wptgg_span_2">' ;
		if( $num == 1 )$str .='				<input type="radio" name="wptrgg_valid_' . $num . '" class="wptrgg_valid" value="trigger" ' . $trgg_valid_css .  '  />' ;
		else $str .='						<span>&nbsp;&nbsp;&nbsp;</span>' ;
		$str .='							&nbsp;Any one of these <label style="font-weight:bold;vertical-align:top;">Triggers</label>
										</span><br style="line-height:15px;"/>
										<span class="wptgg_span_3">One Trigger per line - NOT case sensitive</span><br>


										<textarea class="type_txt" rows="10" ' . $typetxtcss .  '  >' . $typetxt . '</textarea>


										<span>&nbsp;</span>


									</div>


								</td>


								<td>


									<div class="txtarea_div" style="width:95%;float:right;">


										<span class="wptgg_span_1">Then do the following...</span><br style="line-height:25px;"/>
										<span class="wptgg_span_2"><input type="radio" name="html_valid_' . $num . '" class="trigger_html_redirect" value="html" ' . $html_valid_css .  '  />&nbsp;Display this <label style="font-weight:bold;vertical-align:top;">HTML</label></span><br style="line-height:15px;"/>
										<span class="wptgg_span_3">Insert HTML below</span><br>										


										<textarea rows="10" class="display_txt" ' . $displaytxt_css .  ' >' . (isset($ddata->display_txt) ? $ddata->display_txt : '') . '</textarea>										
										<span class="shortcode_chk_cont" ' . $shortcode_cont_css .  '><input type="checkbox" class="shortcode_chk js-switch" ' . $shortcodechkcss .  '  value="checked" ' . (isset($ddata->shortcode_chk) ? $ddata->shortcode_chk : '') . ' />&nbsp;<label>Contains Shortcode</label></span>


									</div>


								</td>


							</tr>
							<tr><td height="10px"></td><td></td></tr>
							<tr>
								<td width="50%">' ;
		
		if( $num == 1 ){
			$str .=					'<div class="trigger_valid_email_content">
										<input type="radio" name="wptrgg_valid_' . $num . '" class="wptrgg_valid" value="email" ' . $email_valid_css . ' />
										<span style="margin-left:5px;">Use any valid email address as a Trigger</span>
									</div>' ;
		}									
		$str .='				</td>
								<td>									
									<div style="width:95%;float:right;">
										<input type="radio" name="html_valid_' . $num . '" class="trigger_html_redirect" value="redirect" ' . $url_valid_css . '  /><span style="margin-left:5px;">Redirect visitors to this URL</span>
										<input type="text" class="trigger_redirect_url" ' . $redirect_url_css . ' value="' . (isset($ddata->redirect_url) ? $ddata->redirect_url : '') . '" />
									</div>									
								</td>
							</tr>


						</table>


					</div>


				</fieldset>';



		return $str;



	}



	



	static function ajax_get_trigger_one_set()


	{



		$str = wptggBackEnd::get_trigger_one_set($_POST["nnumber"]) ;



		echo $str ;



		exit() ;



	}

	static function ajax_trigger_export() {
		global $wpdb;

		$trigger_id = $_GET["trigger_id"] ? $_GET["trigger_id"] : "undefined";

		if($trigger_id != "undefined") {
			$trigger_data = $wpdb->get_row( "SELECT * FROM " . WPTGG_TABLE . " WHERE ID = {$trigger_id}" );

			$exportname = "trigger-".$trigger_data->box_name.".json";

			header("Content-type: application/json; charset=UTF-8");
			header("Content-Disposition: attachment; filename=".$exportname);
			header("Pragma: no-cache");
			header("Expires: 0");

			echo json_encode($trigger_data);

			exit();
		}
	}

	static function delete_trigger_history($history_rows=null) {
		global $wpdb;

		if($history_rows) {
			$sql = 'DELETE FROM '.WPTGG_HISTORY_TABLE.' WHERE ID IN (';

			$history_rows_index = 0;

			foreach($history_rows as $history_rows_index => $history_rows_value) {
				$history_rows_index++;

				$sql .= $history_rows_value.(count($history_rows) > $history_rows_index ? ',' : '');
			}

			$sql .= ')';

			$result = $wpdb->query($sql);
		}
	}

	static function change_option_page_title($pagetitle, $search_string) {
		return "<script>jQuery(document).ready(function() { change_option_page_title('".$pagetitle."', '".$search_string."'); });</script>";
	}

	static function wp_trigger_admin_message($class = 'updated', $message = '') {
		echo '<div class="'.$class.' notice is-dismissible">';
		echo '    <p>'.$message.'</p>';
		echo '</div>';
	}



}







class wptggFrontEnd extends wptggAction


{


	static function check_exist_element($arrs, $elm)
	{
		if(count($arrs)){

			foreach ($arrs as $v) {

				if(!$v)continue;

				// Underscore wildcard
				if(strpos($v, '_')) {
					$preg_template = "/^".str_replace("_", "[a-zA-Z0-9]", $v)."$/";
					if(preg_match(trim(strtolower($preg_template)), trim(strtolower($elm))) != 0) {
						return true;
					}
				}
				// Underscore wildcard

				// Percent wildcard
				if(strpos($v, '%')) {
					$preg_template = "/^".str_replace("%", "[^ ]*", $v)."$/";
					if(preg_match(trim(strtolower($preg_template)), trim(strtolower($elm))) != 0) {
						return true;
					}
				}
				// Percent wildcard

				if( trim(strtolower($elm)) == trim(strtolower($v)))return true;

			}
			return false;

		}
	}
	
	static function get_display_trigger_content($param)
	{
		$trigger = wptggFrontEnd::get_trigger(array("ID" => $param["wptrr_id"])) ;
		if( $trigger ){
			$infos = json_decode($trigger->box_info) ;
			$infos_no_found = json_decode($trigger->no_found);
			if( $infos ){
				$senddata["placeholder"] = $trigger->placeholder;
				$senddata["show_chk"] = $trigger->show_chk ;
				$senddata["only_number_chk"] = $trigger->only_number_chk ;
				$senddata["scroll_to_chk"] = $trigger->scroll_to_chk ;
				$senddata["shake_chk"] = $trigger->shake_chk ;
				foreach ($infos as $info) {
					$key_exists =  wptggFrontEnd::check_exist_element($info->type_txt, $param["trigger"]) ;
					if( $key_exists ){
						$senddata["valid_status"] = "yes" ;
						if( $info->html_redirect == "html" ){
							$senddata["shortcode"] = $info->shortcode_chk ;
							if($info->shortcode_chk != "checked"){								
								$senddata["display_txt"] = nl2br($info->display_txt) ;
							}
							if( $param["method"] == "load" ){
								$senddata["display_txt"] = apply_filters("the_content", $info->display_txt) ;
							}						
						}else{
							$senddata["redirect"] = $info->redirect_url ;													
						}
						return $senddata ;
					}
				}
				
				if( $infos->data_1->trigger_email_valid == "email" ){
					if(strpos($param["trigger"], "@") && strpos($param["trigger"], ".")){
						$senddata["valid_status"] = "yes" ;
						if( $infos->data_1->html_redirect == "html" ){
							$senddata["shortcode"] = $infos->data_1->shortcode_chk ;
							if($infos->data_1->shortcode_chk != "checked"){							
								$senddata["display_txt"] = nl2br($infos->data_1->display_txt) ;	
							}
							if( $param["method"] == "load" ){
								$senddata["display_txt"] = apply_filters("the_content", $info->display_txt) ;
							}
						}else{						
							$senddata["redirect"] =  $infos->data_1->redirect_url ;													
						}
						return $senddata;
					}					
				}
				$senddata["valid_status"] = "no" ;
				if( $infos_no_found->no_found_html_redirect == "html" ) {
					$senddata["shortcode"] = $infos_no_found->no_found_shortcode ;
					if($infos_no_found->no_found_shortcode != "checked") {							
						$senddata["display_txt"] = nl2br($infos_no_found->no_found_txt) ;
					}
					if( $param["method"] == "load" ){
						$senddata["display_txt"] = apply_filters("the_content", $infos_no_found->no_found_txt) ;
					}
				} else {
					$senddata["redirect"] =  $infos_no_found->no_found_redirect_url ;
				}

				return $senddata ;								
			}
			return "" ;
		}
	}
	
	static function get_display_trigger()


	{
		$param = array(
					"wptrr_id" => $_POST["wptgg_id"],
					"trigger" => $_POST["passkey"]
				) ;		
		$senddata = wptggFrontEnd::get_display_trigger_content($param) ;
		
		$store_data = array(
							"trigger" => $_POST["passkey"],
							"trigger_box_id" => $_POST["wptgg_id"],
							"valid_status" => $senddata["valid_status"],
							"create_datetime" => current_time('mysql')
						) ;
		if($senddata["valid_status"])wptggFrontEnd::insert_to_table(WPTGG_HISTORY_TABLE, $store_data) ;
		
		ob_clean() ;					
		echo json_encode($senddata);
		exit() ;
	}

	static function get_trigger_box_custom_style($box_custom_style_data)
	{
		$box_custom_style_css = '';

		$box_custom_style = json_decode($box_custom_style_data);

		if($box_custom_style->data_customTextAlignment) {
			$box_custom_style_css .= 'text-align: '.$box_custom_style->data_customTextAlignment.' !important;';
		}

		if($box_custom_style->data_customTextColor) {
			$box_custom_style_css .= 'color: '.$box_custom_style->data_customTextColor.' !important;';
		}

		if($box_custom_style->data_customBackgroundColor) {
			$box_custom_style_css .= 'background-color: '.$box_custom_style->data_customBackgroundColor.' !important;';
		}

		if($box_custom_style->data_customBorderColor) {
			$box_custom_style_css .= 'border: 1px solid '.$box_custom_style->data_customBorderColor.' !important;';
		}

		if($box_custom_style->data_customTextSize) {
			$box_custom_style_css .= 'font-size: '.$box_custom_style->data_customTextSize.'em !important;';
		}

		if($box_custom_style->data_customCornerRadius) {
			$box_custom_style_css .= 'border-radius: '.$box_custom_style->data_customCornerRadius.'px !important;';
		}

		if($box_custom_style->data_customVerticalPadding) {
			$box_custom_style_css .= 'padding-top: '.$box_custom_style->data_customVerticalPadding.'px !important;';
			$box_custom_style_css .= 'padding-bottom: '.$box_custom_style->data_customVerticalPadding.'px !important;';
		}

		if($box_custom_style->data_customHorizontalPadding) {
			$box_custom_style_css .= 'padding-left: '.$box_custom_style->data_customHorizontalPadding.'px !important;';
			$box_custom_style_css .= 'padding-right: '.$box_custom_style->data_customHorizontalPadding.'px !important;';
		}

		return $box_custom_style_css;
	}

	static function get_trigger_button_custom_style($button_custom_style_data)
	{
		$button_custom_style_css = '';

		$button_custom_style = json_decode($button_custom_style_data);

		if(isset($button_custom_style->data_customButtonBackgroundColor)) {
			$button_custom_style_css .= 'background-color: '.$button_custom_style->data_customButtonBackgroundColor.' !important;';
		}

		if(isset($button_custom_style->data_customButtonCornerRadius)) {
			$button_custom_style_css .= 'border-radius: '.$button_custom_style->data_customButtonCornerRadius.'px !important;';
		}

		if(isset($button_custom_style->data_customButtonVerticalPadding)) {
			$button_custom_style_css .= 'padding-top: '.$button_custom_style->data_customButtonVerticalPadding.'px !important;';
			$button_custom_style_css .= 'padding-bottom: '.$button_custom_style->data_customButtonVerticalPadding.'px !important;';
		}

		if(isset($button_custom_style->data_customButtonHorizontalPadding)) {
			$button_custom_style_css .= 'padding-left: '.$button_custom_style->data_customButtonHorizontalPadding.'px !important;';
			$button_custom_style_css .= 'padding-right: '.$button_custom_style->data_customButtonHorizontalPadding.'px !important;';
		}

		if(isset($button_custom_style->data_customButtonTextColor)) {
			$button_custom_style_css .= 'color: '.$button_custom_style->data_customButtonTextColor.' !important;';
		}

		if(isset($button_custom_style->data_customButtonTextSize)) {
			$button_custom_style_css .= 'font-size: '.$button_custom_style->data_customButtonTextSize.'em !important;';
		}


		/* IMAGE BUTTON */

		if(isset($button_custom_style->data_customImageButtonVerticalMargin)) {
			$button_custom_style_css .= 'margin-top: '.$button_custom_style->data_customImageButtonVerticalMargin.'px !important;';
			$button_custom_style_css .= 'margin-bottom: '.$button_custom_style->data_customImageButtonVerticalMargin.'px !important;';
		}

		if(isset($button_custom_style->data_customImageButtonHorizontalMargin)) {
			$button_custom_style_css .= 'margin-left: '.$button_custom_style->data_customImageButtonHorizontalMargin.'px !important;';
			$button_custom_style_css .= 'margin-right: '.$button_custom_style->data_customImageButtonHorizontalMargin.'px !important;';
		}

		return $button_custom_style_css;
	}

	static function get_trigger_placeholder_style($trigger_id, $placeholder_color) {
		$html = '<style>';
		$html .= '	.wptrigger'.$trigger_id.'-triggerbox::-webkit-input-placeholder { color: '.$placeholder_color.'; }';
		$html .= '	.wptrigger'.$trigger_id.'-triggerbox:-moz-placeholder { color: '.$placeholder_color.'; }';
		$html .= '	.wptrigger'.$trigger_id.'-triggerbox::-moz-placeholder { color: '.$placeholder_color.'; }';
		$html .= '	.wptrigger'.$trigger_id.'-triggerbox:-ms-input-placeholder { color: '.$placeholder_color.'; }';
		$html .= '</style>';

		return $html;
	}
}



?>