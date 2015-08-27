
<?php





global $mk_options;
	$page_layout = $mk_options['archive_page_layout'];
	$loop_style = $mk_options['archive_loop_style'];
	$pagination_style = $mk_options['archive_pagination_style'];
	$image_height = $mk_options['archive_blog_image_height'];
	$meta = $mk_options['archive_blog_meta'];



get_header(); 
$cat_id=get_query_var('cat');

?>
<div id="theme-page">
	<div class="mk-main-wrapper-holder" >
		<div id="mk-page-id-10" class="theme-page-wrapper mk-main-wrapper full-layout no-padding mk-grid vc_row-fluid">
			<div class="theme-content no-padding" itemprop="mainContentOfPage">
										</div></div></div><div class="wpb_row vc_row  vc_row-fluid  mk-fullwidth-true  attched-false vc_row-fluid" style="margin-bottom:25px;">
	<div style="" class="vc_col-sm-12 wpb_column column_container ">
			<div class="wpb_row vc_row  vc_row-fluid hidden-sm mk-fullwidth-false  attched-false vc_row-fluid">
	<div style="" class="vc_col-sm-12 wpb_column column_container ">
			<div class="wpb_revslider_element wpb_content_element">
<div id="rev_slider_1_1_wrapper" class="rev_slider_wrapper fullwidthbanner-container" style="margin:0px auto;background-color:#E9E9E9;padding:0px;margin-top:0px;margin-bottom:0px;max-height:500px;">
<!-- START REVOLUTION SLIDER 4.6.5 fullwidth mode -->
	<div id="rev_slider_1_1" class="rev_slider fullwidthabanner" style="display:block;max-height:500px;">

		<!-- MAIN IMAGE -->
		<img src="<?php bloginfo('template_url'); ?>/images/categoria_<?= $cat_id; ?>.jpg"  data-bgposition="center top" data-bgfit="cover" data-bgrepeat="no-repeat" style="width:100%">
</div></div></div></div></div></div></div>
            
           
<!--           <div class="tp-bgimg defaultimg" data-lazyload="undefined" data-bgfit="cover" data-bgposition="center top" data-bgrepeat="no-repeat" data-lazydone="undefined" src="<?php bloginfo('template_url'); ?>/images/categoria_<?= $cat_id; ?>.jpg" data-src="<?php bloginfo('template_url'); ?>/images/categoria_<?= $cat_id; ?>.jpg" style="width: 100%; height: 100%;background-image: url(http://monzon8.es/wordpress-qah/wp-content/uploads/2015/06/slider_portada1.jpg); background-color: rgba(0, 0, 0, 0); background-size: cover; background-position: 50% 0%; background-repeat: no-repeat;"></div> -->
           
 

<?php 




echo do_shortcode("[vc_column_text][monzon_grid  categoria='$cat_id' numposts=24][/vc_column_text]"); ?>
<div style="height:32px;"></div>
</div></div>
			
		
		<?php if ( $page_layout != 'full' ) get_sidebar(); ?>
		<div class="clearboth"></div>
		</div>
	</div>	
</div>
<?php get_footer(); ?>
