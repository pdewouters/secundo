<?php

add_action('genesis_setup','secundo_theme_setup', 15);
function secundo_theme_setup() {
    
    // Remove stuff
    require_once 'lib/cleanup.php';
    require_once 'lib/latest-tweets.php';
    require_once 'lib/custom-walker.php';
    require_once 'lib/crop-patch.php';
    
    // Add theme functionality
    
    bt_add_image_size( 'proj-thumb', 614, 384, array( 'left','top' ) );
    // Add widget area
    add_theme_support( 'genesis-footer-widgets', 1 ); 
    
    add_action( 'widgets_init', 'secundo_register_sidebars' );
    
    // Add structural wrap div
    add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'inner', 'footer-widgets', 'footer' ) );
    
    // Change menu for mobile
    add_action('genesis_after_header','secundo_mobile_menu_primary');
    
    // Make logo an img tag for SEO
    add_filter( 'genesis_seo_title', 'secundo_site_title', 10, 3 );
    
    // Move the page title
    add_action( 'template_redirect', 'wpc_maybe_move_title' );
            
    
    //Modify post title output
    add_action( 'genesis_after_header', 'secundo_post_title_output' );
    
    // Modify footer
    remove_action( 'genesis_footer', 'genesis_do_footer' );
    add_action( 'genesis_footer', 'secundo_do_footer' );

    //To link all Post Thumbnails on your website to the Post Permalink   
    add_filter( 'post_thumbnail_html', 'secundo_post_image_html', 10, 3 );
    
    /** Change the featured image */
    remove_action( 'genesis_post_content', 'genesis_do_post_image' );
    add_action('genesis_post_content','secundo_post_image' ) ;   
    
    add_filter('widget_text', 'do_shortcode');
    
    /** Customize the next page link */
    add_filter ( 'genesis_next_link_text' , 'secundo_next_link_text' );

    /** Customize the previous page link */
    add_filter ( 'genesis_prev_link_text' , 'secundo_prev_link_text' );

        // Search form text
    add_filter('genesis_search_text', 'secundo_search_text');   
    
    // Add custom text for search button
    add_filter('genesis_search_button_text', 'secundo_search_button_text');  
    
    add_filter( 'genesis_next_link_text', 'secundo_review_next_link_text' );
    
    add_filter( 'genesis_prev_link_text', 'secundo_review_prev_link_text' );  

    add_filter( 'genesis_older_link_text', 'secundo_review_older_link_text' );

    add_filter( 'genesis_newer_link_text', 'secundo_review_newer_link_text' );
      
    remove_action( 'genesis_before_post_content', 'genesis_post_info' );
    add_action( 'genesis_before_post_content', 'secundo_post_info' );    
    
    add_action( 'template_redirect', 'secundo_sidebar_logic' );
    
    add_action( 'wp_enqueue_scripts', 'secundo_load_scripts' );
    
    add_filter( 'widget_title', 'secundo_widget_title' );
    
    add_filter( 'the_title', 'secundo_page_title' );
    
    add_action('pre_get_posts', 'secundo_modify_query', 1);
    
    // Add necesseary meta tags (chrome frame, mobile scaling)
    add_action('genesis_meta','secundo_add_meta_tags', 5);
    
    add_action('genesis_before','secundo_check_google_frame_presence');    
}

function secundo_add_meta_tags(){
    print '<meta name="viewport" content="width=device-width, initial-scale=1" />';
    print '<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=IE6">';
	print '<link rel="author" href="https://plus.google.com/u/0/102451277259777607714/posts />';
    echo '<!-- [if lt IE 7]<link href="' . get_stylesheet_directory_uri() . '/stylesheets/ie6.css" media="screen,projection" rel="stylesheet" type="text/css" /><![endif]-->';
}

function secundo_check_google_frame_presence(){ ?>
  <!--[if lt IE 7]>
    <script type="text/javascript" 
     src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js"></script>

    <style>
     .chromeFrameInstallDefaultStyle {
       width: 100%; /* default is 800px */
       border: 5px solid blue;
     }
    </style>

    <div id="prompt">Install Chrome Frame</div>
 
    <script>
     // The conditional ensures that this code will only execute in IE,
     // Therefore we can use the IE-specific attachEvent without worry
     window.attachEvent("onload", function() {
       CFInstall.check({
         mode: "inline", // the default
         node: "prompt"
       });
     });
    </script>
  <![endif]-->
  <?php
}

