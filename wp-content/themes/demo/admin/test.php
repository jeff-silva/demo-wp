<?php

/* Add menu | /wp-admin/admin.php?page=helper_menu_page */
add_action('admin_menu', function() {
	add_menu_page('Página de teste', 'Página de teste', 'manage_options', 'helper_menu_page', function() { ?>
	<div>Hello World</div>
	<?php }, 'dashicons-admin-users', 10);
});