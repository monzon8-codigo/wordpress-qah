<?php

global $post,
$mk_options;



$single_layout = get_post_meta( $post->ID, '_layout', true );
$padding = get_post_meta( $post->ID, '_padding', true );
$padding = ($padding == 'true') ? 'no-padding' : '';

if($single_layout == 'default' || empty($single_layout)) {
	$single_layout = $mk_options['single_layout'];
}


/*
Image dimensions
*/
$image_height = $mk_options['single_featured_image_height'];
$image_width = mk_content_width($single_layout);







function social_networks_meta() {
	$image_src_array = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full', true );
	$output = '<meta property="og:image" content="'.$image_src_array[ 0 ].'"/>'. "\n";
	$output .= '<meta property="og:url" content="'.get_permalink().'"/>'. "\n";
	$output .= '<meta property="og:title" content="'.get_the_title().'"/>'. "\n";
	echo $output;
}
add_action('wp_head', 'social_networks_meta');





get_header(); ?>





<div id="theme-page">

	<?php if ( have_posts() ) while ( have_posts() ) : the_post();

		$post_type = get_post_meta( $post->ID, '_single_post_type', true );
		$image_src_array = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full', true );
		if($mk_options['blog_single_img_crop'] == 'true') {
			require_once(THEME_FUNCTIONS . "/bfi_cropping.php");
			$image_src = bfi_thumb( $image_src_array[ 0 ], array('width' => $image_width, 'height' => $image_height));
		} else {
			$image_src = $image_src_array[ 0 ];
		}


	?>
	<div class="mk-main-wrapper-holder">
	<div class="theme-page-wrapper mk-blog-single <?php echo $single_layout; ?>-layout vc_row-fluid mk-grid <?php echo $padding; ?>">

