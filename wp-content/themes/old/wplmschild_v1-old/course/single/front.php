<?php

/**
 * The template for displaying Course font
 *
 * Override this template by copying it to yourtheme/course/single/front.php
 *
 * @author 		VibeThemes
 * @package 	vibe-course-module/templates
 * @version     2.0
 */


global $post;
$id= get_the_ID();

do_action('wplms_course_before_front_main');

//if(have_posts()):
//while(have_posts()):the_post();

?>

<div class="course_title">

<?php  
//if(!function_exists('vibe_breadcrumbs')){
//function vibe_breadcrumbs() {  

    global $post;
   
    /* === OPTIONS === */  
    $text['home']     = __('Home','vibe'); // text for the 'Home' link  
    $text['category'] = '%s'; // text for a category page  
    $text['search']   = '%s'; // text for a search results page  
    $text['tag']      = '%s'; // text for a tag page  
    $text['author']   = '%s'; // text for an author page  
    $text['404']      = 'Error 404'; // text for the 404 page  
  
    $showCurrent = apply_filters('vibe_breadcrumbs_show_title',1); // 1 - show current post/page title in breadcrumbs, 0 - don't show  
    $showOnHome  = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show  
    $delimiter   = ''; // delimiter between crumbs  
    $before      = '<li class="current"><span>'; // tag before the current crumb  
    $after       = '</span></li>'; // tag after the current crumb  
    /* === END OF OPTIONS === */  
  
    global $post;  
    $homeLink = home_url();  
    $linkBefore = '<li>';  
    $linkAfter = '</li>';  
    $linkAttr = ' ';  
    $link = $linkBefore . '<a' . $linkAttr . ' href="%1$s" ><span>%2$s</span></a>' . $linkAfter;  
  
    if (is_home() || is_front_page()) {  
  
        if ($showOnHome == 1) echo '<div id="crumbs"><a href="' . $homeLink . '">' . $text['home'] . '</a></div>';  
  
    } else {  
  
        echo '<ul class="breadcrumbs">' . sprintf($link, $homeLink, $text['home']) . $delimiter;  
  
        if ( is_category() ) {  
            $thisCat = get_category(get_query_var('cat'), false);  
            if ($thisCat->parent != 0) {  
                $cats = get_category_parents($thisCat->parent, TRUE, $delimiter);  
                $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);  
                $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);  
                echo $cats;  
            }  
            echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;  
  
        } elseif ( is_search() ) {  
            echo $before . sprintf($text['search'], get_search_query()) . $after;  
  
        } elseif ( is_day() ) {  
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;  
            echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;  
            echo $before . get_the_time('d') . $after;  
  
        } elseif ( is_month() ) {  
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;  
            echo $before . get_the_time('F') . $after;  
  
        } elseif ( is_year() ) {  
            echo $before . get_the_time('Y') . $after;  
  
        } elseif(function_exists('bp_is_directory') && bp_is_directory()){

          $component = bp_current_component();
          $page_url = get_permalink(vibe_get_bp_page_id($component));
          printf($link, $homeLink . '/' . basename($page_url) . '/', get_the_title(vibe_get_bp_page_id($component)));  

        } elseif ( is_single() && !is_attachment() ) {  

            $post_type_var = get_post_type();

            switch($post_type_var){
              case 'post':
                  $cat = get_the_category(); 
                  if(isset($cat) && is_array($cat))
                    $cat = $cat[0];  


                  $cats = get_category_parents($cat, TRUE, $delimiter);  
                  if(isset($cats) && !is_object($cats)){
                  if ($showCurrent == 0) 
                    $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);  
                  
                  $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);  

                  $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);  
                  echo $cats;  
                  }
                  global $post;
                  if ($showCurrent == 1) echo $before . $post->post_title. $after; 
              break;
              case 'product':
                  $shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
                  $post_type = get_post_type_object(get_post_type());  
                  printf($link, $homeLink . '/' . basename($shop_page_url) . '/', $post_type->labels->singular_name);  
                  global $post;
                  if ($showCurrent == 1) echo $delimiter . $before . $post->post_title . $after; 
              break;
              case 'news':
                  $course_id = get_post_meta(get_the_ID(),'vibe_news_course',true);
                  if(!empty($course_id)){
                    $course_url = get_permalink($course_id);
                  }
                  $slug = $post_type->rewrite;  
                  $post_type = get_post_type_object(get_post_type());  
                  printf($link, $homeLink . '/' . (!empty($course_id)?basename($course_url):$slug['slug']) . '/', (!empty($course_id)?get_the_title($course_id):$post_type->labels->singular_name));  
                  global $post;
                  if ($showCurrent == 1) echo $delimiter . $before . $post->post_title . $after; 
              break;
              case 'course':
                  $post_type =  get_post_type_object(get_post_type()); 
                  $course_category = get_the_term_list($post->ID, 'course-cat', '', '', '' );  
                  $slug = $post_type->rewrite;  
                  $courses_url = get_permalink(vibe_get_bp_page_id('course'));
                  if(isset($course_category)){
                    $course_category = str_replace('<a', $linkBefore . '<a' . $linkAttr, $course_category);                    
                    $course_category = str_replace('rel="tag">','rel="tag"><span itemprop="title">',$course_category);
                    $course_category = str_replace('</a>', '</span></a>' . $linkAfter, $course_category);  
                    //printf($link, $homeLink . '/' . basename($courses_url)  . '/', $post_type->labels->singular_name);  //$post_type->labels->singular_name
                    echo apply_filters('wplms_breadcrumbs_course_category',$course_category);
                  }
                  global $post;
                  if ($showCurrent == 1) echo $delimiter . $before . $post->post_title . $after; 
              break;
              case 'forum':
                  $post_type = get_post_type_object(get_post_type());  
                  $slug = $post_type->rewrite;  
                  if($slug['slug'] == 'forums/forum')
                    $slug['slug'] = 'forums';
                  printf($link, $homeLink . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
                  global $post;  
                  if ($showCurrent == 1) echo $delimiter . $before . $post->post_title . $after; 
              break;
              default:
                  $post_type = get_post_type_object(get_post_type());  

                  $slug = $post_type->rewrite;  
                  printf($link, $homeLink . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
                  global $post;  
                  if ($showCurrent == 1) echo $delimiter . $before . $post->post_title . $after; 
              break;
            }
  
        } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {  
            $post_type = get_post_type_object(get_post_type());  

            echo $before . $post_type->labels->singular_name . $after;  
  
        } elseif ( is_attachment() ) {  
            $parent = get_post($post->post_parent);  
            $cat = get_the_category($parent->ID); 
            if(isset($cat[0])){
            $cat = $cat[0];  
            $cats = get_category_parents($cat, TRUE, $delimiter);  
            $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);  
            $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);  
            echo $cats;  
            }
            printf($link, get_permalink($parent), __('Attachment','vibe'));  
            global $post;
            if ($showCurrent == 1) echo $delimiter . $before . $post->post_title . $after;  
  
        } elseif ( is_page() && !$post->post_parent ) {  
            global $post;

            $myaccount_pid = get_option('woocommerce_myaccount_page_id');
            

            if($post->ID == $myaccount_pid && is_user_logged_in()){
              $link = trailingslashit( bp_loggedin_user_domain() . $post->post_name );
              if ($showCurrent == 1) echo $before . '<a href="'.$link.'">'. $post->post_title .'</a>'. $after;  
            }else{
              if ($showCurrent == 1) echo $before . $post->post_title . $after;    
            }
  
        } elseif ( is_page() && $post->post_parent ) { 
            $parent_id  = $post->post_parent;  
            $breadcrumbs = array();  
            while ($parent_id) {  
                $page = get_page($parent_id);  
                
                $pmproaccount_pid = get_option('pmpro_account_page_id');

                if($page->ID == $pmproaccount_pid && is_user_logged_in()){
                   $permalink = trailingslashit( bp_loggedin_user_domain() .$page->post_name );
                    $breadcrumbs[] = sprintf($link, $permalink, get_the_title($page->ID));  
                }else{
                  $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));    
                }
                
                $parent_id  = $page->post_parent;  
            }  
            $breadcrumbs = array_reverse($breadcrumbs);  
            for ($i = 0; $i < count($breadcrumbs); $i++) {  
                echo $breadcrumbs[$i];  
                if ($i != count($breadcrumbs)-1) echo $delimiter;  
            }  
            global $post;
            if ($showCurrent == 1) echo $delimiter . $before .  $post->post_title . $after;  
  
        } elseif ( is_tag() ) {  
            echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;  
  
        } elseif ( is_author() ) {  
            global $author;  
            $userdata = get_userdata($author);  
            echo $before . sprintf($text['author'], $userdata->display_name) . $after;  
  
        } elseif ( is_404() ) {  
            echo $before . $text['404'] . $after;  
        }  
  
        if ( get_query_var('paged') ) {  
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';  
            echo '<li>'.__('Page','vibe') . ' ' . get_query_var('paged').'</li>';  
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';  
        }  
  
        echo '</ul>';  
    }  
 // end vibe_breadcrumbs()
 ?>  
	

	<h1><?php the_title(); ?></h1>
	<h6><?php the_excerpt(); ?></h6>
