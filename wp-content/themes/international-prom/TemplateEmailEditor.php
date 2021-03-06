<?php /* Template Name: Email Editor */ ?>
<?php get_header(); ?>
<div id="content">
	<div class="maincontent">
		<div class="section group">
			<div class="col span_12_of_12">
				<div class="container">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<h1><?php the_title(); ?></h1>
						<div><?php the_content(); ?></div>
					<?php endwhile; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="myImage" style="display: none;"></div>
<div id="content" style="height: 100%;">
	<div class="maincontent" style="height: 96%;">
		<div class="section group" style="height: 100%;">
			<div class="col span_12_of_12" style="height: 100%;">
				<div class="container" style="height: 100%;">
					<div id="gjs" style="height:0px; overflow:hidden; font-size: 16px;">
						<?php if ( ! isset( $_GET['editID'] ) ) { ?>
						<style>
							.clearfix{ clear:both}
							.header-banner{
								padding-top: 35px;
								padding-bottom: 100px;
								color: #ffffff;
								font-family: Helvetica, serif;
								font-weight: 100;
								background-image:url("http://grapesjs.com/img/bg-gr-v.png"), url("http://grapesjs.com/img/work-desk.jpg");
								background-attachment: fixed, scroll;
								background-position:left top, center center;
								background-repeat:repeat-y, no-repeat;
								background-size: contain, cover;
							}
							.container-width{
								width: 90%;
								max-width: 1150px;
								margin: 0 auto;
							}
							.logo-container{
								float: left;
								width: 50%;
							}
							.logo{
								background-color: #fff;
								border-radius: 5px;
								width: 130px;
								padding: 10px;
								min-height: 30px;
								text-align: center;
								line-height: 30px;
								color: #4d114f;
								font-size: 23px;
							}
							.navbar{
								float: right;
								width: 50%;
							}
							.menu-item{
								float:right;
								font-size: 15px;
								color:#eee;
								width: 130px;
								padding: 10px;
								min-height: 50px;
								text-align: center;
								line-height: 30px;
								font-weight: 400;
							}
							.lead-title{
								padding: 25px;
								margin: 150px 50px 30px 30px;
								font-size: 40px;
							}
							.sub-lead-title{
								max-width: 650px;
								line-height:30px;
								margin-bottom:30px;
								color: #c6c6c6;
							}
							.lead-btn{
								margin-top: 15px;
								padding:10px;
								width:190px;
								min-height:30px;
								font-size:20px;
								text-align:center;
								letter-spacing:3px;
								line-height:30px;
								background-color:#d983a6;
								border-radius:5px;
								transition: all 0.5s ease;
								cursor: pointer;
							}
							.lead-btn:hover{
								background-color:#ffffff;
								color:#4c114e;
							}
							.lead-btn:active{
								background-color:#4d114f;
								color:#fff;
							}
							.flex-sect{
								background-color: #fafafa;
								padding: 100px 0;
								font-family: Helvetica, serif;
							}
							.flex-title{
								margin-bottom: 15px;
								font-size: 2em;
								text-align: center;
								font-weight: 700;
								color:#555;
								padding: 5px;
							}
							.flex-desc{
								margin-bottom: 55px;
								font-size: 1em;
								color: rgba(0, 0, 0, 0.5);
								text-align: center;
								padding: 5px;
							}
							.cards{
								padding: 20px 0;
								display: flex;
								justify-content: space-around;
								flex-flow: wrap;
							}
							.card{
								background-color: white;
								height: 300px;
								width:300px;
								margin-bottom:30px;
								box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2);
								border-radius: 2px;
								transition: all 0.5s ease;
								font-weight: 100;
								overflow: hidden;
							}
							.card:hover{
								margin-top: -5px;
								box-shadow: 0 20px 30px 0 rgba(0, 0, 0, 0.2);
							}
							.card-header{
								height: 155px;
								background-image:url("http://placehold.it/350x250/78c5d6/fff/image1.jpg");
								background-size:cover;
								background-position:center center;
							}
							.card-header.ch2{
								background-image:url("http://placehold.it/350x250/459ba8/fff/image2.jpg");
							}
							.card-header.ch3{
								background-image:url("http://placehold.it/350x250/79c267/fff/image3.jpg");
							}
							.card-header.ch4{
								background-image:url("http://placehold.it/350x250/c5d647/fff/image4.jpg");
							}
							.card-header.ch5{
								background-image:url("http://placehold.it/350x250/f28c33/fff/image5.jpg");
							}
							.card-header.ch6{
								background-image:url("http://placehold.it/350x250/e868a2/fff/image6.jpg");
							}
							.card-body{
								padding: 15px 15px 5px 15px;
								color: #555;
							}
							.card-title{
								font-size: 1.4em;
								margin-bottom: 5px;
							}
							.card-sub-title{
								color: #b3b3b3;
								font-size: 1em;
								margin-bottom: 15px;
							}
							.card-desc{
								font-size: 0.85rem;
								line-height: 17px;
							}
							.am-sect{
								padding-top: 100px;
								padding-bottom: 100px;
								font-family: Helvetica, serif;
							}
							.img-phone{
								float: left;
							}
							.am-container{
								display: flex;
								flex-wrap: wrap;
								align-items: center;
								justify-content: space-around;
							}
							/*
							.am-container{
							  perspective: 1000px;
							}*/
							.am-content{
								float:left;
								padding:7px;
								width: 490px;
								color: #444;
								font-weight: 100;
								margin-top: 50px;
								/*transform: rotateX(0deg) rotateY(-20deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1);*/
							}
							.am-pre{
								padding:7px;
								color:#b1b1b1;
								font-size:15px;
							}
							.am-title{
								padding:7px;
								font-size:25px;
								font-weight: 400;
							}
							.am-desc{
								padding:7px;
								font-size:17px;
								line-height:25px;
							}
							.am-post{
								padding:7px;
								line-height:25px;
								font-size:13px;
							}
							.blk-sect{
								padding-top: 100px;
								padding-bottom: 100px;
								background-color: #222222;
								font-family: Helvetica, serif;
							}
							.blk-title{
								color:#fff;
								font-size:25px;
								text-align:center;
								margin-bottom: 15px;
							}
							.blk-desc{
								color:#b1b1b1;
								font-size:15px;
								text-align:center;
								max-width:700px;
								margin:0 auto;
								font-weight:100;
							}
							.price-cards{
								margin-top: 70px;
								display: flex;
								flex-wrap: wrap;
								align-items: center;
								justify-content: space-around;
							}
							.price-card-cont{
								width: 300px;
								padding: 7px;
								float:left;
							}
							.price-card{
								margin:0 auto;
								min-height:350px;
								background-color:#d983a6;
								border-radius:5px;
								font-weight: 100;
								color: #fff;
								width: 90%;
							}
							.pc-title{
								font-weight:100;
								letter-spacing:3px;
								text-align:center;
								font-size:25px;
								background-color:rgba(0, 0, 0, 0.1);
								padding:20px;
							}
							.pc-desc{
								padding: 75px 0;
								text-align: center;
							}
							.pc-feature{
								color:rgba(255,255,255,0.5);
								background-color:rgba(0, 0, 0, 0.1);
								letter-spacing:2px;
								font-size:15px;
								padding:10px 20px;
							}
							.pc-feature:nth-of-type(2n){
								background-color:transparent;
							}
							.pc-amount{
								background-color:rgba(0, 0, 0, 0.1);
								font-size: 35px;
								text-align: center;
								padding: 35px 0;
							}
							.pc-regular{
								background-color: #da78a0;
							}
							.pc-enterprise{
								background-color: #d66a96;
							}
							.footer-under{
								background-color: #312833;
								padding-bottom: 100px;
								padding-top: 100px;
								min-height: 500px;
								color:#eee;
								position: relative;
								font-weight: 100;
								font-family: Helvetica,serif;
							}
							.led{
								border-radius: 100%;
								width: 10px;
								height: 10px;
								background-color: rgba(0, 0, 0, 0.15);
								float: left;
								margin: 2px;
								transition: all 5s ease;
							}
							.led:hover{
								background-color: #c29fca;/* #eac229 */
								box-shadow: 0 0 5px #9d7aa5, 0 0 10px #e6c3ee;/* 0 0 10px 0 #efc111 */
								transition: all 0s ease;
							}
							.copyright {
								background-color: rgba(0, 0, 0, 0.15);
								color: rgba(238, 238, 238, 0.5);
								bottom: 0;
								padding: 1em 0;
								position: absolute;
								width: 100%;
								font-size: 0.75em;
							}
							.made-with{
								float: left;
								width: 50%;
								padding: 5px 0;
							}
							.foot-social-btns{
								display: none;
								float: right;
								width: 50%;
								text-align: right;
								padding: 5px 0;
							}
							.footer-container{
								display: flex;
								flex-wrap: wrap;
								align-items: stretch;
								justify-content: space-around;
							}
							.foot-list {
								float: left;
								width: 200px;
							}
							.foot-list-title {
								font-weight: 400;
								margin-bottom: 10px;
								padding: 0.5em 0;
							}
							.foot-list-item {
								color: rgba(238, 238, 238, 0.8);
								font-size: 0.8em;
								padding: 0.5em 0;
							}
							.foot-list-item:hover {
								color: rgba(238, 238, 238, 1);
							}
							.foot-form-cont{
								width: 300px;
								float: right;
							}
							.foot-form-title{
								color: rgba(255,255,255,0.75);
								font-weight: 400;
								margin-bottom: 10px;
								padding: 0.5em 0;
								text-align: right;
								font-size: 2em;
							}
							.foot-form-desc{
								font-size: 0.8em;
								color: rgba(255,255,255,0.55);
								line-height: 20px;
								text-align: right;
								margin-bottom: 15px;
							}
							.sub-input{
								width: 100%;
								margin-bottom: 15px;
								padding: 7px 10px;
								border-radius: 2px;
								color:#fff;
								background-color: #554c57;
								border: none;
							}
							.sub-btn{
								width: 100%;
								margin-bottom: 15px;
								background-color: #785580;
								border: none;
								color:#fff;
								border-radius: 2px;
								padding: 7px 10px;
								font-size: 1em;
								cursor: pointer;
							}
							.sub-btn:hover{
								background-color: #91699a;
							}
							.sub-btn:active{
								background-color: #573f5c;
							}
							.blk-row::after{
								content: "";
								clear: both;
								display: block;
							}
							.blk-row{
								padding: 10px;
							}
							.blk1{
								width: 100%;
								padding: 10px;
								min-height: 75px;
							}
							.blk2{
								float: left;
								width: 50%;
								padding: 10px;
								min-height: 75px;
							}
							.blk3{
								float: left;
								width: 33.3333%;
								padding: 10px;
								min-height: 75px;
							}
							.blk37l{
								float: left;
								width: 30%;
								padding: 10px;
								min-height: 75px;
							}
							.blk37r{
								float: left;
								width: 70%;
								padding: 10px;
								min-height: 75px;
							}
							.heading{padding: 10px;}
							.paragraph{padding: 10px;}

							.bdg-sect{
								padding-top:100px;
								padding-bottom:100px;
								font-family: Helvetica, serif;
								background-color: #fafafa;
							}
							.bdg-title{
								text-align: center;
								font-size: 2em;
								margin-bottom: 55px;
								color: #555555;
							}
							.badges{
								padding:20px;
								display: flex;
								justify-content: space-around;
								align-items: flex-start;
								flex-wrap: wrap;
							}
							.badge{
								width: 290px;
								font-family: Helvetica, serif;
								background-color: white;
								margin-bottom:30px;
								box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.2);
								border-radius: 3px;
								font-weight: 100;
								overflow: hidden;
								text-align: center;
							}
							.badge-header{
								height: 115px;
								background-image:url("http://grapesjs.com/img/bg-gr-v.png"), url("http://grapesjs.com/img/work-desk.jpg");
								background-position:left top, center center;
								background-attachment:scroll, fixed;
								overflow: hidden;
							}
							.blurer{
								filter: blur(5px);
							}
							.badge-name{
								font-size: 1.4em;
								margin-bottom: 5px;
							}
							.badge-role{
								color: #777;
								font-size: 1em;
								margin-bottom: 25px;
							}
							.badge-desc{
								font-size: 0.85rem;
								line-height: 20px;
							}
							.badge-avatar{
								width:100px;
								height:100px;
								border-radius: 100%;
								border: 5px solid #fff;
								box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2);
								margin-top: -75px;
								position: relative;
							}
							.badge-body{
								margin: 35px 10px;
							}
							.badge-foot{
								color:#fff;
								background-color:#a290a5;
								padding-top:13px;
								padding-bottom:13px;
								display: flex;
								justify-content: center;
							}
							.badge-link{
								height: 35px;
								width: 35px;
								line-height: 35px;
								font-weight: 700;
								background-color: #fff;
								color: #a290a5;
								display: block;
								border-radius: 100%;
								margin: 0 10px;
							}
							.quote{
								color:#777;
								font-weight: 300;
								padding: 10px;
								box-shadow: -5px 0 0 0 #ccc;
								font-style: italic;
								margin: 20px 30px;
							}

							.iframe {
								height: 300px;
								width: 500px;
							}

							.row {
								display: table;
								padding: 10px;
								width: 100%;
							}

							.cell {
								width: 8%;
								display: table-cell;
								height: 75px;
							}

							.cell30 {
								width: 30%;
							}

							.cell70 {
								width: 70%;
							}

							@media (max-width: 768px){
								.foot-form-cont{
									width:400px;
								}
								.foot-form-title{
									width: auto;
								}

								.cell, .cell30, .cell70 {
									width: 100%;
									display: block;
								}
							}

							@media (max-width: 480px){
								.foot-lists{
									display:none;
								}
							}
						</style>
							<?php
} else {
	echo get_post_meta( $_GET['editID'], 'templateHtml', true );
	$templateStyle = json_decode( get_post_meta( $_GET['editID'], 'templateStyle', true ) );
	if ( ! empty( $templateStyle ) ) {
		?>
								<style>
			<?php foreach ( $templateStyle as $style ) { ?>
										<?php
										if ( $style->selectors[0]->active == 1 ) {
											if ( $style->selectors[0]->type == 'class' ) {
												echo '.' . $style->selectors[0]->label . '{ ';
												if ( ! empty( $style->style ) ) {
													foreach ( $style->style as $key => $style1 ) {
														echo $key . ': ' . $style1 . ';';
													}
												}
												echo ' }';
											}
											if ( $style->selectors[0]->type == 'id' ) {
												echo '#' . $style->selectors[0]->label . '{ ';
												if ( ! empty( $style->style ) ) {
													foreach ( $style->style as $key => $style1 ) {
														echo $key . ': ' . $style1 . ';';
													}
												}
												echo ' }';
											}
										}
										?>
									<?php } ?>
								</style>
								<?php
	}
}
?>
					</div>

					<script type="text/javascript">
						jQuery(document).ready(function() {
                            var blkStyle = '.blk-row::after{ content: ""; clear: both; display: block;} .blk-row{padding: 10px;}';

                            var editor  = grapesjs.init(

                                {
                                    allowScripts: 1,
                                    showOffsets: 1,
                                    autorender: 0,
                                    noticeOnUnload: 0,
                                    container  : '#gjs',
                                    height: '100%',
                                    fromElement: true,
                                    clearOnRender: 0,

                                    storageManager: {
                                        type: 'remote',
                                        //stepsBeforeSave: 10,
                                        autosave: false,
                                        storeHtml: true,
                                        storeCss: true,
                                        urlStore: MyAjax.ajaxurl,
                                        //urlLoad: 'http://load/endpoint',
                                        params: {
											<?php if ( ! isset( $_GET['editID'] ) ) { ?>
                                            action: "save_data",
											<?php } else { ?>
                                            action: "edit_data",
                                            eventId: <?php echo $_GET['editID']; ?>,
											<?php } ?>
                                            type: "email",
                                            type2: "email_template",
                                            event_name: '<?php echo $_GET['event_name']; ?>',
                                            event_date: '<?php echo $_GET['event_date']; ?>',
                                            event_time: '<?php echo $_GET['event_time']; ?>',
                                            mailchimpList: '<?php echo $_GET['mailchimpList']; ?>',
                                            //event_content: '<?php //echo $_GET["event_name"]; ?>',
                                            event_image: '<?php echo $_GET['event_name']; ?>',
                                            subjectLine: '<?php echo $_GET['subjectLine']; ?>',
                                            previewtext: '<?php echo $_GET['previewtext']; ?>',
                                            fromName: '<?php echo $_GET['fromName']; ?>',
                                            fromEmail: '<?php echo $_GET['fromEmail']; ?>',
                                        },   // For custom values on requests
                                    },

                                    commands:     {
                                        defaults: [{
                                            id:   'open-github',
                                            run:   function(editor, sender){
                                                sender.set('active',false);
                                                window.open('https://github.com/artf/grapesjs','_blank');
                                            }
                                        },{
                                            id:   'undo',
                                            run:   function(editor, sender){
                                                sender.set('active',false);
                                                editor.UndoManager.undo(true);
                                            }
                                        },{
                                            id:   'redo',
                                            run:   function(editor, sender){
                                                sender.set('active',false);
                                                editor.UndoManager.redo(true);
                                            }
                                        },{
                                            id:   'clean-all',
                                            run:   function(editor, sender) {
                                                sender.set('active',false);
                                                if(confirm('Are you sure to clean the canvas?')) {
                                                    var comps = editor.DomComponents.clear();
                                                }
                                            }
                                        },{
                                            id: 'storeData',
                                            run:  function(editor, sender){
                                                editor.store();
                                            },
                                        }],
                                    },
                                    assetManager: {
                                        storageType  	: '',
                                        storeOnChange  : true,
                                        storeAfterUpload  : true,
                                        upload: '<?php bloginfo('url'); ?>/wp-content/uploads',        //for temporary storage
                                        assets    	: [ ],
                                        uploadFile: function(e) {
                                            var files = e.dataTransfer ? e.dataTransfer.files : e.target.files;
                                            var formData = new FormData();
                                            for(var i in files){
                                                formData.append('file-'+i, files[i]) //containing all the selected images from local
                                            }
                                            jQuery.ajax({
                                                url: '<?php bloginfo('url'); ?>/wp-admin/admin-ajax.php?action=emailEditorImageUpload',
                                                type: 'POST',
                                                data: formData,
                                                contentType:false,
                                                crossDomain: true,
                                                dataType: 'json',
                                                mimeType: "multipart/form-data",
                                                processData:false,
                                                success: function(result){
                                                    var myJSON = [];
                                                    jQuery.each( result['data'], function( key, value ) {
                                                        myJSON[key] = value;
                                                    });
                                                    var images = myJSON;
                                                    editor.AssetManager.add(images); //adding images to asset manager of GrapesJS
                                                }
                                            });
                                        },
                                    },

                                    blockManager: {
                                        blocks: [{
                                            id: 'b1',
                                            label: '1 Block',
                                            category: 'Basic',
                                            attributes: {class:'gjs-fonts gjs-f-b1'},
                                            content: '<div class="row" data-gjs-droppable=".cell" data-gjs-custom-name="Row"><div class="cell" data-gjs-draggable=".row" data-gjs-custom-name="Cell"></div></div>'
                                        },{
                                            id: 'b2',
                                            label: '2 Blocks',
                                            category: 'Basic',
                                            attributes: {class:'gjs-fonts gjs-f-b2'},
                                            content: '<div class="row" data-gjs-droppable=".cell" data-gjs-custom-name="Row"><div class="cell" data-gjs-draggable=".row" data-gjs-custom-name="Cell"></div><div class="cell" data-gjs-draggable=".row" data-gjs-custom-name="Cell"></div></div>'
                                        },{
                                            id: 'b3',
                                            label: '3 Blocks',
                                            category: 'Basic',
                                            attributes: {class:'gjs-fonts gjs-f-b3'},
                                            content: '<div class="row" data-gjs-droppable=".cell" data-gjs-custom-name="Row"><div class="cell" data-gjs-draggable=".row" data-gjs-custom-name="Cell"></div><div class="cell" data-gjs-draggable=".row" data-gjs-custom-name="Cell"></div><div class="cell" data-gjs-draggable=".row" data-gjs-custom-name="Cell"></div></div>'
                                        },{
                                            id: 'b4',
                                            label: '3/7 Block',
                                            category: 'Basic',
                                            attributes: {class:'gjs-fonts gjs-f-b37'},
                                            content: '<div class="row" data-gjs-droppable=".cell" data-gjs-custom-name="Row"><div class="cell cell30" data-gjs-draggable=".row" data-gjs-custom-name="Cell"></div><div class="cell cell70" data-gjs-draggable=".row" data-gjs-custom-name="Cell"></div></div>',
                                        },{
                                            id: 'hero',
                                            label: 'Hero section',
                                            category: 'Section',
                                            content: '<header class="header-banner"> <div class="container-width">'+
                                                '<div class="logo-container"><div class="logo">GrapesJS</div></div>'+
                                                '<nav class="navbar">'+
                                                '<div class="menu-item">BUILDER</div><div class="menu-item">TEMPLATE</div><div class="menu-item">WEB</div>'+
                                                '</nav><div class="clearfix"></div>'+
                                                '<div class="lead-title">Build your templates without coding</div>'+
                                                '<div class="lead-btn">Try it now</div></div></header>',
                                            attributes: {class:'gjs-fonts gjs-f-hero'}
                                        },{
                                            id: 'h1p',
                                            label: 'Text section',
                                            category: 'Typography',
                                            content: '<div><h1 class="heading">Insert title here</h1><p class="paragraph">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p></div>',
                                            attributes: {class:'gjs-fonts gjs-f-h1p'}
                                        },{
                                            id: '3ba',
                                            label: 'Badges',
                                            category: 'Section',
                                            content: '<div class="badges">'+
                                                '<div class="badge">'+
                                                '<div class="badge-header"></div>'+
                                                '<img class="badge-avatar" src="img/team1.jpg">'+
                                                '<div class="badge-body">'+
                                                '<div class="badge-name">Adam Smith</div><div class="badge-role">CEO</div><div class="badge-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore ipsum dolor sit</div>'+
                                                '</div>'+
                                                '<div class="badge-foot"><span class="badge-link">f</span><span class="badge-link">t</span><span class="badge-link">ln</span></div>'+
                                                '</div>'+
                                                '<div class="badge">'+
                                                '<div class="badge-header"></div>'+
                                                '<img class="badge-avatar" src="img/team2.jpg">'+
                                                '<div class="badge-body">'+
                                                '<div class="badge-name">John Black</div><div class="badge-role">Software Engineer</div><div class="badge-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore ipsum dolor sit</div>'+
                                                '</div>'+
                                                '<div class="badge-foot"><span class="badge-link">f</span><span class="badge-link">t</span><span class="badge-link">ln</span></div>'+
                                                '</div>'+
                                                '<div class="badge">'+
                                                '<div class="badge-header"></div>'+
                                                '<img class="badge-avatar" src="img/team3.jpg">'+
                                                '<div class="badge-body">'+
                                                '<div class="badge-name">Jessica White</div><div class="badge-role">Web Designer</div><div class="badge-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore ipsum dolor sit</div>'+
                                                '</div>'+
                                                '<div class="badge-foot"><span class="badge-link">f</span><span class="badge-link">t</span><span class="badge-link">ln</span>'+
                                                '</div>'+
                                                '</div></div>',
                                            attributes: {class:'gjs-fonts gjs-f-3ba'}
                                        },{
                                            id: 'text',
                                            label: 'Text',
                                            attributes: {class:'gjs-fonts gjs-f-text'},
                                            category: 'Basic',
                                            content: {
                                                type:'text',
                                                content:'Insert your text here',
                                                style: {padding: '10px' },
                                                activeOnRender: 1
                                            },
                                        },{
                                            id: 'image',
                                            label: 'Image',
                                            category: 'Basic',
                                            attributes: {class:'gjs-fonts gjs-f-image'},
                                            content: {
                                                style: {color: 'black'},
                                                type:'myImage',
                                                activeOnRender: 1
                                            },
                                        },{
                                            id: 'quo',
                                            label: 'Quote',
                                            category: 'Typography',
                                            content: '<blockquote class="quote">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore ipsum dolor sit</blockquote>',
                                            attributes: {class:'fa fa-quote-right'}
                                        },{
                                            id: 'link',
                                            label: 'Link',
                                            category: 'Basic',
                                            attributes: {class:'fa fa-link'},
                                            content: {
                                                type:'link',
                                                content:'Link',
                                                style:{color: '#d983a6'}
                                            },
                                        },{
                                            id: 'map',
                                            label: 'Map',
                                            category: 'Extra',
                                            attributes: {class:'fa fa-map-o'},
                                            content: {
                                                type: 'map',
                                                style: {height: '350px'}
                                            },
                                        },{
                                            id: 'video',
                                            label: 'Video',
                                            category: 'Basic',
                                            attributes: {class:'fa fa-youtube-play'},
                                            content: {
                                                type: 'video',
                                                src: 'img/video2.webm',
                                                style: {
                                                    height: '350px',
                                                    width: '615px',
                                                }
                                            },
                                        }],
                                    },

                                    styleManager : {
                                        sectors: [{
                                            name: 'General',
                                            open: false,
                                            buildProps: ['float', 'display', 'position', 'top', 'right', 'left', 'bottom']
                                        },{
                                            name: 'Dimension',
                                            open: false,
                                            buildProps: ['width', 'height', 'max-width', 'min-height', 'margin', 'padding'],
                                        },{
                                            name: 'Typography',
                                            open: false,
                                            buildProps: ['font-family', 'font-size', 'font-weight', 'letter-spacing', 'color', 'line-height', 'text-align', 'text-shadow'],
                                            properties: [{
                                                property: 'text-align',
                                                list    : [
                                                    {value: 'left', className: 'fa fa-align-left'},
                                                    {value: 'center', className: 'fa fa-align-center' },
                                                    {value: 'right', className: 'fa fa-align-right'},
                                                    {value: 'justify', className: 'fa fa-align-justify'}
                                                ],
                                            }]
                                        },{
                                            name: 'Decorations',
                                            open: false,
                                            buildProps: ['border-radius-c', 'background-color', 'border-radius', 'border', 'box-shadow', 'background'],
                                        },{
                                            name: 'Extra',
                                            open: false,
                                            buildProps: ['opacity', 'transition', 'perspective', 'transform'],
                                            properties: [{
                                                type: 'slider',
                                                property: 'opacity',
                                                defaults: 1,
                                                step: 0.01,
                                                max: 1,
                                                min:0,
                                            }]
                                        },{
                                            name: 'Flex',
                                            open: false,
                                            properties: [{
                                                name    : 'Flex Container',
                                                property  : 'display',
                                                type    : 'select',
                                                defaults  : 'block',
                                                list    : [{
                                                    value     : 'block',
                                                    name   : 'Disable',
                                                },{
                                                    value   : 'flex',
                                                    name   : 'Enable',
                                                }],
                                            },{
                                                name: 'Flex Parent',
                                                property: 'label-parent-flex',
                                            },{
                                                name      : 'Direction',
                                                property  : 'flex-direction',
                                                type    : 'radio',
                                                defaults  : 'row',
                                                list    : [{
                                                    value   : 'row',
                                                    name    : 'Row',
                                                    className : 'icons-flex icon-dir-row',
                                                    title   : 'Row',
                                                },{
                                                    value   : 'row-reverse',
                                                    name    : 'Row reverse',
                                                    className : 'icons-flex icon-dir-row-rev',
                                                    title   : 'Row reverse',
                                                },{
                                                    value   : 'column',
                                                    name    : 'Column',
                                                    title   : 'Column',
                                                    className : 'icons-flex icon-dir-col',
                                                },{
                                                    value   : 'column-reverse',
                                                    name    : 'Column reverse',
                                                    title   : 'Column reverse',
                                                    className : 'icons-flex icon-dir-col-rev',
                                                }],
                                            },{
                                                name      : 'Wrap',
                                                property  : 'flex-wrap',
                                                type    : 'radio',
                                                defaults  : 'nowrap',
                                                list    : [{
                                                    value   : 'nowrap',
                                                    title   : 'Single line',
                                                },{
                                                    value   : 'wrap',
                                                    title   : 'Multiple lines',
                                                },{
                                                    value   : 'wrap-reverse',
                                                    title   : 'Multiple lines reverse',
                                                }],
                                            },{
                                                name      : 'Justify',
                                                property  : 'justify-content',
                                                type    : 'radio',
                                                defaults  : 'flex-start',
                                                list    : [{
                                                    value   : 'flex-start',
                                                    className : 'icons-flex icon-just-start',
                                                    title   : 'Start',
                                                },{
                                                    value   : 'flex-end',
                                                    title    : 'End',
                                                    className : 'icons-flex icon-just-end',
                                                },{
                                                    value   : 'space-between',
                                                    title    : 'Space between',
                                                    className : 'icons-flex icon-just-sp-bet',
                                                },{
                                                    value   : 'space-around',
                                                    title    : 'Space around',
                                                    className : 'icons-flex icon-just-sp-ar',
                                                },{
                                                    value   : 'center',
                                                    title    : 'Center',
                                                    className : 'icons-flex icon-just-sp-cent',
                                                }],
                                            },{
                                                name      : 'Align',
                                                property  : 'align-items',
                                                type    : 'radio',
                                                defaults  : 'center',
                                                list    : [{
                                                    value   : 'flex-start',
                                                    title    : 'Start',
                                                    className : 'icons-flex icon-al-start',
                                                },{
                                                    value   : 'flex-end',
                                                    title    : 'End',
                                                    className : 'icons-flex icon-al-end',
                                                },{
                                                    value   : 'stretch',
                                                    title    : 'Stretch',
                                                    className : 'icons-flex icon-al-str',
                                                },{
                                                    value   : 'center',
                                                    title    : 'Center',
                                                    className : 'icons-flex icon-al-center',
                                                }],
                                            },{
                                                name: 'Flex Children',
                                                property: 'label-parent-flex',
                                            },{
                                                name:     'Order',
                                                property:   'order',
                                                type:     'integer',
                                                defaults :  0,
                                                min: 0
                                            },{
                                                name    : 'Flex',
                                                property  : 'flex',
                                                type    : 'composite',
                                                properties  : [{
                                                    name:     'Grow',
                                                    property:   'flex-grow',
                                                    type:     'integer',
                                                    defaults :  0,
                                                    min: 0
                                                },{
                                                    name:     'Shrink',
                                                    property:   'flex-shrink',
                                                    type:     'integer',
                                                    defaults :  0,
                                                    min: 0
                                                },{
                                                    name:     'Basis',
                                                    property:   'flex-basis',
                                                    type:     'integer',
                                                    units:    ['px','%',''],
                                                    unit: '',
                                                    defaults :  'auto',
                                                }],
                                            },{
                                                name      : 'Align',
                                                property  : 'align-self',
                                                type      : 'radio',
                                                defaults  : 'auto',
                                                list    : [{
                                                    value   : 'auto',
                                                    name    : 'Auto',
                                                },{
                                                    value   : 'flex-start',
                                                    title    : 'Start',
                                                    className : 'icons-flex icon-al-start',
                                                },{
                                                    value   : 'flex-end',
                                                    title    : 'End',
                                                    className : 'icons-flex icon-al-end',
                                                },{
                                                    value   : 'stretch',
                                                    title    : 'Stretch',
                                                    className : 'icons-flex icon-al-str',
                                                },{
                                                    value   : 'center',
                                                    title    : 'Center',
                                                    className : 'icons-flex icon-al-center',
                                                }],
                                            }]
                                        }

                                        ],

                                    },


                                });


                            window.editor = editor;

                            var pnm = editor.Panels;
                            pnm.addButton('options', [{
                                id: 'undo',
                                className: 'fa fa-undo icon-undo',
                                command: function(editor, sender) {
                                    sender.set('active', 0);
                                    editor.UndoManager.undo(1);
                                },
                                attributes: { title: 'Undo (CTRL/CMD + Z)'}
                            },{
                                id: 'redo',
                                className: 'fa fa-repeat icon-redo',
                                command: function(editor, sender) {
                                    sender.set('active', 0);
                                    editor.UndoManager.redo(1);
                                },
                                attributes: { title: 'Redo (CTRL/CMD + SHIFT + Z)' }
                            },{
                                id: 'clean-all',
                                className: 'fa fa-trash icon-blank',
                                command:  function(editor, sender) {
                                    if(sender) sender.set('active', false);
                                    if(confirm('Are you sure to clean the Email?')) {
                                        editor.DomComponents.clear();
                                        setTimeout(function() {
                                            localStorage.clear();
                                        }, 0);
                                    }
                                },
                                attributes: { title: 'Empty canvas' }
                            },{
                                id: 'storeData',
                                className: 'fa fa-floppy-o icon-blank',
                                command: function(editor, sender) {
                                    if(sender) sender.set('active', false);
                                    if(confirm('Save Email?')) {
                                        editor.store();
                                        setTimeout(function() {
                                            //localStorage.clear();
                                            window.location = '<?php //bloginfo( "url"); ?>/marketing-manager/';
                                        }, 0);
                                    }
                                },
                                attributes: { title: 'Save Canvas' }
                            }]);

                            var bm = editor.BlockManager;
                            /*
							bm.add('link-block', {
							  label: 'Link Block',
							  attributes: {class:'fa fa-link'},
							  category: 'Basic',
							  content: {
								type:'link',
								editable: false,
								droppable: true,
								style:{
								  display: 'inline-block',
								  padding: '5px',
								  'min-height': '50px',
								  'min-width': '50px'
								}
							  },
							});*/

                            var domc = editor.DomComponents;
                            var defaultType = domc.getType('default');
                            var defaultModel = defaultType.model;
                            var defaultView = defaultType.view;

							/*domc.addType('default', {
							  model: defaultModel.extend({
								defaults: Object.assign({}, defaultModel.prototype.defaults, {
								  traits: [{
									name: 'title',
									label: 'Título',
									placeholder: 'Insira um texto aqui'
								  }]
								}),
							  }),
							});*/

                            var originalImage = domc.getType('image');

                            domc.addType('myImage', {
                                model: originalImage.model.extend({
                                    // Override how the component is rendered to HTML
                                    toHTML: function() {
                                        var src = jQuery('#myImage').html();
                                        console.log(src);
                                        return '<img src="'+src+'"/>';
                                    },
                                }, {
                                    isComponent: function(el) {
                                    },
                                }),
                                view: defaultType.view.extend({
                                    // By default the view will get the tagName from the model
                                    tagName: 'div',

                                    // The render() should return 'this'
                                    render: function () {
                                        jQuery.ajax({
                                            url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                                            type: "POST",
                                            data: {action: 'fancyboxImageSelector', emailEditor: true},
                                            success: function (result) {
                                                jQuery.fancybox.open({
                                                    width: 1040,
                                                    height: 540,
                                                    fitToView: false,
                                                    autoSize: false,
                                                    content: result,
                                                });
                                            }
                                        });
                                        // Extend the original render method
                                        defaultType.view.prototype.render.apply(this, arguments);

                                        /*var imgPreview = document.createElement('img');

                                        imgPreview.src = 'https://internationalprom.com/wp-content/uploads/2018/01/jovani-59210-b-prom-dresses-images.jpg';
                                        console.log(imgPreview);
                                        this.el.appendChild(imgPreview);*/

                                        // Here you apply all your custom logic
                                        var thisElement = this;
                                        var timer = setInterval(function(){
                                            var src = jQuery('#myImage').html();
                                            //jQuery('#myImage').html('');
                                            if(src != '') {
                                                var imgPreview = document.createElement('img');
                                                imgPreview.src = src;
                                                //console.log(imgPreview);
                                                thisElement.el.appendChild(imgPreview);
                                                clearInterval(timer);
                                            }
                                        }, 500);

                                        return this;
                                    },
                                }),
                            });


                            // Store and load events
                            editor.on('storage:load', function(e) {
                                //console.log('LOAD ', e);
                            })
                            editor.on('storage:store', function(e) {
                                //console.log('STORE ', e);
                            })

                            editor.on('styleManager:change:text-shadow', function(view) {
                                var model = view.model;
                                var targetValue = view.getTargetValue({ignoreDefault: 1});
                                var computedValue = view.getComputedValue();
                                var defaultValue = view.model.getDefaultValue();
                                //console.log('Style of ', model.get('property'), 'Target: ', targetValue, 'Computed:', computedValue, 'Default:', defaultValue);
                            });

                            editor.render();
                        });
					</script>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	body, html{ height: 100%; margin: 0;}
</style>
<?php get_footer(); ?>