<?php

			if($mk_options['single_disable_featured_image'] == 'true' && get_post_meta( $post->ID, '_disable_featured_image', true ) != 'false') :

			if($post_type == 'image') { ?>
				<?php if(has_post_thumbnail()) : ?>
						<div class="single-featured-image" style="margin-bottom:0 !important;">
							<div style="background:url('<?php echo mk_thumbnail_image_gen($image_src, $image_width, $image_height) ; ?>') no-repeat; max-height:450px; height:0; width:auto; background-size:cover; padding-top: 27.78%;"></div>
							<!--<img alt="<?php the_title(); ?>" title="<?php the_title(); ?>" src="<?php echo mk_thumbnail_image_gen($image_src, $image_width, $image_height) ; ?>" height="<?php echo $image_height; ?>" width="100%" itemprop="image" style="max-height:500px;background-size:cover;" />-->
						</div>
				<?php endif; ?>
			<?php }elseif ($post_type == 'portfolio'){
            $featured_image_id = get_post_thumbnail_id();
            $attachment_ids = get_post_meta($post->ID, '_gallery_images', true);
            $output_portfolio = '';

            if(!empty($attachment_ids)) {
                   if(!empty($featured_image_id)) {
                        $final_attachment_ids = $featured_image_id . ',' .  $attachment_ids;
                   } else {
                        $final_attachment_ids = $attachment_ids;
                   }
                     $output_portfolio .= '<div class="single-featured-image">';
                     $output_portfolio .= do_shortcode('[mk_swipe_slideshow images="' . $final_attachment_ids . '" image_width="' . $image_width . '" image_height="' . $image_height . '"]');
                     $output_portfolio .= '</div>';

            } else {
                $show_lightbox = get_post_meta($post->ID, '_disable_post_lightbox', true);
                $disable_lightbox  = '';
                $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true);
                  $image_output_src = $image_src_array[0];
                if (($show_lightbox == 'true' || $show_lightbox == '') && $disable_lightbox == 'true') {
                    $lightbox_code = ' class="mk-lightbox blog-modern-lightbox" data-fancybox-group="blog-modern" href="' . $lightbox_full_size[0] . '"';
                } else {
                    $lightbox_code = ' href="' . get_permalink() . '"';
                }
                $output_portfolio .= '<div class="single-featured-image"><a title="' . get_the_title() . '"' . $lightbox_code . '>';

                $output_portfolio .= '<img alt="' . get_the_title() . '" title="' . get_the_title() . '" src="' . $image_output_src . '" itemprop="image" />';

                $output_portfolio .= '<div class="image-hover-overlay"></div>';
                $output_portfolio .= '<div class="post-type-badge" href="' . get_permalink() . '"><i class="mk-jupiter-icon-' . $post_type . '"></i></div>';
                $output_portfolio .= '</a></div>';
            }
            echo $output_portfolio;
         } elseif($post_type == 'video') {
				$skin_color = $mk_options['skin_color'];
				$video_id = get_post_meta( $post->ID, '_single_video_id', true );
				$video_site  = get_post_meta( $post->ID, '_single_video_site', true );


				if($video_site =='vimeo') {
				echo '<div style="width:'.$image_width.'px;" class="mk-video-wrapper"><div class="mk-video-container"><iframe src="http'.((is_ssl())? 's' : '').'://player.vimeo.com/video/'.$video_id.'?title=0&amp;byline=0&amp;portrait=0&amp;color='.str_replace("#", "", $skin_color).'" width="'.$image_width.'" height="'.$image_height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div></div>';
				}


				if($video_site =='youtube') {
				echo '<div style="width:'.$image_width.'px;" class="mk-video-wrapper"><div class="mk-video-container"><iframe src="http'.((is_ssl())? 's' : '').'://www.youtube.com/embed/'.$video_id.'?showinfo=0&amp;theme=light&amp;color=white&amp;rel=0" frameborder="0" width="'.$image_width.'" height="'.$image_height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div></div>';
				}

				if($video_site =='dailymotion') {
				echo '<div style="width:'.$image_width.'px;" class="mk-video-wrapper"><div class="mk-video-container"><iframe src="http'.((is_ssl())? 's' : '').'://www.dailymotion.com/embed/video/'.$video_id.'?logo=0" frameborder="0" width="'.$image_width.'" height="'.$image_height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div></div>';
				}

			} elseif($post_type == 'audio') {

				 $iframe = get_post_meta($post->ID, '_audio_iframe', true);

       			 if (empty($iframe)) {

								$mp3_file  = get_post_meta( $post->ID, '_mp3_file', true );
								$ogg_file  = get_post_meta( $post->ID, '_ogg_file', true );
								$audio_author  = get_post_meta( $post->ID, '_single_audio_author', true );
								$image_src_array = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full', true );

								echo do_shortcode('[mk_audio mp3_file="'.$mp3_file.'" ogg_file="'.$ogg_file.'" thumb="'.$image_src_array[ 0 ].'" audio_author="'.$audio_author.'"]');

								

							 } else {
					            echo '<div class="audio-iframe">' . $iframe . '</div>';
					        }
					    }

							 endif;

 ?>	

		<div class="theme-content <?php echo $padding; ?>">
		<article id="<?php the_ID(); ?>" <?php post_class(); ?>>
			<!--<?php

			if($mk_options['single_disable_featured_image'] == 'true' && get_post_meta( $post->ID, '_disable_featured_image', true ) != 'false') :

			if($post_type == 'image') { ?>
				<?php if(has_post_thumbnail()) : ?>
						<div class="single-featured-image">
							<img alt="<?php the_title(); ?>" title="<?php the_title(); ?>" src="<?php echo mk_thumbnail_image_gen($image_src, $image_width, $image_height) ; ?>" height="<?php echo $image_height; ?>" width="<?php echo $image_width; ?>" itemprop="image" style="border-radius:50%;"/>
						</div>
				<?php endif; ?>
			<?php }elseif ($post_type == 'portfolio'){
            $featured_image_id = get_post_thumbnail_id();
            $attachment_ids = get_post_meta($post->ID, '_gallery_images', true);
            $output_portfolio = '';

            if(!empty($attachment_ids)) {
                   if(!empty($featured_image_id)) {
                        $final_attachment_ids = $featured_image_id . ',' .  $attachment_ids;
                   } else {
                        $final_attachment_ids = $attachment_ids;
                   }
                     $output_portfolio .= '<div class="single-featured-image">';
                     $output_portfolio .= do_shortcode('[mk_swipe_slideshow images="' . $final_attachment_ids . '" image_width="' . $image_width . '" image_height="' . $image_height . '"]');
                     $output_portfolio .= '</div>';

            } else {
                $show_lightbox = get_post_meta($post->ID, '_disable_post_lightbox', true);
                $disable_lightbox  = '';
                $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true);
                  $image_output_src = $image_src_array[0];
                if (($show_lightbox == 'true' || $show_lightbox == '') && $disable_lightbox == 'true') {
                    $lightbox_code = ' class="mk-lightbox blog-modern-lightbox" data-fancybox-group="blog-modern" href="' . $lightbox_full_size[0] . '"';
                } else {
                    $lightbox_code = ' href="' . get_permalink() . '"';
                }
                $output_portfolio .= '<div class="single-featured-image"><a title="' . get_the_title() . '"' . $lightbox_code . '>';

                $output_portfolio .= '<img alt="' . get_the_title() . '" title="' . get_the_title() . '" src="' . $image_output_src . '" itemprop="image" />';

                $output_portfolio .= '<div class="image-hover-overlay"></div>';
                $output_portfolio .= '<div class="post-type-badge" href="' . get_permalink() . '"><i class="mk-jupiter-icon-' . $post_type . '"></i></div>';
                $output_portfolio .= '</a></div>';
            }
            echo $output_portfolio;
         } elseif($post_type == 'video') {
				$skin_color = $mk_options['skin_color'];
				$video_id = get_post_meta( $post->ID, '_single_video_id', true );
				$video_site  = get_post_meta( $post->ID, '_single_video_site', true );


				if($video_site =='vimeo') {
				echo '<div style="width:'.$image_width.'px;" class="mk-video-wrapper"><div class="mk-video-container"><iframe src="http'.((is_ssl())? 's' : '').'://player.vimeo.com/video/'.$video_id.'?title=0&amp;byline=0&amp;portrait=0&amp;color='.str_replace("#", "", $skin_color).'" width="'.$image_width.'" height="'.$image_height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div></div>';
				}


				if($video_site =='youtube') {
				echo '<div style="width:'.$image_width.'px;" class="mk-video-wrapper"><div class="mk-video-container"><iframe src="http'.((is_ssl())? 's' : '').'://www.youtube.com/embed/'.$video_id.'?showinfo=0&amp;theme=light&amp;color=white&amp;rel=0" frameborder="0" width="'.$image_width.'" height="'.$image_height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div></div>';
				}

				if($video_site =='dailymotion') {
				echo '<div style="width:'.$image_width.'px;" class="mk-video-wrapper"><div class="mk-video-container"><iframe src="http'.((is_ssl())? 's' : '').'://www.dailymotion.com/embed/video/'.$video_id.'?logo=0" frameborder="0" width="'.$image_width.'" height="'.$image_height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div></div>';
				}

			} elseif($post_type == 'audio') {

				 $iframe = get_post_meta($post->ID, '_audio_iframe', true);

       			 if (empty($iframe)) {

								$mp3_file  = get_post_meta( $post->ID, '_mp3_file', true );
								$ogg_file  = get_post_meta( $post->ID, '_ogg_file', true );
								$audio_author  = get_post_meta( $post->ID, '_single_audio_author', true );
								$image_src_array = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full', true );

								echo do_shortcode('[mk_audio mp3_file="'.$mp3_file.'" ogg_file="'.$ogg_file.'" thumb="'.$image_src_array[ 0 ].'" audio_author="'.$audio_author.'"]');

								

							 } else {
					            echo '<div class="audio-iframe">' . $iframe . '</div>';
					        }
					    }

							 endif;

 ?>	-->	
					
