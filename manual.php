<?php
add_action('admin_menu', 'wp_manual');
function wp_manual()
{
  add_menu_page('操作マニュアル', '操作マニュアル', 'manage_options', 'wp_manual', 'add_wp_manual', 'dashicons-welcome-learn-more', 10);
}
function add_wp_manual()
{
?>
  <header class="p-manual_header">
    <div class="p-manual_header__inr c-inner">
      <h1 class="p-manual_header__heading">WPマニュアル</h1>
      <div class="p-manual_header__menu">
        <a class="p-manual_header__adminBtn" data-to_view="0">WPマニュアルTOP</a>
      </div>
    </div>
  </header>
  <main class="l-content p-manual view" id="manual-0" data-id="manual-0">

    <section class="p-manual_kv">
      <div class="p-manual_kv__inr c-inner">
        <h2 class="p-manual_kv__title">WPマニュアル 一覧</h2>
      </div>
    </section>
    <?php
    $args = array(
      'post_status'    => array('publish'),
      'post_type'      => array('wp_manual'),
      'posts_per_page' => -1,
      'no_found_rows'  => true,
      'orderby'        => 'menu_order',
      'order'          => 'ASC',
    );
    $the_query = new WP_Query($args);
    ?>
    <div class="p-manual_mainConts">
      <div class="c-inner">

        <?php
        $i = 1;
        if ($the_query->have_posts()) {
          echo '<ul class="p-manual_post">';

          while ($the_query->have_posts()) {
            $the_query->the_post();
            include('loop_manual.php');
            $i++;
          }

          echo '</ul>';
        } else {
          echo '<p>まだマニュアルはありません。</p>';
        }
        wp_reset_postdata();
        ?>

      </div>
    </div>
  </main>
  <?php
  $i = 1;
  if ($the_query->have_posts()) :
    while ($the_query->have_posts()) :
      $the_query->the_post();
  ?>
      <main class="l-content p-manual" id="manual-<?php echo $i; ?>" data-id="manual-<?php echo $i; ?>">
        <section class="p-manual_kv">
          <div class="p-manual_kv__inr c-inner">
            <h2 class="p-manual_kv__title"><?php the_title(); ?></h2>
          </div>
        </section>
        <div class="p-manual_mainConts">
          <div class="c-inner">

            <div class="wp-editor"><?php the_content(); ?></div>
            <div class="p-manual_btnWrap">
              <a data-to_view="0" class="p-manual_btn">戻る</a>
            </div>
          </div>
        </div>
      </main>
  <?php
      $i++;
    endwhile;
  else :
    echo '投稿がありません';
  endif;

  wp_reset_postdata();
  ?>
  </main>
  <?php get_template_part('footer_manual'); ?>
<?php
}
