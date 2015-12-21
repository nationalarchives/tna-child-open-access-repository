<div class="row" id="breadcrumb-holder">
    <div class="col starts-at-full clr">
        <div id="breadcrumb">
            <span class="first"><a href="/">Home</a> &gt;</span>
            <?php if ( function_exists('yoast_breadcrumb') ) {
                yoast_breadcrumb();
            } ?>

            <?php if (current_user_can( 'manage_options' )) {

                if (!has_post_thumbnail() ) {

                    ?>

                    <!--<div class="float-right"><a href="/wp-admin/media-upload.php?post_id=<?php //echo $post->ID; ?>&type=image&TB_iframe=1" target="_blank" class="button">Add featured image</a></div>-->
                <?php } }?>
        </div>
    </div>
</div>

  