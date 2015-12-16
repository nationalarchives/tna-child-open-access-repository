<?php
/*

*/
get_header();
?>
    <div class="container" id="page_wrap" role="main">
        <?php include 'breadcrumb.php'; ?>
        <div class="row">
            <div class="col starts-at-full ends-at-two-thirds box clr">

                <?php $feat_image = wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>
                    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                        <div class="image-container large position-relative pad-top-medium"
                             style="background-image: url('<?php echo $feat_image; ?>')">
                                <h1 class="margin-none">
                                <span>
                                    <span><?php the_title(); ?></span>
                                </span>
                                </h1>
                        </div>
                        <div class="breather"> <?php the_content(); ?> </div>
                             <?php endwhile; else: ?>
                        <p>Sorry, no content</p>
                <?php endif; wp_reset_query(); ?>
                <div class="col starts-at-full ends-at-full boc margin-none clr">
                    <?php
                        $args = array(
                                'post_type'      => 'page',
                                'posts_per_page' => -1,
                                'post_parent'    => $post->ID,
                                'order'          => 'ASC',
                                'orderby'        => 'menu_order'
                             );

                        $child = new WP_Query( $args );
                    ?>
                <?php if ($child -> have_posts()) : while ($child -> have_posts()) : $child -> the_post(); ?>
                   <div class="breather">
                       <div class="resource-block">
                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                <h3 class="margin-bottom-small"><?php the_title(); ?></h3>
                            </a>
                                <div class="clearfix"></div>
                                <span class="entry-meta">
                                    <?php
                                        $other_authors = get_post_meta( $post->ID, 'authors_section_other-authors', true );
                                        if (empty ($other_authors)) {
                                            echo '<strong>Author:</strong>';
                                        } else {
                                            echo '<strong>Authors:</strong>';
                                        }
                                    ?>
                                </span>
                                <span class="entry-meta">
                                    <?php
                                        $lead_author = get_post_meta( $post->ID, 'authors_section_lead-author', true );
                                        echo $lead_author;

                                        if (!empty ($other_authors)) {
                                            echo ', '.$other_authors;
                                        }
                                    ?>
                                </span>
                                <div class="clearfix"></div>
                            <p><?php the_excerpt(); ?>
                            </p>

                        </div>
                    </div>
                    <hr class="line-stroke">
                    <?php endwhile; else: ?>
                          <p>Sorry, no content</p>
                <?php endif; wp_reset_query();?>
                </div>
            </div>
                <?php //sidebar comes here
                    get_sidebar(  );
                ?>
            </div>

        </div>
    </div>

<?php
    get_footer();
?>