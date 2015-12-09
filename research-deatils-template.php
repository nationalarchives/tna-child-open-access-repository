<?php
/*
Template Name: OA Research details template
*/

get_header();

?>
<div class="container" id="page_wrap" role="main">
    <?php include 'breadcrumb.php'; ?>
<div class="row">
    <div class="col starts-at-full ends-at-two-thirds box clr">
        <div class="heading-holding-banner">
            <h1>
                <span>
                    <span>
                       <?php echo get_the_title(); ?>
                    </span>
                </span>
            </h1>
        </div>
        <div class="breather">
            <span class="entry-meta"><strong>Author(s):</strong></span>
            <span class="entry-meta">Julie Halls</span>
            <div class="clearfix"></div>
            <span class="entry-meta"><strong>Date of publication:</strong> <?php the_date('d/m/y'); ?></span>
            <br />
            <span class="entry-meta"><strong>Published by:</strong> Laura Ipsum</span>
            <hr class="line-stroke">
            <div class="clearfix"></div>
            <span class="entry-meta"><strong>Keywords:</strong> </span>
            <span class="entry-meta">copyright</span>,
            <span class="entry-meta">wallpaper</span>,
            <span class="entry-meta">material culture</span>
            <hr class="line-stroke">
            <div class="clearfix"></div>
            <?php
                the_content();
            ?>
            <div class="clearfix"></div>
            <a class="button float-right" href="#">Download PDF (123KB)</a>
        </div>
        <!--  Research ends here-->
    </div>
    <div class="col starts-at-full ends-at-one-third clr box">
        <div class="heading-holding-banner">
            <h2>
                <span>
                    <span>
                        You may also be interested in
                    </span>
                </span>
            </h2>
        </div>
        <div class="breather">
            <ul class="sibling">
                <li>Lorem ipsuim</li>
                <li>Lorem ipsuim</li>
                <li>Lorem ipsuim</li>
            </ul>
        </div>
    </div>
</div>
</div>

<a id="goTop"></a>




<?php
get_footer();
