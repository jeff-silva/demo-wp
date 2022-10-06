<?php

// add_action('admin_init', function() {
//   global $post, $pagenow;

//   if ($pagenow == 'post.php') {
//     dd($_REQUEST, $_SERVER['REQUEST_URI'], $post, $pagenow); die;
//   }
// });

// add_action('load-post.php', function() {
//   global $post;
//   dd('aaa', array_keys($GLOBALS), $GLOBALS['post']); die;
// });


add_action('the_post', function() {
  global $post, $pagenow;
  if ($pagenow != 'post.php') return;
  
  $_elementor_data = get_post_meta($post->ID, '_elementor_data');
  if (empty($_elementor_data)) return;

  if ($_GET['action']=='elementor') return;

  $_GET['action'] = 'elementor';
  wp_redirect('post.php?' . http_build_query($_GET)); die;
});