<div class="row" id="breadcrumb-holder">
    <div class="col starts-at-full clr">
        <div id="breadcrumb">
            <span class="first"><a href="/">Home</a> &gt;</span> <a href="/about/">About us</a> &gt; <a href="/about/our-role/">Our role</a> &gt;
            <?php if ( function_exists('yoast_breadcrumb') ) {
                $breadcrumbs = yoast_breadcrumb('','',false);
                echo make_urls_root_relative($breadcrumbs);
            } ?>
        </div>
    </div>
</div>
