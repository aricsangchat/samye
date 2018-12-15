<?php
if(!current_user_can('edit_posts') && is_post_type_archive(array('quiz','question','assignment','unit')))
    wp_die(__('Permission denied','vibe'));

get_header();
?>
<section id="content">
	<div class="container">
        <div class="row">
    		<div class="col-md-9 col-sm-8">
    			<div class="content">
                    <div class="pagetitle samye_breadcrumbs">
                        <?php vibe_breadcrumbs(); ?>  
                        <h1><?php

                        if(is_month()){
                            single_month_title(' ');
                        }elseif(is_year()){
                            echo get_the_time('Y');
                        }else if(is_category()){
                            echo single_cat_title();
                        }else if(is_tag()){
                             single_tag_title();
                        }else{
                            post_type_archive_title();
                        }
                         ?></h1>
                        <h5><?php echo term_description(); ?></h5>
                    </div>



<p><h2>Categories</h2>
<div class="tagcloud">
<?php $args = array(
	'smallest'                  => 8, 
	'largest'                   => 22,
	'unit'                      => 'pt', 
	'number'                    => 45,  
	'format'                    => 'flat',
	'separator'                 => " ",
	'orderby'                   => 'name', 
	'order'                     => 'ASC',
	'exclude'                   => 'wisdom', 
	'include'                   => null, 
	'topic_count_text_callback' => default_topic_count_text,
	'link'                      => 'view', 
	'taxonomy'                  => 'category', 
	'echo'                      => true,
); 
wp_tag_cloud( $args ); ?></div>
</p>

    				<?php
                        if ( have_posts() ) : while ( have_posts() ) : the_post();

                        $categories = get_the_category();
						$post_type = get_post_type(); // <!-- j00lz -->

						if ( $post_type == 'post' ) { $post_type = 'Wisdom Blog'; } else { $post_type = 'Guru Rinpoche Day Message'; }

                        $cats='<ul>';
                        if($categories){
                            foreach($categories as $category) {
                                $cats .= '<li><a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '">'.$category->cat_name.'</a></li>';
                            }
                        }
                        $cats .='</ul>';
                            
                           echo ' <div class="blogpost">
                                <div class="meta">
                                   <div class="date">
                                    <p class="day"><span>'.sprintf('%02d', get_the_time('j')).'</span></p>
                                    <p class="month">'.get_the_time('M').'\''.get_the_time('y').'</p>
                                   </div>
                                </div>
                                '.(has_post_thumbnail(get_the_ID())?'
                                <div class="featured">
                                    <a href="'.get_permalink().'">'.get_the_post_thumbnail(get_the_ID(),'thumbnail').'</a>
                                </div>':'').'
                                <div class="excerpt '.(has_post_thumbnail(get_the_ID())?'thumb':'').'">
                                    <h3><a href="'.get_permalink().'">'.get_the_title().'</a></h3>
                                    <!-- <p>| <div class="cats">
                                        '.$cats.'
                                        
                                        <a href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'">'.get_the_author_meta( 'display_name' ).'</a>
                                        </p> 
                                    </div>-->
                                    <p>'.get_the_excerpt().'</p>
									'. $post_type .' <!-- j00lz -->
                                    <a href="'.get_permalink().'" class="link">'.__('Read More','vibe').'</a>
                                </div>
                            </div>';
                        endwhile;
                        endif;
                        pagination();
                    ?>
    			</div>

<div class="content">

<p><h2>Tags</h2>
<div class="tagcloud">
<?php $args = array(
	'smallest'                  => 8, 
	'largest'                   => 22,
	'unit'                      => 'pt', 
	'number'                    => 45,  
	'format'                    => 'flat',
	'separator'                 => " ",
	'orderby'                   => 'name', 
	'order'                     => 'ASC',
	'exclude'                   => 'wisdom', 
	'include'                   => null, 
	'topic_count_text_callback' => default_topic_count_text,
	'link'                      => 'view', 
	'taxonomy'                  => 'post_tag', 
	'echo'                      => true,
); 
wp_tag_cloud( $args ); ?></div>
</p>
</div>

    		</div>
    		<div class="col-md-3 col-sm-4">
    			<div class="sidebar">
                    <?php
                    $sidebar = apply_filters('wplms_sidebar','mainsidebar');
                    if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar($sidebar) ) : ?>
                    <?php endif; ?>
    			</div>
    		</div>
        </div>
	</div>
</section>

<?php
get_footer();
?>