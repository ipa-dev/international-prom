<?php /* Template Name: Social Beyouty */ ?>
<?php get_header(); ?>
<div id="content">
	<div class="maincontent">
	    <div class="section group">
	        <div class="col span_12_of_12"> 
                <div class="container">
                    <?php while (have_posts()) : the_post(); ?>
                    <h1><?php the_title(); ?></h1>
                    <div><?php the_content(); ?></div>
                    <?php endwhile; ?>  
                </div>                         
	        </div>
	    </div>
	</div>
</div>
<div id="insta_inner">
	<div class="maincontent ins noPadding">
	    <div class="section group">
        <?php
            $tag = 'Internationalprom';
            $client_id = "33d1b447a0554bd283810c6b725f0dfc";
            $url = 'https://api.instagram.com/v1/tags/'.$tag.'/media/recent?client_id='.$client_id.'&access_token=2175545644.5b9e1e6.8d1e28a9e35f4ebb8a32c2e75ffa5a40';
            $all_result = processURL($url);
            $decoded_results = json_decode($all_result, true);
            //print_r($decoded_results);
        ?>
        <?php if(!empty($decoded_results)) { ?>
        <?php foreach($decoded_results['data'] as $item){ ?>
	        <div class="col span_3_of_12">
                <div class="insta matchheight">
                    <?php
                        $image_link = $item['images']['standard_resolution']['url'];
                        $text = $item['caption']['text'];
                    ?>
                    <img src="<?php echo $image_link; ?>" />
                    <div class="insta_hover">
                        <div class="insta_hover_inner">
                            <a href="javascript:void(0);"><?php echo the_excerpt_max_charlength_by_content(300, $text); ?></a>                                
                        </div>
                    </div>
                </div>                       
	        </div>
         <?php } ?>
         <?php } ?>
         <?php
            $tag = 'IPAProm';
            $client_id = "33d1b447a0554bd283810c6b725f0dfc";
            $url = 'https://api.instagram.com/v1/tags/'.$tag.'/media/recent?client_id='.$client_id.'&access_token=2175545644.5b9e1e6.8d1e28a9e35f4ebb8a32c2e75ffa5a40';
            $all_result = processURL($url);
            $decoded_results = json_decode($all_result, true);
            //print_r($decoded_results);
        ?>
        <?php if(!empty($decoded_results)) { ?>
        <?php foreach($decoded_results['data'] as $item){ ?>
	        <div class="col span_3_of_12">
                <div class="insta matchheight">
                    <?php
                        $image_link = $item['images']['standard_resolution']['url'];
                        $text = $item['caption']['text'];
                    ?>
                    <img src="<?php echo $image_link; ?>" />
                    <div class="insta_hover">
                        <div class="insta_hover_inner">
                            <a href="javascript:void(0);"><?php echo the_excerpt_max_charlength_by_content(300, $text); ?></a>                                
                        </div>
                    </div>
                </div>                       
	        </div>
         <?php } ?>
         <?php } ?>
         <?php
            $tag = 'beYOUty';
            $client_id = "33d1b447a0554bd283810c6b725f0dfc";
            $url = 'https://api.instagram.com/v1/tags/'.$tag.'/media/recent?client_id='.$client_id.'&access_token=2175545644.5b9e1e6.8d1e28a9e35f4ebb8a32c2e75ffa5a40';
            $all_result = processURL($url);
            $decoded_results = json_decode($all_result, true);
            //print_r($decoded_results);
        ?>
        <?php if(!empty($decoded_results)) { ?>
        <?php foreach($decoded_results['data'] as $item){ ?>
	        <div class="col span_3_of_12">
                <div class="insta matchheight">
                    <?php
                        $image_link = $item['images']['standard_resolution']['url'];
                        $text = $item['caption']['text'];
                    ?>
                    <img src="<?php echo $image_link; ?>" />
                    <div class="insta_hover">
                        <div class="insta_hover_inner">
                            <a href="javascript:void(0);"><?php echo the_excerpt_max_charlength_by_content(300, $text); ?></a>                                
                        </div>
                    </div>
                </div>                       
	        </div>
         <?php } ?>
         <?php } ?>
	    </div>
	</div>
</div>
<?php get_footer(); ?>