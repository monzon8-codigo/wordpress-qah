
<div class="mk-main-wrapper-holder">
<div class="theme-page-wrapper full-layout mk-grid vc_row-fluid no-padding">
<div class="theme-content no-padding"><!-- Row Backgrounds -->
<div class="wpb_row vc_row  vc_row-fluid  mk-fullwidth-false  attched-false vc_row-fluid">
	<div style="" class="vc_col-sm-12 wpb_column column_container ">
			<!-- vc_grid start -->
<div class="vc_grid-container-wrapper vc_clearfix">
				

<div class="vc_grid-container vc_clearfix wpb_content_element vc_basic_grid" data-vc-request="/wordpress-qah/wp-admin/admin-ajax.php" data-vc-post-id="10">
	
<link rel="stylesheet" id="ssb-css-css" href="http://monzon8.es/wordpress-qah/wp-content/plugins/social-share-button/css/ssb-style.css?ver=4.2.2" type="text/css" media="all">
<link rel="stylesheet" id="ssb-admin-css-css" href="http://monzon8.es/wordpress-qah/wp-content/plugins/social-share-button/css/ssb-admin.css?ver=4.2.2" type="text/css" media="all">
<link rel="stylesheet" id="ParaAdmin-css" href="http://monzon8.es/wordpress-qah/wp-content/plugins/social-share-button/ParaAdmin/css/ParaAdmin.css?ver=4.2.2" type="text/css" media="all">

<div class="vc_grid vc_row vc_grid-gutter-10px vc_pageable-wrapper vc_hook_hover" >
<div class="vc_pageable-slide-wrapper vc_clearfix" data-vc-grid-content="true">

<?php
global $post;
$args = array( 'numberposts' => $numposts, 'meta_key' => $meta_key, 'orderby' => 'meta_value_num',  'order' => 'DESC' );

$myposts = get_posts( $args );


foreach( $myposts as $post ) :  setup_postdata($post); 

	//Obtener Categoría
	$category_detail=get_the_category($post->ID);

	//Obtener LIKES
	
	$obj_fb = json_decode( file_get_contents( 'http://graph.facebook.com/?id='.get_permalink() ) );
	$likes_fb = $obj_fb->shares;
	
	//Obtener VIEWS
	$post_views = get_post_meta($post->ID, 'post_views_count', true);

		//Obtener RANKING
	$ranking = get_post_meta($post->ID, 'ranking', true);

	
	$cat_id=$category_detail[0]->category_parent;
	$cat=get_cat_name($cat_id);

	$category_id=get_cat_ID($cat);
	$category_link=get_category_link($category_id);
	
	if ($meta_key=="fb_likes")
		$valor=$likes_fb;
	else
		if ($meta_key=="ranking")
			$valor=$ranking;
		else
			$valor=$post_views;

	if (!($cat_id>0))
		$cat_id=$category_detail[0]->term_id;
		$cat=get_cat_name($cat_id);
	
		$category_id=get_cat_ID($cat);
		$category_link=get_category_link($category_id);

	$cat_img=z_taxonomy_image_url($cat_id);
	//Obtener Imagen destacada
	 $image = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ));
	 ?>
	
    <div class="vc_grid-item vc_clearfix vc_col-sm-3 vc_grid-term-7  vc_grid-term-9  vc_visible-item zoomIn animated">
    	<div class="vc_grid-item-mini vc_clearfix  ">
        	<div id="custom-bg" class=" vc_gitem-zone vc_gitem-zone-a vc_custom_1429260595175  vc-gitem-zone-height-mode-auto vc-gitem-zone-height-mode-auto-1-1 vc_gitem-is-link" style="background-image:linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url('<?= $image; ?>');">
	
    
    <a href="<?= get_the_permalink(); ?>" title="<?= get_the_title(); ?>" class="vc_gitem-link vc-zone-link" ></a>		<div class="vc_gitem-zone-mini">
		
        <div class="vc_gitem_row vc_row vc_gitem-row-position-top"><div class="vc_col-sm-8 vc_gitem-col vc_gitem-col-align-left"></div><div class="vc_col-sm-4 vc_gitem-col vc_gitem-col-align-left vc_custom_1429264200469"></div></div>
        
        <div class="vc_gitem_row vc_row vc_gitem-row-position-middle"><div class="vc_col-sm-8 vc_gitem-col vc_gitem-col-align-left vc_custom_1429266746145"><div class="vc_custom_heading vc_gitem-post-data vc_gitem-post-data-source-post_title">
        
        <h6 class="titulo-art"><?= get_the_title(); ?></h6>
        
        </div></div><div class="vc_col-sm-4 vc_gitem-col vc_gitem-col-align-left"></div></div>
        
        <div class="vc_gitem_row vc_row vc_gitem-row-position-bottom"><div class="vc_col-sm-8 vc_gitem-col vc_gitem-col-align-left"></div><div class="vc_col-sm-4 vc_gitem-col vc_gitem-col-align-left vc_custom_1429264208292"></div></div>	</div>
    <?


   // echo '<div class="titulo-articulo"><a href="'.get_the_permalink().' " style="color:white">'.get_the_title().'</a></div>';
	echo '<a href="'.esc_url( $category_link ).'"><div class="img-categoria" style="background:url('.$cat_img.') no-repeat; z-index:999; position:absolute; top:320px; background-position:right; height:70px; width:100%; color:white; text-align:right; font-weight:500; padding-top:47px;"><div class="nom-categoria">'.$cat.'</div></div></a>';
	echo '<div class="nom-autor">'.get_the_author()." - ".$valor.'</div>';
	echo '</div></div></div>';
endforeach;
wp_reset_postdata(); 
?>
</div></div></div></div></div></div></div></div></div>