<?php
/*
Plugin Name: TPT Blog Authors Widget
Plugin URI: http://www.temperatio.com/tpt-blog-authors-widget
Description: Provides a widget to list blog authors, including gravatars, post counts, and bios. Based in Simple Blog Authors Widget by Pippin Williamson (http://pippinsplugins.com/simple-blog-authors-widget/)
Version: 0.1
Author: César Gómez
Author URI: http://www.temperatio.com
*/

/**
 * Authors Widget Class
 */
class tpt_authors_widget extends WP_Widget {

  /** constructor */
  function tpt_authors_widget() {
    parent::WP_Widget(false, $name = 'TPT Authors Widget');
  }
  
  /** @see WP_Widget::widget */
  function widget($args, $instance) {	
    extract( $args );
    global $wpdb;
    
    $title = apply_filters('widget_title', $instance['title']);
    $gravatar = $instance['gravatar'];
    $count = $instance['count'];
    $name = $instance['count'];
		
		if(!$size)
      $size = 40;

    echo $before_widget;
    if ( $title )
      echo $before_title . $title . $after_title;
		echo "<ul>";
    $authors = get_users( array( 'who' => 'authors', 'number' => 99999) );

    foreach($authors as $author) {
    	
      $post_count = count_user_posts($author->ID);
      									
      if( $post_count >= 1 ) {									
      										
        $author_info = get_userdata($author->ID);
        	
        echo '<li class="sbaw_author">';
        
        if( $gravatar ) {											
          echo '<div class="tpt-gravatar-container">';
          echo '<a href="' . get_author_posts_url($author->ID) .'" title="View author archive">';
          echo get_avatar($author->ID, 40);
          echo "</a>";
          echo '</div>';
        }
        
        if($name){
          echo '<a href="' . get_author_posts_url($author->ID) .'" title="View author archive">';
          echo $author_info->display_name;
          if($count) {
            echo '(' . $post_count . ')';
          }
          echo '</a>';
        }	
        
        echo '</li>';
        				
      }
    }							
    echo	"</ul>";
    echo $after_widget; 
  }

  /** @see WP_Widget::update */
  function update($new_instance, $old_instance) {		
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['gravatar'] = strip_tags($new_instance['gravatar']);
    $instance['name'] = strip_tags($new_instance['name']);
    $instance['count'] = strip_tags($new_instance['count']);
    return $instance;
  }

  /** @see WP_Widget::form */
  function form($instance) {	
    $title = esc_attr($instance['title']);
    $gravatar = esc_attr($instance['gravatar']);
    $count = esc_attr($instance['count']);
		
?>
    <p>
      <label for="<?php echo $this -> get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
      <input class="widefat" id="<?php echo $this -> get_field_id('title'); ?>" name="<?php echo $this -> get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
    <p>
      <input id="<?php echo $this -> get_field_id('name'); ?>" name="<?php echo $this -> get_field_name('name'); ?>" type="checkbox" value="1" <?php checked('1', $count); ?>/>
      <label for="<?php echo $this -> get_field_id('name'); ?>"><?php _e('Display Author Name?'); ?></label> 
    </p>
    <p>
      <input id="<?php echo $this -> get_field_id('count'); ?>" name="<?php echo $this -> get_field_name('count'); ?>" type="checkbox" value="1" <?php checked('1', $count); ?>/>
      <label for="<?php echo $this -> get_field_id('count'); ?>"><?php _e('Display Post Count?'); ?></label> 
    </p>
    <p>
      <input id="<?php echo $this -> get_field_id('gravatar'); ?>" name="<?php echo $this -> get_field_name('gravatar'); ?>" type="checkbox" value="1" <?php checked('1', $gravatar); ?>/>
      <label for="<?php echo $this -> get_field_id('gravatar'); ?>"><?php _e('Display Author Gravatar?'); ?></label> 
    </p>
<?php
  }
} // class utopian_recent_posts

// register Recent Posts widget
add_action('widgets_init', create_function('', 'return register_widget("tpt_authors_widget");'));
