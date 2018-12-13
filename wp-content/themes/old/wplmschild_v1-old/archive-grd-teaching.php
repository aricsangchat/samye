<?php
if(!current_user_can('edit_posts') && is_post_type_archive(array('quiz','question','assignment','unit')))
    wp_die(__('Permission denied','vibe'));

// Redirect to main page unless viewing archive by year.
if( !is_year() ) { wp_redirect( 'http://samyedharma.org/guru-rinpoche-day-teachings' ); exit;}

get_header();
?>
<section id="content">
	<div class="container">
        <div class="row">
    		<div class="col-md-9 col-sm-8">
    			<div class="content">
                    <div class="pagetitle samye_breadcrumbs">
<ul class="breadcrumbs">
<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a rel="v:url" property="v:title" itemprop="url" href="http://samyedharma.org" ><span itemprop="name">Home</span></a></li>
<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a rel="v:url" property="v:title" itemprop="url" href="http://samyedharma.org/guru-rinpoche-day-teachings" ><span itemprop="name">Guru Rinpoche Day Teachings</span></a></li>
<li class="current"><span itemprop="title"><?php echo get_the_time('Y');?></span></li>
</ul> 
                        <h1><a href="http://samyedharma.org/guru-rinpoche-day-teachings">Guru Rinpoche Day Teachings Archive: </a>
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
                         ?></h1>
                        <h5><?php echo term_description(); ?></h5>
                    </div>

    				<?php
                        if ( have_posts() ) : while ( have_posts() ) : the_post();

                        $categories = get_the_category();
                        $cats='<ul>';
                        if($categories){
                            foreach($categories as $category) {
                                $cats .= '<li><a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '">'.$category->cat_name.'</a></li>';
                            }
                        }
                        $cats .='</ul>';
                            
                        echo '<div class="blogpost">
                        <div class="meta">
                        <div class="date">
                        <p class="day"><span>'.sprintf('%02d', get_the_time('j')).'</span></p>
                        <p class="month">'.get_the_time('M').'\''.get_the_time('y').'</p>
                        </div>
                        </div>';

                        if( has_post_thumbnail() && !empty(get_the_post_thumbnail(get_the_ID(), 'full')) ) {
                            echo '<div class="featured">
                            <a href="'.get_permalink().'">' .get_the_post_thumbnail(get_the_ID(), 'full'). '</a>
                            </div>';
                            echo '<div class="excerpt thumb">'; 
                        } else {
                            echo '<div class="excerpt">';
                        }

                        echo '<h3><a href="'.get_permalink().'">'.get_the_title().'</a></h3>
                        <div class="cats">' .$cats. '</div>
                        <p>'.get_the_excerpt().'</p>
                        <a href="'.get_permalink().'" class="link">'.__('Read More','vibe').'</a>
                        </div>
                        </div>';

                        endwhile; wp_reset_postdata();
                        endif;
                        pagination();
                    ?>
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