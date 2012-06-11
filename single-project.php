<?php

/** Remove the post info function */
remove_action( 'genesis_before_post_content', 'genesis_post_info' );

/** Remove the post meta function */
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );

add_action('genesis_before_content_sidebar_wrap', 'secundo_do_single_nav');

function secundo_do_single_nav() { ?>
    <div id="project-nav">
        <div class="go-back"><?php echo '<a href="' . home_url() . '/work/">back to work</a>'; ?></div>
        <div class="navigation">
            <div class="alignleft"><?php 	previous_post_link('%link', 'Previous project'); ?></div>
            <div class="alignright"><?php   next_post_link('%link', 'next project'); ?></div>
        </div>
    </div>    
<?php }


genesis();