<?php if($mk_options['single_meta_section'] == 'true' && get_post_meta( $post->ID, '_disable_meta', true ) != 'false') : ?>
							<div class="blog-single-meta">
								<!--<div class="mk-blog-author"><?php _e('By', 'mk_framework'); ?> <?php the_author_posts_link(); ?></div>-->
							<div class="mk-post-cat"><?php the_category( ', ' ) ?></div>
									<time class="mk-post-date" datetime="<?php the_date() ?>">
										<a href="<?php echo get_month_link( get_the_time( "Y" ), get_the_time( "m" ) ); ?>"><?php echo get_the_date(); ?></a>
									</time>
							</div><br/><br/>
							<?php endif; ?>

							<?php if(isset($mk_options['blog_single_title']) && !empty($mk_options['blog_single_title']) ? $mk_options['blog_single_title'] : 'true') : ?>
								<?php if($mk_options['blog_single_title'] == 'true') : ?>
 									<h2 class="blog-single-title"><?php the_title(); ?></h2>
								<?php endif; ?>
 							<?php endif; ?>


								<div class="single-social-section">
								<div class="mk-love-holder"><?php //echo mk_love_this(); ?><?php
if (function_exists('ssb_share_icons')) {
echo ssb_share_icons();
}
?><div class="comment-art"><a href="<?php echo get_permalink(); ?>#comments" class="blog-modern-comment"><i class="mk-moon-bubble-9"></i><span> <?php echo comments_number( '0', '1', '%'); ?></span></a></div></div>
								<?php
								if($mk_options['enable_blog_single_comments'] == 'true') :
										if ( get_post_meta( $post->ID, '_disable_comments', true ) != 'false' ) {
											?><!--<div class="comment-art"><a href="<?php echo get_permalink(); ?>#comments" class="blog-modern-comment"><i class="mk-moon-bubble-9"></i><span> <?php echo comments_number( '0', '1', '%'); ?></span></a></div>--><?php
										}
									endif;
								?>


								<?php if($mk_options['single_blog_social'] == 'true' ) : ?>

								<div class="blog-share-container">
									<a class="facebook-share" data-title="<?php the_title(); ?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-jupiter-icon-simple-facebook"></i></a>
								</div>
								<div class="blog-share-container">
									<a class="twitter-share" data-title="<?php the_title(); ?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-moon-twitter"></i></a>
								</div>
								<div class="blog-share-container">
									<a class="linkedin-share" data-title="<?php the_title(); ?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-jupiter-icon-simple-linkedin"></i></a>
								</div>

								<?php endif; ?>
								<a class="mk-blog-print" onClick="window.print()" href="#" title="<?php _e('Print', 'mk_framework'); ?>"><i class="mk-moon-print-3"></i></a>
							<div class="clearboth"></div>
							</div>



							<div class="clearboth"></div>
							<div class="mk-single-content" itemprop="mainContentOfPage">

