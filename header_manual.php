<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <?php wp_head(); ?>
  <style>
    .wp-editor h2 {
      padding: 0.8em 1em;
      background-color: #003974;
      border-left: 0.5rem solid #003974;
      color: #fff;
    }
  </style>
</head>

<body <?php body_class(); ?>>

  <header class="p-manual_header">
    <div class="p-manual_header__inr c-inner">
      <h1 class="p-manual_header__heading"><a href="<?php echo get_post_type_archive_link('wp-manual') ?>">WPマニュアル</a></h1>
      <div class="p-manual_header__menu">
        <a href="<?php echo esc_url(get_option('siteurl') . '/wp-admin'); ?>" rel="noopener noreferrer" class="p-manual_header__adminBtn">WP管理画面</a>
      </div>
    </div>
  </header>