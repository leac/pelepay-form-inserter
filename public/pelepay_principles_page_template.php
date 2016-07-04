<?php
/*
  Template Name: פלא פיי טופס עקרונות
 */
?>
<?php
get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <?php while ( have_posts() ) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php /* This code was taken from content-page and adapted to our needs:  
                 * Remove header
                 * Add form beneath content */ ?>

                <div class="entry-content">
                    <?php the_content(); ?>
                    <?php echo apply_filters( 'pelepay_add_form_after_principles', '' ) ?>
                    <?php
                    wp_link_pages( array(
                        'before' => '<div class="page-links">' . __( 'Pages:', 'pelepay-form-inserter' ),
                        'after' => '</div>',
                    ) );
                    ?>
                </div><!-- .entry-content -->
                <?php edit_post_link( __( 'Edit', 'pelepay-form-inserter' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>
            </article><!-- #post-## -->


            <?php
            // If comments are open or we have at least one comment, load up the comment template
            if ( comments_open() || '0' != get_comments_number() ) :
                comments_template();
            endif;
            ?>        

        <?php endwhile; // end of the loop.  ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
