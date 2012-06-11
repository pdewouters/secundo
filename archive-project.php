<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// Force full width
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

remove_action('genesis_loop', 'genesis_do_loop');

add_action('genesis_loop', 'secundo_do_work_loop');

function secundo_do_work_loop() { ?>

    <?php if ( have_posts() ) : ?>
        <div id="projects">
        <?php while ( have_posts() ) : the_post(); ?>
        
            <div class="element">
                <?php 
                    if( has_post_thumbnail() ) {

                        the_post_thumbnail('proj-thumb'); 
                    }
                ?>
            </div>
            <?php endwhile; ?>

        </div><!-- projects -->
        <?php genesis_posts_nav(); ?>
    <?php endif; ?>
    
<?php }

genesis();
?>
