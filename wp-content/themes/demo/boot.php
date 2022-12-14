<?php

/**
 * Folders:
 * 
 * /admin:                Admin pages
 * /elementor:            Elementor files
 * /elementor/elements:   Elementor elements for editor
 * /utils:                Helper files
 * /vue:                  All Vue files
 * /vue/components:       Vue components
 * 
 */

function dd() {
  foreach(func_get_args() as $data) {
    echo '<pre>'. print_r($data, true) .'</pre>';
  }
}

function boot_include($file) {
  return include __DIR__ . $file;
}


// Utils
include __DIR__ . '/utils/elementor-prevent-post-edit.php';
include __DIR__ . '/utils/wp-endpoint-register.php';
include __DIR__ . '/utils/wp-remove-emoji.php';

// Admin pages
include __DIR__ . '/admin/install.php';


endpoint_register('get', '/aaa', function() {
  return [123];
});


// Elementor elements
add_action('elementor/widgets/widgets_registered', function($manager) {
  include __DIR__ . '/elementor/UtilsTrait.php';

  $files[] = __DIR__ . '/elementor/elements/form-contact.php';
  $files[] = __DIR__ . '/elementor/elements/imc.php';
  $files[] = __DIR__ . '/elementor/elements/layout-default.php';
  $files[] = __DIR__ . '/elementor/elements/test.php';

  foreach($files as $file) {
    $element = include $file;
    $manager->register_widget_type($element);
  }
});


// Assets
foreach(['wp_enqueue_scripts', 'admin_enqueue_scripts'] as $action) {
	add_action($action, function() {
    wp_enqueue_script('vue', 'https://unpkg.com/vue@2/dist/vue.min.js');
    wp_enqueue_script('vuetify', 'https://unpkg.com/vuetify@2/dist/vuetify.min.js');
    wp_enqueue_style('vuetify', 'https://unpkg.com/vuetify@2/dist/vuetify.min.css');
    wp_enqueue_style('mdi', 'https://cdn.jsdelivr.net/npm/@mdi/font@6/css/materialdesignicons.min.css');
    // wp_enqueue_style('roboto', 'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900');
    // wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
	});
}