</div>
<div class="students_undertaking">
	<?php
	$students_undertaking=array();
	$students_undertaking = bp_course_get_students_undertaking();
	$students=get_post_meta(get_the_ID(),'vibe_students',true);

	echo '<strong>'.$students.__(' STUDENTS ENROLLED','vibe').'</strong>';

	echo '<ul>';
	$i=0;
	foreach($students_undertaking as $student){
		$i++;
		echo '<li>'.get_avatar($student).'</li>';
		if($i>5)
			break;
	}
	echo '</ul>';
	?>
</div>
<?php 
do_action('wplms_before_course_description');
?>
<div class="course_description">
	<?php if(!empty($post->post_excerpt) && strpos($post->post_content,$post->post_excerpt) === false){ echo '<h6>';the_excerpt(); echo '</h6>';} ?>
	<div class="small_desc">
	<?php 
		$more_flag = 1;
		$content=get_the_content(); 
		$middle=strpos( $post->post_content, '<!--more-->' );
		if($middle){
			echo apply_filters('the_content',substr($content, 0, $middle));
		}else{
			$more_flag=0;
			echo apply_filters('the_content',$content);
		}
	?>
	<?php 
		if($more_flag){
			echo '<a href="#" id="more_desc" class="link" data-middle="'.$middle.'">'.__('READ MORE','vibe').'</a>';
		}
	?>
	</div>
	<?php 
		if($more_flag){ 
	?>
		<div class="full_desc">
		<?php 
			echo apply_filters('the_content',substr($content, $middle,-1));
		?>
		<?php 
			echo '<a href="#" id="less_desc" class="link">'.__('LESS','vibe').'</a>';
		?>
		</div>
	<?php
		}
	?>	

</div>
<?php
do_action('wplms_after_course_description');
?>

<div class="course_reviews">
<?php
	 comments_template('/course-review.php',true);
?>
</div>
<?php
//endwhile;
//endif;
?>