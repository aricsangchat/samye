<?php
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(vibe_get_header());
?>

<section id="page-title" style="background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/bg-page-title.png);">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h2>
                    <?php
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
                    ?>
                </h2>
            </div>
        </div>
    </div>
</section>

<section id="content" class="archive">
	<div class="<?php echo vibe_get_container(); ?>">
        <div class="row">
    		<div class="col-md-9 col-sm-8">
    			<div class="content">
                    <div class="term-description">
                        <?php echo term_description(); ?>
                    </div>
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