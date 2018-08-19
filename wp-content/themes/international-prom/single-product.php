<?php get_header(); ?>
<div id="content">
	<div class="maincontent">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
		<div class="section group">
			<div class="col span_6_of_12">
				<div class="pro_img_single matchheight">
					<div class="productGallery">
						<?php $images = get_field( 'product_images' ); ?>
						<div class="section group">
							<div class="col span_10_of_12">
								<ul class="productGallerySlider">
									<li><?php the_post_thumbnail( 'full' ); ?></li>
									<?php if ( ! empty( $images ) ) { ?>
										<?php foreach ( $images as $image ) { ?>
									<li><img src="<?php echo $image['url']; ?>" /></li>
									<?php } ?>
									<?php } ?>
								</ul>
							</div>
							<div class="col span_2_of_12">
test
								<div id="productGallery-pager">
									<!--<a data-slide-index="0" href="javascript:void(0)"><?php the_post_thumbnail( 'pro-thumbnail' ); ?></a> -->
									<?php
									if ( ! empty( $images ) ) {
											  $i = 1;
										foreach ( $images as $image ) {
											?>
											  <a data-slide-index="<?php echo $i; ?>" href="javascript:void(0)"><img src="<?php echo $image['url']; ?>" /></a>
											<?php
											$i++; }
									}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col span_6_of_12"> 
				<div class="container matchheight">
					<h1 style="text-align: left; margin-bottom: 5px;"><?php the_title(); ?></h1>
					<?php the_content(); ?>
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
							<div class="add-to-wish-list"><a class="fancybox_login_popup" href="#login-popup">ADD TO WISH LIST <i class="fa fa-heart-o"></i></a></div>
						</div>
					</div>
					<div class="section group">
						<div class="col span_12_of_12"><br />
							<div class="devider"></div><br />
							<h4 class="h4heading">Product Description</h4>
							<div><?php the_content(); ?></div>
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
	jQuery('.productGallerySlider').bxSlider({
		auto: true,
		pagerCustom: '#productGallery-pager'
	});
});
</script>
<?php get_footer(); ?>
