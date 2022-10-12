<?php

/* Add submenu | /wp-admin/themes.php?page=install */
add_action('admin_menu', function() {
	add_submenu_page('themes.php', 'Instalar tema', 'Instalar tema', 'manage_options', 'install', function() { ?>
    <div id="app">
      <v-app>
        <v-main>
          <v-container>
            Instalação
          </v-container>
        </v-main>
      </v-app>
    </div>

    <script>
      new Vue({
        el: "#app",
        vuetify: new Vuetify(),
        data: {},
      });
    </script>
	<?php });
});
