<?php
get_header();
if ( have_posts() ) : while ( have_posts() ) : the_post();


$title=get_post_meta(get_the_ID(),'vibe_title',true);
$template = get_post_meta(get_the_ID(),'vibe_template',true);
?>
<section id="content">
    <div class="container">
        <?php if($template == 'right'){ ?>
        <div class="pagetitle samye_breadcrumbs">
            <?php 
            $breadcrumbs=get_post_meta(get_the_ID(),'vibe_breadcrumbs',true);
            if(!isset($breadcrumbs) || !$breadcrumbs || vibe_validate($breadcrumbs)){
            vibe_breadcrumbs();
            }   
            ?>
            <h1><?php the_title(); ?></h1>
            <?php the_sub_title(); ?>
        </div>
        <?php } ?>
        <div class="row">
            <?php
                if($template == 'right'){
                    echo '<div class="col-md-9 col-sm-8 right">';
                }else if($template == 'full'){
                    echo '<div class="col-md-12">';
                }else{
                    echo '<div class="col-md-9 col-sm-8 ">';
                }

            ?>
                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="content">
                    <?php if($template != 'right'){ ?>
                    <div class="pagetitle samye_breadcrumbs">
                        <?php 
                        $breadcrumbs=get_post_meta(get_the_ID(),'vibe_breadcrumbs',true);
                        if(!isset($breadcrumbs) || !$breadcrumbs || vibe_validate($breadcrumbs)){
                        vibe_breadcrumbs();
                        }   
                        ?>
                        <h1><?php the_title(); ?></h1>
                        <?php the_sub_title(); ?>

                        <?php
                    echo ' <div class="blogpost">
                                <div class="meta" style="float: right; margin-top: -4em;">
                                   <div class="date">
                                    <p class="day"><span>'.sprintf('%02d', get_the_time('j')).'</span></p>
                                    <p class="month">'.get_the_time('M').'\''.get_the_time('y').'</p>
                                   </div>
                                </div>
                                '.(has_post_thumbnail(get_the_ID())?'
                                <div class="featured">
                                    <a href="'.get_permalink().'">'.get_the_post_thumbnail(get_the_ID(),'full').'</a>
                                </div>':'').'
                            </div>';
                    ?>
                    </div>

                    <?php } ?>

                    <?php if( has_post_thumbnail() &&  ( ! in_category('public-teachings') ) ){ ?>

                    <div class="featured">
                        <?php the_post_thumbnail(get_the_ID(),'full'); ?>
                    </div>
                    <?php
                    }
                    global $more; $more=0;
                    the_content();
                    

                     ?>
                     <div class="tags">
                    <?php echo '<div class="indate"><i class="icon-clock"></i> ';the_date();echo '</div>';the_tags('<ul><li>','</li><li>','</li></ul>'); ?>
                    <?php wp_link_pages('before=<div class="page-links"><ul>&link_before=<li>&link_after=</li>&after=</ul></div>'); ?>
                        <div class="social_sharing">
                             <?php echo social_sharing(); ?>   
                        </div>
                    </div>
                </div>
                <?php
                        $prenex=get_post_meta(get_the_ID(),'vibe_prev_next',true);
                        if(vibe_validate($prenex)){
                    ?>
                    <div class="prev_next_links">
                        <ul class="prev_next">
                            <?php 
                            $prev_post = get_previous_post();
                            $next_post = get_next_post();
                            echo '<li>';
                            if(isset($prev_post))
                            echo '<a href="'.get_permalink($prev_post->ID).'" class="prev">'.get_the_post_thumbnail($prev_post->ID,'thumbnail').'<span>'.$prev_post->post_title.'</span></a>';

                            echo '<li>';
                            if(isset($next_post))
                            echo '<a href="'.get_permalink($next_post->ID).'" class="next"><span>'.$next_post->post_title.'</span>'.get_the_post_thumbnail($next_post->ID,'thumbnail').'</a>';
                            echo '</li>';
                            ?>
                        </ul>    
                    </div>
                    <?php
                        }
                    ?>
                </div>
                
                <?php
                    $author = getPostMeta($post->ID,'vibe_author',true);
                    if(vibe_validate($author)){ ?>
                    <div class="postauthor">
                        <div class="auth_image">
                            <?php
                                echo get_avatar( get_the_author_meta('email'), '160');
                             ?>
                        </div>
                        <div class="author_info">
                            <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="readmore link"><?php printf(__('Courses from %s','vibe'),get_the_author_meta( 'display_name' )); ?></a>
                            <h6><?php the_author_meta( 'display_name' ); ?></h6>
                            <div class="author_desc">
                                <p>
                                 <?php  the_author_meta( 'description' );?>
                                 </p>
                                 <p class="website">Website : <a href="<?php  the_author_meta( 'url' );?>" target="_blank"><?php  the_author_meta( 'url' );?></a></p>
                                         <?php
                                $author_id=  get_the_author_meta('ID');
                                vibe_author_social_icons($author_id);
                            ?>  
                                
                            </div>     
                        </div>    
                    </div>
                    <?php
                    } 
                if( comments_open() ) {
                    comments_template();
                }
                endwhile;
                endif;
                ?>

            </div>
            <?php
             if($template != 'full'){
            ?>
            <div class="col-md-3 col-sm-3">
                <div class="sidebar">
                    <?php
                    $sidebar = apply_filters('wplms_sidebar','mainsidebar',get_the_ID());
                    if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar($sidebar) ) : ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php
                }
            ?>
        </div>
    </div>
</section>
</div>

<?php
get_footer();
?>