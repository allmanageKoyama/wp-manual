<?php

/**
 * @package    WordPress
 * @subpackage allmanage
 * @author     y-koyama <y-koyama@a-manage.jp>
 *
 *
 * Plugin Name: wp-manual
 * Plugin URI:  https://www.e-webseisaku.com/
 * Text Domain: allmanage
 * Description: Wordpressの操作マニュアルを管理画面に追加するプラグイン　有効にすると自動で投稿とデータが生成されます。編集メニューは運用時に非表示にしてください。
 * Author:      y-koyama
 * Author URI:  https://www.e-webseisaku.com/
 * Version:     0.0.2
 * License:     GPLv3+
 */
if (!defined('MY_PLUGIN_VERSION')) {
  define('MY_PLUGIN_VERSION', '1.0');
}
if (!defined('MY_PLUGIN_PATH')) {
  define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__));
}
if (!defined('MY_PLUGIN_URL')) {
  define('MY_PLUGIN_URL', plugins_url('/', __FILE__));
}
add_action('admin_enqueue_scripts', function ($hook_suffix) {
  // 管理ページのクエリパラメータをチェックする
  if (isset($_GET['page']) && $_GET['page'] === 'wp_manual') {
    wp_enqueue_style(
      'my-test-style',
      MY_PLUGIN_URL . 'assets/style.css',
      array(),
      MY_PLUGIN_VERSION
    );
  }
});



//======================================================================
// カスタム投稿タイプを自作
//======================================================================
function self_made_post_type()
{
  register_post_type(
    'wp_manual',
    array(
      'label' => '操作マニュアル', //表示名
      'public'        => true, //公開状態
      'exclude_from_search' => false, //検索対象に含めるか
      'show_ui' => true, //管理画面に表示するか
      'show_in_menu' => true, //管理画面のメニューに表示するか
      'menu_position' => 2, //管理メニューの表示位置を指定
      'hierarchical' => true, //階層構造を持たせるか
      'has_archive'   => true, //この投稿タイプのアーカイブを作成するか
      'supports' => array(
        'title',
        'editor',
        'custom-fields',
        'post-formats',
        'page-attributes',
      ), //編集画面で使用するフィールド
      'show_in_rest' => true,
      // 'capabilities' => array(
      //   'publish_posts' => 'publish_posts',
      //   'edit_posts' => 'edit_posts',
      //   'edit_others_posts' => 'edit_others_posts',
      //   'delete_posts' => 'delete_posts',
      //   'delete_others_posts' => 'delete_others_posts',
      //   'read_private_posts' => 'read_private_posts',
      //   'edit_post' => 'edit_post',
      //   'delete_post' => 'delete_post',
      //   'read_post' => 'read_post',
      // ),
    )
  );
}
add_action('init', 'self_made_post_type', 1);

function enable_block_editor_for_wp_manual()
{
  add_post_type_support('wp_manual', 'editor');
}
add_action('init', 'enable_block_editor_for_wp_manual');

function remove_footer_on_wp_manual()
{
  global $post;
  if ('wp_manual' === $post->post_type) {
    remove_action('wp_print_footer_scripts', 'wp_manual');
    echo 'wp_manual';
  }
}
add_action('get_footer', 'remove_footer_on_wp_manual');

function custom_header_for_wp_manual()
{
  if (is_singular('wp_manual')) {
    remove_action('wp_print_scripts', 'wp_manual');
    remove_action('wp_print_footer_scripts', 'wp_manual');
  }
}
add_action('get_header', 'custom_header_for_wp_manual');

include_once(MY_PLUGIN_PATH . 'manual.php');

function add_manual()
{
  add_action('admin_enqueue_scripts', 'manual_style');
  add_action('admin_enqueue_scripts', 'manual_script');
}

function manual_style()
{
  $min = 1;
  $max = 100;
  $randomNumber = rand($min, $max);
  if (isset($_GET['page']) && $_GET['page'] === 'wp_manual') {
    $plugin_url = plugins_url('wp-manual');
    wp_enqueue_style('manual_main', $plugin_url . '/assets/style.css', false, $randomNumber);
    wp_enqueue_style('manual_include', $plugin_url . '/assets/manual.css', false, $randomNumber);
  }
}
function manual_script()
{
  $min = 1;
  $max = 100;
  $randomNumber = rand($min, $max);
  if (isset($_GET['page']) && $_GET['page'] === 'wp_manual') {
    $plugin_url = plugins_url('wp-manual');
    wp_enqueue_script('jquery336', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js', array(), null, true);
    wp_enqueue_script('manual_main', $plugin_url . '/assets/manual.js', array('jquery'), true, $randomNumber);
  }
}
add_action('admin_menu', 'add_manual');
//ランダムな整数を生成する

// 画像を読み込むショートコードを作成
function plugin_image_url_shortcode($atts)
{
  $atts = shortcode_atts(
    array(
      'file' => 'default.jpg' // デフォルトの画像ファイル名
    ),
    $atts,
    'plugin_image_url'
  );

  return plugins_url('assets/img/' . 'img' . $atts['file'] . '.png', __FILE__);
}
add_shortcode('plugin_image_url', 'plugin_image_url_shortcode');


// プラグインが有効化された際に実行する関数
function my_plugin_activation()
{
  // XMLファイルのパス
  $xml_path = MY_PLUGIN_PATH . 'data.xml';

  // XMLファイルが存在するか確認
  if (file_exists($xml_path)) {
    // SimpleXMLを使用してXMLをロード
    $xml = simplexml_load_file($xml_path);

    // XMLの各要素に対してループを回す（例として、<post>要素を基に投稿を作成）
    foreach ($xml->channel->item as $post_data) {
      $post_name_elements = $post_data->children('wp', true)->post_name;
      if ($post_name_elements && count($post_name_elements) > 0) {
        $post_name = (string)$post_name_elements[0];
      } else {
        $post_name = '';
      }
      $content_encoded_elements = $post_data->children('content', true)->encoded;
      if ($content_encoded_elements && count($content_encoded_elements) > 0) {
        $post_content = (string)$content_encoded_elements[0];
      } else {
        $post_content = '';
      }

      $post_array = array(
        'post_title'   => (string)$post_data->title,
        'post_name'    => $post_name,
        'post_content' => $post_content,
        'post_status'  => 'publish',
        'post_type'    => 'wp_manual',
      );

      // 投稿を作成する前にスラッグをログに出力
      error_log('投稿を作成しようとしています。スラッグ: ' . $post_array['post_name']);

      // 投稿の作成を試みる
      $result = wp_insert_post($post_array, true);

      if (is_wp_error($result)) {
        // エラーがあった場合、そのエラーメッセージをログに出力
        error_log('投稿の作成中にエラーが発生しました: ' . $result->get_error_message());
      } else {
        // 投稿が成功した場合、作成された投稿のスラッグをログに出力
        $created_post = get_post($result);
        error_log('投稿が正常に作成されました。スラッグ: ' . $created_post->post_name);
      }
    }
  }
}
register_activation_hook(__FILE__, 'my_plugin_activation');

// プラグインが無効化された際に実行される関数
function my_plugin_deactivation()
{
  // カスタム投稿タイプ'wp_manual'に関連する投稿を検索し、削除する
  $posts = get_posts(array(
    'post_type' => 'wp_manual',
    'numberposts' => -1,
    'post_status' => 'any'
  ));

  foreach ($posts as $post) {
    wp_delete_post($post->ID, true); // trueはゴミ箱に移動せずに完全に削除することを指示
  }
}
register_deactivation_hook(__FILE__, 'my_plugin_deactivation');