// Modify site title
function secundo_site_title( $title, $inside, $wrap ) {
    
	/** Set what goes inside the wrapping tags */
	$inside = sprintf( '<a href="%s" title="%s"><img src="%s" alt="%s" /></a>', trailingslashit( home_url() ), esc_attr( get_bloginfo( 'name' ) ), get_stylesheet_directory_uri() . '/images/logo.png',get_bloginfo( 'name' ) );    
	
	/** Build the Title */
	$title = sprintf( '<%s id="title">%s</%s>', $wrap, $inside, $wrap );
    
    return $title;
}

function wpc_maybe_move_title(){
    if( ! is_home() && ! is_singular( 'project' ) && ! is_archive() && ! is_search()  && !is_page_template( 'page_blog.php' ) && ! is_single() )
        remove_action( 'genesis_post_title', 'genesis_do_post_title' );
 }
 
// Modify post title output
function secundo_post_title_output() {
    //global $post;
    
    if( is_front_page() )
        return;
    if( is_tag() || is_category() )
        echo '<div id="secundo-post-title"><div class="wrap"><h1><span>blog</span></h1></div></div>';
    elseif( is_post_type_archive( 'project' ) || is_singular('project') )
        echo '<div id="secundo-post-title"><div class="wrap"><p><span>work</span></p></div></div>';

    elseif( is_single() )
        echo '<div id="secundo-post-title"><div class="wrap"><p><span>blog</span></p></div></div>';
    elseif ( is_search() )
        echo '<div id="secundo-post-title"><div class="wrap"><h1><span>Search results</span></h1></div></div>';
 
     elseif ( is_404() )
        echo '<div id="secundo-post-title"><div class="wrap"><h1><span>Page not found</span></h1></div></div>';
   
    else
        the_title('<div id="secundo-post-title"><div class="wrap"><h1><span>','</span></h1></div></div>');

}