<?php if($mk_options['enable_blog_author'] == 'true' && get_post_meta( $post->ID, '_disable_about_author', true ) != 'false') : ?>
						<div class="mk-about-author-wrapper" style="max-width:240px; width:30%; float:left; margin-right:25px !important; margin-bottom:15px !important; border-bottom:0; border-top:0; padding: 0 0; position:relative; z-index:999;">
								<div class="avatar-wrapper" style="width:100%; float:none;"><?php global $user; echo get_avatar( get_the_author_meta('email'), '238', false, get_the_author_meta('display_name', $user['ID'])); ?></div>
								<div class="mk-about-author-meta" style="margin:2px 0 0 2px; font-size:12px; border-top:2px solid #004770;">
								<a class="about-author-name" href="<?php echo $userpro->permalink(get_the_author_ID()); ?>"><?php the_author_meta('display_name'); ?></a>
								<ul class="about-author-social" style="border-bottom:2px solid #004770; margin-top:0;">
									<?php
									if(get_the_author_meta('email')) {
										echo '<li><a class="email-icon" title="'.__('Email','mk_framework').'" href="mailto:'.get_the_author_meta('email').'"><i class="mk-moon-envelop"></i></a></li>';
									}
									if(get_the_author_meta( 'twitter' )) {
										echo '<li><a class="twitter-icon" title="'.__('Twitter','mk_framework').'" href="'.get_the_author_meta( 'twitter' ).'"><i class="mk-moon-twitter"></i></a></li>';
									}
									if(get_the_author_meta( 'linkedin' )) {
										echo '<li><a class="linkedin-icon" title="'.__('LinkedIn','mk_framework').'" href="'.get_the_author_meta( 'linkedin' ).'"><i class="mk-moon-linkedin"></i></a></li>';
									}
									?>
								</ul>
								</div>
								<div class="clearboth"></div>
						</div>
					<?php endif; ?>

								<?php the_content(); ?>
							</div>
							<?php wp_link_pages('before=<div class="mk-page-links">'.__('Pages:', 'mk_framework').'&after=</div>'); ?>

							<div class="single-post-tags">
								<?php if($mk_options['diable_single_tags'] == 'true' && get_post_meta( $post->ID, '_disable_tags', true ) != 'false') : ?>
									<?php the_tags(); ?>
								<?php endif; ?>
							</div>


						<?php
						
						// Update FB Count
							$obj_fb = json_decode( file_get_contents( 'http://graph.facebook.com/?id='.get_permalink() ) );
							$likes_fb = $obj_fb->shares;
							if ($likes_fb=='')
								$likes_fb=0;
							update_post_meta($post->ID, 'fb_likes', $likes_fb, false);
							
							// Update Post Views
							$kjl_count_key = 'post_views_count';
							$kjl_count = get_post_meta($post->ID, $kjl_count_key, true);
							if($kjl_count=='') {
								$kjl_count=1;
								delete_post_meta($post->ID, $kjl_count_key);
								add_post_meta($post->ID, $kjl_count_key, '1');
							} else {
								$kjl_count++;
								update_post_meta($post->ID, $kjl_count_key, $kjl_count);
							}
							
							//Actualizar sumatorio de campos
							$ranking=$kjl_count+$likes_fb;
							update_post_meta($post->ID, 'ranking', $ranking);
							
							echo "RANKING".$ranking;
							
					if($mk_options['enable_single_related_posts'] == 'true' && get_post_meta( $post->ID, '_disable_related_posts', true ) != 'false') {
						do_action('blog_similar_posts', $post->ID);
					
						do_action('blog_similar_posts_monzon', $post->ID, 116, '#999','EBOOKS');
						//do_action('blog_similar_posts_monzon', $post->ID, 115, '#369','DEBATES');
					}

						?>
<div class="clearboth"></div>
					<?php
					if($mk_options['enable_blog_single_comments'] == 'true') :

						if ( get_post_meta( $post->ID, '_disable_comments', true ) != 'false' ) {
							comments_template( '', true );
						}
					endif;
					?>
			</article>
			<div class="clearboth"></div>
			</div>
				<?php endwhile; ?>
			<?php  if($single_layout != 'full') get_sidebar();  ?>
			<div class="clearboth"></div>
		</div>
	</div>
</div>
<?php get_footer(); ?>
