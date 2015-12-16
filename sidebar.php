<?php
     $defaults = array(
       'theme_location'  => 'sidebar-menu',
       'menu'            => 'Sidebar Menu',
       'container'       => 'div',
       'container_class' => 'breather',
       'items_wrap'      => '<ul class="sibling">%3$s</ul>',
     );
?>
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
    <?php wp_nav_menu( $defaults ); ?>
</div>