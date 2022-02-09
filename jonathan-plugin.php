<?php

/**
 * @package JonathanPlugin 
 */

/**
* Plugin Name: Jonathan's Plugin
* Contributors: smachine
* Plugin URI: https://github.com/jonro2955
* Description: Adds a sentence at the end of all blog posts asking readers to follow us on social media if they liked the blog post. 
* Version: 1.0
* Author: Jonathan Ro
* Author URI: https://github.com/jonro2955
* License: GPL v2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
**/


/**Security precaution steps required for all WP Plugins:
 * 
 * 1. Create a file called index.php and place it in the same directory as this plugin file.
 * It should only the following text: 
 * 
 *  "<?php 
 *    // silence is golden."
 * 
 * This prevents unauthorized access to our code.
 * 
 * 2. Place the following security check at the top of this plugin php file:
 * 
 * if(!defined('ABSPATH')){
 *  exit;
 * }
 * 
 */

if(!defined('ABSPATH')){
  exit;
}


/** 
 * 1. add_filter() example: Insert an HTML element at the end of a post 
 * */

function wpb_follow_us($content) {
  // is_single() : for "post" 
  // is_page() : for "page"
  // is_singular() : for "post or page"
  if ( is_single() ) { 
    // Message to display after the post
    $content .= '<p class="follow-us"> For more news, updates, and resources, please follow us on 
      <a href="https://twitter.com/LiteracyBurnaby" title="LNB on Twitter" target="_blank">Twitter</a>, 
      <a href="https://www.facebook.com/LiteracyBurnaby" title="LNB on Facebook" target="_blank">Facebook</a> 
      and <a href="https://www.instagram.com/literacyburnaby/" title="LNB on Instagram" target="_blank">Instagram</a>.</p>';
  } 
  //A callback for a filter must accept a variable, modify it, and return it
  return $content; 
}
// Hook the function to the_content filter
add_filter('the_content', 'wpb_follow_us');

//Another filter example: change the "Read More" link text to "Click to Read!"
function dh_modify_read_more_link() {
    return '<a class="more-link" href="' . get_permalink() . '">Click to Read!</a>';
}
// Hook the function to the_content filter
add_filter( 'the_content_more_link', 'dh_modify_read_more_link' );




/**
 * 2. create a custom shortcode: associate some text output to a certain shortcode.
 * With the following function, you can use the text "[helloWorldShortCode]" as the shortcode
 * in the WP editor to output "Hello world!" onto the page 
 * */

function hello_world_shortcode() { 
  // Instructions for things you want to do 
  $message = 'Hello world!';   
  // The output needs to be returned
  return $message;
} 
// register the shortcode 
add_shortcode('helloWorldShortCode', 'hello_world_shortcode'); 
// you can now use the shortcode: "[helloWorldShortCode]" in the WP editor to output "Hello world!"



/**
 * 3. add_action() example: Function to return a random line from the Hello Dolly lyrics 
 * */

function hello_dolly_get_lyric() {
	$lyrics = "Hello, Dolly
    Well, hello, Dolly
    It's so nice to have you back where you belong
    You're lookin' swell, Dolly
    I can tell, Dolly
    You're still glowin', you're still crowin'
    You're still goin' strong
    I feel the room swayin'
    While the band's playin'
    One of our old favorite songs from way back when
    So, take her wrap, fellas
    Dolly, never go away again
    Hello, Dolly
    Well, hello, Dolly
    It's so nice to have you back where you belong
    You're lookin' swell, Dolly
    I can tell, Dolly
    You're still glowin', you're still crowin'
    You're still goin' strong
    I feel the room swayin'
    While the band's playin'
    One of our old favorite songs from way back when
    So, golly, gee, fellas
    Have a little faith in me, fellas
    Dolly, never go away
    Promise, you'll never go away
    Dolly'll never go away again";
	// Convert $lyrics into an array of strings split at each newline character
	$lyrics = explode( "\n", $lyrics );
	// Return a random line formatted with wptexturize().
	return wptexturize( $lyrics[ mt_rand( 0, count( $lyrics ) - 1 ) ] );
}

// Function to echo a chosen line from the lyrics using printf (prints a formatted string to the screen or HTML dom)
function hello_dolly() {
	$chosen = hello_dolly_get_lyric();
	$lang   = '';
	if ( 'en_' !== substr( get_user_locale(), 0, 3 ) ) {
		$lang = ' lang="en"';
	}
  //The double underscore and bracket __() is a WP function that translates a string from one language to another
	printf(
		'<p id="dolly"><span class="screen-reader-text">%s </span><span dir="ltr"%s>%s</span></p>',
		__( 'Quote from Hello Dolly song, by Jerry Herman:', 'hello-dolly' ),
		$lang,
		$chosen
	);
}

// The line below says 'when WP's admin_notices action is called, run hello_dolly()'
add_action( 'admin_notices', 'hello_dolly' );

// CSS to position the echoed paragraph
function dolly_css() {
	echo "
	<style type='text/css'>
	#dolly {
		float: right;
		padding: 5px 10px;
		margin: 0;
		font-size: 12px;
		line-height: 1.6666;
	}
	.rtl #dolly {
		float: left;
	}
	.block-editor-page #dolly {
		display: none;
	}
	@media screen and (max-width: 782px) {
		#dolly,
		.rtl #dolly {
			float: none;
			padding-left: 0;
			padding-right: 0;
		}
	}
	</style>
	";
}
// When WP's admin_head action is called, run dolly_css() to inject the css into the head
add_action( 'admin_head', 'dolly_css' );



