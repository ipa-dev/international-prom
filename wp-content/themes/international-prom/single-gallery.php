<?php
	$view         = get_post_meta( get_the_ID(), 'view', true );
	$redirect_url = get_post_meta( get_the_ID(), 'redirect_url', true );
if ( $view == 0 ) {
	if ( ! empty( $redirect_url ) ) {
		header( 'Location: ' . $redirect_url );
	}
}
?>
<?php get_header(); ?>
<div id="content">
	<div class="maincontent">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<?php
			$category = get_the_terms( get_the_ID(), 'gallery_category' );
			if ( $category[0]->slug == 'prom-dresses' ) {
				$slug = $category[1]->slug;
			} else {
				$slug = $category[0]->slug;
			}
			echo "<a href='" . get_bloginfo( 'url' ) . '/gallery_category/' . $slug . "'><div style='position:relative;'><img
src='" . get_bloginfo( 'template_directory' ) . "/images/pro_slider_prev_black.png'><span style='position:absolute;top:15%;font-size:13px'>BACK TO DESIGNER GALLERY</span></div></a>";
			?>
		<div class="section group">

			<div class="col span_6_of_12">
				<div class="pro_img_single matchheight">
					<div class="simpleLens-gallery-container">
						<?php $images = get_field( 'product_images' ); ?>
						<div class="section group">
						   <?php if ( count( $images ) + 1 < 7 ) { ?>
							<div class="col span_2_of_12">
								<div id="productGallery-pager">
									<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'pro-thumbnail' ); ?>
									<?php $thumb_big = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' ); ?>
									<a class="simpleLens-thumbnail-wrapper" href="#" data-lens-image="<?php echo $thumb_big[0]; ?>" data-big-image="<?php echo $thumb_big[0]; ?>">
										<img src="<?php echo $thumb[0]; ?>" />
									</a>
									<?php if ( ! empty( $images ) ) { ?>
										<?php //$k = 0; ?>
										<?php foreach ( $images as $image ) { ?>
											<?php //if ($k != 0) { ?>
											<a class="simpleLens-thumbnail-wrapper" href="#" data-lens-image="<?php echo $image['url']; ?>" data-big-image="<?php echo $image['url']; ?>">
												<?php $thumb1 = wp_get_attachment_image_src( $image['ID'], 'pro-thumbnail' ); ?>
												<img src="<?php echo $thumb1[0]; ?>" />
											</a>
											<?php
											//}
											//$k++;
											?>
										<?php } ?>
									<?php } ?>
								</div>
							</div>
							 <div class="col span_10_of_12">
								<?php $url = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ); ?>
								<div class="simpleLens-container">
									<div class="simpleLens-big-image-container">
										<a class="simpleLens-lens-image" href="#" data-lens-image="<?php echo $url; ?>">
											<img class="simpleLens-big-image" src="<?php echo $url; ?>" />
										</a>
									</div>
								</div>
							</div>
						<?php } ?>
					   <?php if ( count( $images ) + 1 >= 7 ) { ?>
							<div class="col span_2_of_12">
								<div id="productGallery-pager">
									<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'pro-thumbnail' ); ?>
									<?php $thumb_big = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' ); ?>
									<a class="simpleLens-thumbnail-wrapper" href="#" data-lens-image="<?php echo $thumb_big[0]; ?>" data-big-image="<?php echo $thumb_big[0]; ?>">
										<img src="<?php echo $thumb[0]; ?>" />
									</a>
									<?php if ( ! empty( $images ) ) { ?>
										<?php for ( $i = 1; $i < 6; $i++ ) { ?>
											<a class="simpleLens-thumbnail-wrapper" href="#" data-lens-image="<?php echo $images[ $i ]['url']; ?>" data-big-image="<?php echo $images[ $i ]['url']; ?>">
												<img src="
												<?php
												$url = explode( '.', $images[ $i ]['url'] );
																echo $url[0] . '.' . $url[1] . '-170x170.' . $url[2];
												?>
												 " />
											</a>
										<?php } ?>
									<?php } ?>
								</div>
							</div>
							<div class="col span_2_of_12">
								<div id="productGallery-pager">
									<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'pro-thumbnail' ); ?>
									<?php $thumb_big = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' ); ?>
									<a class="simpleLens-thumbnail-wrapper" href="#" data-lens-image="<?php echo $thumb_big[0]; ?>" data-big-image="<?php echo $thumb_big[0]; ?>">
										<img src="<?php echo $thumb[0]; ?>" />
									</a>
									<?php if ( ! empty( $images ) ) { ?>
										<?php for ( $i = 6; $i < count( $images ); $i++ ) { ?>
											<a class="simpleLens-thumbnail-wrapper" href="#" data-lens-image="<?php echo $images[ $i ]['url']; ?>" data-big-image="<?php echo $images[ $i ]['url']; ?>">
												<img src="
												<?php
												$url = explode( '.', $images[ $i ]['url'] );
																echo $url[0] . '.' . $url[1] . '-170x170.' . $url[2];
												?>
												 " />
											</a>
										<?php } ?>
									<?php } ?>
								</div>
							</div>
							<div class="col span_8_of_12">
								<?php $url = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ); ?>
								<div class="simpleLens-container">
									<div class="simpleLens-big-image-container">
										<a class="simpleLens-lens-image" href="#" data-lens-image="<?php echo $url; ?>">
											<img class="simpleLens-big-image" src="<?php echo $url; ?>" />
										</a>
									</div>
								</div>
							</div>
						<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<div class="col span_6_of_12"> 
				<div class="container matchheight">
					<h1 style="text-align: left; margin-bottom: 5px;"><?php the_title(); ?></h1>
					<div class="section group">
						<div class="col span_12_of_12">
							<h4 class="h4heading">Sizes: </h4>
							<span class="prod-attr">
								<?php the_field( 'sizes' ); ?>
							</span>
						</div>
					</div>
					<div class="section group">
						<div class="col span_12_of_12">
							<h4 class="h4heading">Colors:</h4>
							<span class="prod-attr">
								<?php the_field( 'colors' ); ?>
							</span>
						</div>
					</div>
					<div class="section group">
						<div class="col span_6_of_12">
							<div class="find-store"><a href="<?php bloginfo( 'url' ); ?>/store-locator/">FIND A STORE NEAR YOU</a></div>
						</div>
						<div class="col span_6_of_12">
							<?php
								$fav       = get_user_meta( $user_ID, 'fav', true );
								$fav_array = json_decode( $fav );
							if ( ! empty( $fav_array ) ) {
								if ( in_array( get_the_ID(), $fav_array ) ) {
									$isfav = 'active';
									?>
										<script>
											jQuery(document).ready(function() {
											   //jQuery('a#fav-<?php //echo get_the_ID(); ?>').bind('click', removeFav<?php //echo get_the_ID(); ?>); 
											   jQuery('a#fav-<?php echo get_the_ID(); ?>').addClass('active');
											});
										</script>
									<?php
								}
							}
							?>
							<script>
							function addFav<?php echo get_the_ID(); ?>(){
								var articleID = <?php echo get_the_ID(); ?>;
								jQuery.ajax({
								  url: "<?php bloginfo( 'url' ); ?>/favorite-add/",
								  data: {"id": articleID},
								  success: function(response){
									if(response == 'addSuccess') {
									   jQuery('a#fav-<?php echo get_the_ID(); ?>')
											 .addClass('active')
											 .attr('title','[-] Remove from favorites')
											 .attr('onclick','removeFav<?php echo get_the_ID(); ?>();')
											 .html('REMOVE FROM FAVORITES <i class="fa fa-heart">')
									   ;
									}
								  }
								});
							}
							function removeFav<?php echo get_the_ID(); ?>(){
								var articleID = <?php echo get_the_ID(); ?>;
								jQuery.ajax({
								  url: "<?php bloginfo( 'url' ); ?>/favorite-remove/",
								  data: {"id": articleID},
								  success: function(response){
									if(response == 'removeSuccess') {
										jQuery('a#fav-<?php echo get_the_ID(); ?>')
											 .removeClass('active')
											 .attr('title','[+] Add as favorite')
											 .unbind('click')
											 .attr('onclick','addFav<?php echo get_the_ID(); ?>();')
											 .html('ADD TO FAVORITES <i class="fa fa-heart-o"></i>')
										;
									}
								  }
								});
							}
							//this will make the link listen to function addFav (you might know this already)
							//jQuery('a#fav').bind('click', addFav);
							</script>
							<div class="add-to-wish-list">
								<?php if ( is_user_logged_in() ) { ?>
								<a href="javascript:void(0)" id="fav-<?php echo get_the_ID(); ?>" 
																				<?php
																				if ( $isfav == 'active' ) {
																					?>
									onclick="removeFav<?php echo get_the_ID(); ?>();" title="[-] Remove from favorites"
																					<?php
																				} else {
																					?>
									onclick="addFav<?php echo get_the_ID(); ?>();" title="[+] Add as favorite"<?php } ?>>
									<?php
									if ( $isfav == 'active' ) {
										?>
	REMOVE FROM FAVORITES <i class="fa fa-heart"></i>
										<?php
									} else {
										?>
	ADD TO FAVORITES <i class="fa fa-heart-o"></i><?php } ?></a>
								<?php } else { ?>
								<a class="fancybox_login_popup" href="#login-popup">ADD TO FAVORITES <i class="fa fa-heart-o"></i></a>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="section group">
						<div class="col span_12_of_12"><br />
							<div class="devider"></div><br />
							<h4 class="h4heading">Product Description</h4>
							<div><?php the_content(); ?></div>
							<div><?php the_field( 'video' ); ?></div>
						</div>
					</div>
					<div class="section group">
						<div class="col span_12_of_12">
							<div class="devider"></div>
							<div class="product-share"><?php get_template_part( 'social_share' ); ?></div>
							<div class="devider"></div>
						</div>
					</div>
				</div>                         
			</div>
		</div>
		<div class="section group">
			<div class="col span_12_of_12">
				<div id="reviews">
					<?php comments_template(); ?>
				</div>  
			</div>
		</div>
		<?php endwhile; ?>
	</div>
</div>
<script>
jQuery(document).ready(function(){
	jQuery('#productGallery-pager img').simpleGallery({
		loading_image: '<?php bloginfo( 'template_directory' ); ?>/images/loading.gif'
	});
});
</script>
<script>
jQuery(document).ready(function() {
	jQuery('.simpleLens-big-image').simpleLens({
		loading_image: '<?php bloginfo( 'template_directory' ); ?>/images/loading.gif'
	});
});
</script>
<?php get_footer(); ?>
