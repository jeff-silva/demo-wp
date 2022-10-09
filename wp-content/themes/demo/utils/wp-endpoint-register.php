<?php

function endpoint_register($methods, $route, $callback) {
  $methods = is_array($methods)? $methods: [ $methods ];
  $methods = array_map('strtoupper', $methods);

  add_action('rest_api_init', function() use($methods, $route, $callback) {
    register_rest_route('api', $route, [
      'methods' => $methods,
      'callback' => $callback,
    ]);
  });
}
