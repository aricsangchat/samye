<?php

if (isset($_GET["post_type"]) && $_GET["post_type"] == 'course'){ 
    if(file_exists(get_stylesheet_directory(). '/search-incourse.php')){
        load_template(get_stylesheet_directory() . '/search-incourse.php'); 
        exit();
    }
    if(file_exists(get_template_directory(). '/search-incourse.php')){
        load_template(get_template_directory() . '/search-incourse.php'); 
        exit();
    }
}

get_header(vibe_get_header());
global $wp_query;
$total_results = $wp_query->found_posts;
?>

<section id="page-title" style="background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/bg-page-title.png);">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h2>
                    <?php _e('Search Results for "', 'vibe'); the_search_query(); ?>"
                </h2>
                <h5><?php echo $total_results.__(' results found','vibe');  ?></h5>
            </div>
        </div>
    </div>
</section>



<section id="content" class="archive">
	<div class="<?php echo vibe_get_container(); ?>">
        <div class="row">
    		<div class="col-md-9 col-sm-8">
    			<div class="content">

                    <?php 
                        if (term_description()) {
                            echo '<div class="term-description">
                                    '.term_description().'
                                </div>';
                        }
                    ?>

                    
    				<?php
                        
                        if ( have_posts() ) : while ( have_posts() ) : the_post();

                        $categories = get_the_category();
                        $cats='<ul>';
                        if($categories){
                            foreach($categories as $category) {
                                $cats .= '<li><a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s","vibe" ), $category->name ) ) . '">'.$category->cat_name.'</a></li>';
                            }
                        }
                        $cats .='</ul>';
                        
                        if(function_exists('vibe_get_option')){
                            $default_archive = vibe_get_option('default_archive');
                            if(!empty($default_archive)){
                                get_template_part('content',$default_archive);
                            }else{
                               get_template_part('content','default');
                            }
                        }
                        endwhile;
                        endif;
                        pagination();
                    ?>

                    <div class="social_sharing">
                        <h3>Share This Page</h3>
                        <?php echo do_shortcode('[Sassy_Social_Share]') ?>  
                    </div>
    			</div>
    		</div>
    		<div class="col-md-3 col-sm-4">
    			<div>
                    <?php get_template_part( 'sidebar' ); ?>
    			</div>
    		</div>
        </div>
	</div>
</section>

<?php
get_footer(vibe_get_footer());
?>