<?php

global $mk_options;
	$page_layout = $mk_options['archive_page_layout'];
	$loop_style = $mk_options['archive_loop_style'];
	$pagination_style = $mk_options['archive_pagination_style'];
	$image_height = $mk_options['archive_blog_image_height'];
	$meta = $mk_options['archive_blog_meta'];



get_header(); ?>
<?php
$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
?>
<div id="theme-page">
	<div class="mk-main-wrapper-holder">

		<div class="theme-content">
			<div class="author-photo">
				<?php echo get_avatar( $curauth->user_email, '300' ); ?>
			</div>
			<div class="author-info">
			<div class="display-author-info">
<h3><?php echo $curauth->display_name; ?></h3>
<p id="author-position"><?php echo $curauth->description; ?></p>
<p id="rrss-position"><?php if(!empty($curauth->user_email)) {
    echo '<a id="author-email" title="Email" href="mailto:'.$curauth->user_email.'"><img src="http://monzon8.es/wordpress-qah/wp-content/uploads/2015/05/email.png" alt="" /></a>';
}
if(!empty($curauth->twitter)) {
    echo '<a title="Twitter" href="http://'.$curauth->twitter.'"><img src="http://monzon8.es/wordpress-qah/wp-content/uploads/2015/05/twitter.png" alt="" /></a>';
}
if(!empty($curauth->linkedin)) {
    echo '<a title="LinkedIn" href="http://'.$curauth->linkedin.'"><img src="http://monzon8.es/wordpress-qah/wp-content/uploads/2015/05/linkedin.png" alt="" /></a>';
}?>
</p>
			</div>
			</div>
		</div>

		<div class="theme-page-wrapper <?php echo $page_layout; ?>-layout  mk-grid vc_row-fluid row-fluid">
			<div class="theme-content">
				<?php
					echo do_shortcode( '[mk_blog style="'.$loop_style.'" grid_image_height="'.$image_height.'" disable_meta="'.$meta.'" pagination_style="'.$pagination_style.'"]' );
	?>
			</div>
		<?php if ( $page_layout != 'full' ) get_sidebar(); ?>
		<div class="clearboth"></div>
		</div>
	</div>	
</div>
<?php get_footer(); ?>
