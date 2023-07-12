<li class="p-manual_post__item">
  <a data-to_view="<?php echo $i; ?>" class="p-manual_post__link">
    <div class="p-manual_post__infoWrap">
      <time datetime="<?php echo esc_html(get_the_modified_date('Y-m-d')); ?>" class="p-manual_post__date">更新日：<?php echo esc_html(get_the_modified_date('Y.m.d')); ?></time>
    </div>
    <p class="p-manual_post__title"><?php echo get_the_title(); ?></p>
  </a>
</li>