<?php
/*
Plugin Name: Content Art Direction
Plugin URI: http://feedingtherobots.com
Description: Allows custom CSS and JS for each post or page
Version: 1.0
Author: José Marques
License:

  Copyright 2015 José Marques

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/

class ContentArtDirection {

	/*--------------------------------------------*
	 * Constants
	 *--------------------------------------------*/
	const name = 'Content Art Direction';
	const slug = 'content_art_direction';
	
	/**
	 * Constructor
	 */
	function __construct() {
		add_action( 'init', array( &$this, 'init_content_art_direction' ) );
	}
  
	function init_content_art_direction() {
		
		if ( is_admin() ) {
			add_action( 'add_meta_boxes', array( &$this, 'content_art_direction_meta' ) );
			add_action( 'save_post',  array( &$this, 'content_art_direction_save_meta' ) );
		} else {
			add_action('wp_head', array( &$this, 'content_art_direction_append_css' ) );
			add_action('wp_footer', array( &$this, 'content_art_direction_append_js' ) );
		}
		
	}

	function content_art_direction_meta() {
	  	add_meta_box( 'artdirection_meta', 'Content Art Direction', array( &$this, 'content_art_direction_add_admin_ui' ), 'post', 'advanced', 'core' );
	  	add_meta_box( 'artdirection_meta', 'Content Art Direction', array( &$this, 'content_art_direction_add_admin_ui' ), 'page', 'advanced', 'core' );
	}

	function content_art_direction_add_admin_ui( $post ) {
		
		$artdirection_meta_css = get_post_meta( $post->ID, '_artdirection_meta_css', true);
		$artdirection_meta_js = get_post_meta( $post->ID, '_artdirection_meta_js', true);
		
		echo '<p>Add the additional CSS styles below:</p>';
		?>
		<textarea name="artdirection_meta_css" style="display:block; width: 100%;"  rows=20 ><?php echo esc_attr( $artdirection_meta_css ); ?></textarea>
		<?php

		echo '<p>Add the additional Javascript below:</p>';
		?>
		<textarea name="artdirection_meta_js" style="display:block; width: 100%;"  rows=20 ><?php echo esc_attr( $artdirection_meta_js ); ?></textarea>
		<?php
		
	}

    function content_art_direction_save_meta( $post_ID ) {
        global $post;
        //if( $post->post_type == "post" ) {
        if (isset( $_POST )&&isset($_POST['artdirection_meta_css'])) {

            update_post_meta( $post_ID, '_artdirection_meta_css', strip_tags( $_POST['artdirection_meta_css'] ) );
        }

        if (isset( $_POST )&&isset($_POST['artdirection_meta_js'])) {

            update_post_meta( $post_ID, '_artdirection_meta_js', strip_tags( $_POST['artdirection_meta_js'] ) );
        }
   }

	function content_art_direction_append_css ( $post_ID ) {
   		$css = get_post_meta( get_the_ID(),'_artdirection_meta_css',true ); 
   		if("" != $css):?>
<!-- Art Direction CSS -->
<style type='text/css'>
	<?php echo $css; ?>
</style>
<?php
		endif;
	}

	function content_art_direction_append_js ( $post_ID ) {
		$js = get_post_meta( get_the_ID(),'_artdirection_meta_js',true ); 
   		if("" != $js):?>
<!-- Art Direction JS -->
<script type='text/javascript'>
	<?php echo $js; ?>
</script>
<?php
		endif;
	}
  
} // end class
new ContentArtDirection();

?>