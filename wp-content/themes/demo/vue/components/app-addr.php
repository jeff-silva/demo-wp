<?php

endpoint_register('get', 'place-search', function() {
  return [123];
});

add_action('get_footer', function() {
  ?>
  <template id="app-addr">
    <div>
      App endereço
    </div>
  </template>

  <script>
    Vue.component('app-addr', {
      template: '#app-addr',
    });
  </script>
  <?php
});