// ADd widget area to footer
function secundo_register_sidebars() {
	/* Register the 'primary' sidebar. */
	register_sidebar(
		array(
			'id' => 'footer-widget-area',
			'name' => __( 'Footer Widget Area' ),
			'description' => __( 'Footer widgets here.' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widgettitle">',
			'after_title' => '</h3>'
		)
	);    
    
    // Register new sidebar
        genesis_register_sidebar( array(
            'id' => 'blog-sidebar',
            'name' => 'Blog Sidebar',
            'description' => 'This is the bottom left column in the sidebar.',
        ) );
        
        genesis_register_sidebar( array(
            'id' => 'project-sidebar',
            'name' => 'Project Sidebar',
            'description' => 'This is the project sidebar.',
        ) );
     
}

function secundo_do_footer() { ?>

    <?php dynamic_sidebar('footer-widget-area'); ?>

<?php }


function secundo_post_image_html( $html, $post_id, $post_image_id ) {
    $img = wp_get_attachment_image_src($post_image_id, 'thumbnail');
  $html = '<a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( get_post_field( 'post_title', $post_id ) ) . '"><p class="aligncenter" style="background-repeat: no-repeat;background-image: url(' . $img[0] . ')">' . $html . '</p></a>';
  return $html;

}


function secundo_post_image() {
    if ( ! is_singular() && genesis_get_option( 'content_archive_thumbnail' ) ) {
        $img = genesis_get_image( array( 'format' => 'html', 'size' => genesis_get_option( 'image_size' ), 'attr' => array( 'class' => 'aligncenter post-image' ) ) );
        printf( '<a href="%s" title="%s">%s</a>', get_permalink(), the_title_attribute( 'echo=0' ), $img );
    }
}



function secundo_review_older_link_text() {
    $olderlink = 'Older posts';
    return $olderlink;
}

function secundo_review_newer_link_text() {
    $newerlink = 'Newer posts';
    return $newerlink;
}


function secundo_review_prev_link_text() {
    $prevlink = 'Previous posts';
    return $prevlink;
}

function secundo_review_next_link_text() {
    $nextlink = 'Next posts';
    return $nextlink;
}

function secundo_search_text($text) {
    return esc_attr('Search...');
}
 

function secundo_search_button_text($text) {
    return esc_attr('Go');
}


/**
 * Custom Post-Info with Google Rich Snippet support
 *
 * @author Greg Rickaby
 * @since 1.0.0
 */
function secundo_post_info() {
	if ( is_page() || is_singular('project') )
		return; // don't do post-info on pages ?>

	<div class="post-info">
		<span class="date published time">
			<time class="entry-date" itemprop="startDate" datetime="<?php echo get_the_date( 'c' ); ?>" pubdate><?php echo get_the_date(); ?></time>
		</span> By 
		<span class="author vcard">
			<a class="fn n" href="<?php echo get_the_author_url( get_the_author_meta( 'ID' ) ); ?>" title="View <?php echo get_the_author(); ?>'s Profile" rel="author me"><?php the_author_meta( 'display_name' ); ?></a>
		</span>
		<span class="post-comments">&middot; <a href="<?php the_permalink() ?>#comments"><?php comments_number( 'Leave a Comment', '1 Comment', '% Comments' ); ?></a></span>
		<?php // if the post has been modified, display the modified date
		$published = get_the_date( 'F j, Y' );
		$modified = the_modified_date( 'F j, Y', '', '', FALSE );
		$published_compare = get_the_date( 'Y-m-d' );
		$modified_compare = the_modified_date( 'Y-m-d', '', '', FALSE ); 
			if ( $published_compare < $modified_compare ) {
				echo '<span class="updated"><em>&middot; (Updated: ' . $modified . ')</em></span>';
			} ?>
	</div>
<?php }



/**
 * Swap in a different sidebar instead of the default sidebar.
 *
 * @author Jennifer Baumann
 * @link http://dreamwhisperdesigns.com/?p=1034
 */
function secundo_sidebar_logic() {
	if ( ( is_page_template( 'page_blog.php' ) || is_archive() || is_singular('post') ) && ! is_post_type_archive('project') ) {
		remove_action( 'genesis_after_content', 'genesis_get_sidebar' );
		add_action( 'genesis_after_content', 'secundo_get_blog_sidebar' );
	} elseif( is_singular( 'project' ) ) {
		remove_action( 'genesis_after_content', 'genesis_get_sidebar' );
		add_action( 'genesis_after_content', 'secundo_get_project_sidebar' );       
    } else {}
    
    
}

/**
 * Retrieve blog sidebar
 */
function secundo_get_blog_sidebar() { 
    get_sidebar('blog');
 }

function secundo_get_project_sidebar() { 
	get_sidebar('project');
}

/* Load scripts */

function secundo_load_scripts() {
    wp_dequeue_script('superfish');
    wp_dequeue_script('superfish-args');
    
    if( is_post_type_archive( 'project' ) ) {
        
        /* wp_enqueue_script( 'isotope', get_stylesheet_directory_uri() . '/js/jquery.isotope.min.js', array('jquery') );
         wp_enqueue_script( 'global', get_stylesheet_directory_uri() . '/js/global.js', array('jquery','isotope') );       
        wp_enqueue_style( 'isotope', get_stylesheet_directory_uri() . '/css/isotope.css' ); */
    }
    
    if( is_front_page() ){
        //wp_enqueue_script( 'isotope', get_stylesheet_directory_uri() . '/js/sequence.jquery-min.js', array('jquery') );
        
    }
}

function secundo_widget_title( $title ) {
    if( '' != $title)
        return '<span>' . $title . '</span>';
}

function secundo_page_title( $title ) {
    if( ! is_admin )
        return '<span>' . $title . '</span>';
    
    return $title;
}

function secundo_modify_query( $query ) {

    if ( is_post_type_archive('project') ){

        $query->query_vars['posts_per_page'] = 10;
        return;
    }
}

function secundo_mobile_menu_primary(){

        wp_nav_menu( 
            array(
                'menu' => 'Primary Navigation', // your theme location here
                'walker'         => new Walker_Nav_Menu_Mobile(),
                'items_wrap'     => '<div class="wrap"><form><select class="default" id="prim-selector" name="URL" onchange="window.location.href= this.form.URL.options[this.form.URL.selectedIndex].value">%3$s</select></form></div>',
                'container_id' => 'mobile_menu_primary'
            )
        );     


}