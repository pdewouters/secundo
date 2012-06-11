<?php

/**
 * Template Name: Front page
 * This file handles the front page.
 *

 *
 * @category Genesis
 * @package  Templates
 * @author   StudioPress
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     http://www.studiopress.com/themes/genesis
 */

// Force full width
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

/* DAS UBER SLIDESHOW */
add_action( 'genesis_after_header', 'secundo_do_slideshow' );

// Moving site desc on home page
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );
add_action( 'genesis_after_header','secundo_seo_site_description' );

// Services nav
add_action( 'genesis_after_header', 'genesis_do_services' );

/* Roll our own custom loop */
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'secundo_do_loop' );

/* Add testimonials carousel */
add_action( 'genesis_before_footer', 'secundo_do_testimonials', 5 );

function secundo_do_slideshow(){ ?>
    <!-- slideshow -->
    <div id="slideshow">
        <div class="wrap">
            <div class="intro">
            <h2>Your go-to WordPress guy</h2>
            <ul>
                <li><a class="button" href="http://wpconsult.net/services#genesis">Genesis child themes</a></li>
                <li><a class="button" href="http://wpconsult.net/services#psd-to-wp">PSD to WordPress</a></li>
				<li>Since 2009</li>
            </ul>
            </div>
            <div class="banner">
                <img class="aligncenter" src="<?php echo get_stylesheet_directory_uri() . '/images/paul-cutout.png'; ?>" alt="" />
            </div>
        </div>
    </div>
<?php }

function secundo_seo_site_description() { ?>
    <!-- Site description -->
    <div id="tagline">
        <div class="wrap">
            <?php genesis_seo_site_description(); ?>
        </div>
    </div>
<?php }

// Services section
function genesis_do_services(){ ?>
    <div id="services">
        <div class="wrap">
            <p><span>I'm available for freelance work <a class="button" href="http://wpconsult.net/contact/">hire me</a></span></p>
        </div>
    </div>
<?php }

function secundo_do_loop() { ?>

    <!-- Recent work -->
    <div id="recent-work">
        <p><span>recent <a href="http://wpconsult.local/work/">work</a></span></p>
        <?php
            $args = array(
                'posts_per_page' => 4,
                'post_type' => 'project',
                'post_status' => 'publish'
            );
            $projects = new WP_Query( $args );        
        ?>
        <?php if( $projects->have_posts() ) : ?>
        <div id="projects">
            <?php while( $projects->have_posts() ) : ?>
            <?php $projects->the_post(); ?>
            <div class="project lifted drop-shadow">
                <div class="thumbnail">
                    <?php if( has_post_thumbnail( ) ) the_post_thumbnail( 'thumbnail', array('class' => 'aligncenter') ); ?>
                </div>
                
                <div class="content">
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <!--<p class="tags"><span>wordpress</span><span>genesis</span></p>-->
                    <?php the_excerpt(); ?>
                </div><!-- content -->
                
            </div><!-- project -->
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div><!-- projects -->
        <?php else : ?>
            <p>No projects matched the criteria.</p>
        <?php endif; ?>
        
        <?php //echo '<pre>'; var_dump( secundo_recent_projects() ); echo '</pre>'; ?>
    </div><!-- recent work -->
    
<?php }

function secundo_do_testimonials() { ?>
    <div id="testimonials">
        <div class="wrap">
            <blockquote>
            <p>Follow @pauldewouters if you're looking for a top notch freelance WordPress developer</p>
            <cite>Jon Haslett - <a href="http://dashmedia.com.au">Dashmedia</a></cite>
            </blockquote>

            <blockquote>
            <p>Paul has worked on a number of Web Savvy Marketing's custom website design projects. He has superior WordPress skills and delivers projects on time and to specification. I consider Paul one of my "go to guys" and would recommend him without reservation.</p>
            <cite>Rebecca Gill - <a href="http://web-savvy-marketing.com/">Web Savvy Marketing</a></cite>
            </blockquote>

            <blockquote>
            <p>I am very happy to recommend Paul, I have engaged him several times for web design projects and each time he has delivered exactly what was asked, on time and on budget</p>
            <cite>John Curtis - <a href="http://agorabrazil.com">Agora Imobiliaria</a></cite>
            </blockquote>
            </div><!-- testi > wrap -->
        </div><!-- testimonials -->
<?php }

/* Resume our noirmal programs */
genesis();
