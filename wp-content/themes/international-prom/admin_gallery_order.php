<script type="text/javascript" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/select/1.2.3/js/dataTables.select.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/rowreorder/1.2.3/js/dataTables.rowReorder.min.js"></script>
<link rel="stylesheet" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="//cdn.datatables.net/select/1.2.3/css/select.dataTables.min.css" />
<link rel="stylesheet" href="//cdn.datatables.net/rowreorder/1.2.3/css/rowReorder.dataTables.min.css" />
<script>

	jQuery(document).ready(function($){
		var table = jQuery('#dataTable').DataTable({
			"pageLength": 50,
			columns: [
				{ data: 'readingOrder', className: 'reorder' },
				{ data: 'title' },
				{ data: 'style_no' },
				{ data: 'view' },
				{ data: 'date' }
			],
			columnDefs: [
				{ orderable: false, targets: [ 1,2,3 ] }
			],
			rowReorder: {
				dataSrc: 'readingOrder'
			},
			select: true,
		});

		table.on( 'row-reorder', function ( e, diff, edit ) {
			jQuery.ajax({
				type : "post",
				url : '<?php echo admin_url( 'admin-ajax.php' ); ?>',
				data : {action: "reOrder", orderArray : edit.values},
				success: function(response) {
					//console.log(response);
				}
			});
		} );

		jQuery('.view_checkbox').on('click',function(){
			var PostId = jQuery(this).attr("data-PostId");
			var view = jQuery('input[name="view"].View_'+PostId).val();
			if(view === undefined) {
				view = 0;
			}
			jQuery.ajax({
				type : "post",
				dataType : "json",
				url : '<?php echo admin_url( 'admin-ajax.php' ); ?>',
				data : {action: "galleryView", PostId : PostId, view: view},
				success: function(response) {
					//alert('success');
				}
			});
		});
	});
</script>
<h1>Designers Order</h1>
<?php
$args      = array(
	'post_type'      => 'gallery',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'meta_key'       => 'order',
	'orderby'        => 'meta_value_num',
	'order'          => 'ASC',
);
$the_query = new WP_Query( $args );
if ( $the_query->have_posts() ) :
	//$i = 1;
	?>
<table id="dataTable" class="row-border hover display compact" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th>Order<br/>(Drag row to reorder)</th>
			<th>Title</th>
			<th>Style Number</th>
			<th>Show/Hide</th>
			<th>Publish Date</th>
		</tr>
	</thead>
	<tbody>
	<?php
	while ( $the_query->have_posts() ) :
		$the_query->the_post();
		//add_post_meta(get_the_ID(), 'order', $i);
		$order = get_post_meta( get_the_ID(), 'order', true );
		?>
		<tr id="<?php echo get_the_ID(); ?>">
			<td><?php echo $order; ?></td>
			<td><?php the_title(); ?></td>
			<td><?php echo get_post_meta( get_the_ID(), 'style_no', true ); ?></td>
			<td>
				<?php
					$view = get_post_meta( get_the_ID(), 'view', true );
				if ( $view != 1 ) {
					?>
				<input class="view_checkbox" data-PostId="<?php echo get_the_ID(); ?>" type="checkbox" checked value="1" />
				<input class="View_<?php echo get_the_ID(); ?>" type="hidden" name="view" value="1">
				<?php } else { ?>
				<input class="view_checkbox" data-PostId="<?php echo get_the_ID(); ?>" type="checkbox" value="1" />
				<input class="View_<?php echo get_the_ID(); ?>" type="hidden" name="view" value="0">
				<?php } ?>
			</td>
			<td>
				<strong><?php the_time( 'l, F jS, Y' ); ?></strong>
			</td>
		</tr>
		<?php
		//$i++;
	endwhile;
	?>
	</tbody>
</table>
	<?php
endif;
wp_reset_postdata();
?>
<style>
	th, td {
		text-align: left;
	}
</style>
