<?php

function dd() {
  foreach(func_get_args() as $data) {
    echo '<pre>'. print_r($data, true) .'</pre>';
  }
}

// Elementor elements
include __DIR__ . '/elementor/test.php';

