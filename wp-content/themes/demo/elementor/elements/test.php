<?php

return new class extends \Elementor\Widget_Base {

	use UtilsTrait;

	public function get_name() {
		return pathinfo(__FILE__, PATHINFO_FILENAME);
	}

	public function get_title() {
		return 'Element test';
	}

	// https://ecomfe.github.io/eicons/demo/demo.html
	public function get_icon() {
		return 'eicon-editor-code';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function get_script_depends() {
		return [];
	}

	public function get_style_depends() {
		return [];
	}

	protected function _register_controls() {
		$this->start_controls_section('section_heading', [
			'label' => 'Configurações',
		]);

		$this->add_control('color', [
			'label' => 'Color',
			'type' => \Elementor\Controls_Manager::COLOR,
			'default' => '#000000',
		]);

		// $repeater = new \Elementor\Repeater();

		// $repeater->add_control('question', [
		// 	'label' => 'Pergunta',
		// 	'type' => \Elementor\Controls_Manager::WYSIWYG,
		// 	'default' => '',
		// 	'label_block' => true,
		// ]);

		// $repeater->add_control('answer', [
		// 	'label' => 'Pergunta',
		// 	'type' => \Elementor\Controls_Manager::WYSIWYG,
		// 	'default' => '',
		// 	'label_block' => true,
		// ]);

		// $this->add_control('questions', [
		// 	'label' => 'Perguntas',
		// 	'type' => \Elementor\Controls_Manager::REPEATER,
		// 	'fields' => $repeater->get_controls(),
		// 	'default' => [],
		// 	'title_field' => '{{{ question }}}',
		// ]);

		// $this->add_control('css', [
		// 	'label' => 'CSS',
		// 	'type' => \Elementor\Controls_Manager::CODE,
		// 	'default' => '$root {}',
		// ]);

		$this->end_controls_section();
	}

	public function render_full() {
		$set = $this->computed_settings();

		?>
		<div>
			<a href="">refresh</a>
			<div style="background: <?php echo $set->color; ?>;">
				color: <?php echo $set->color; ?>
			</div>
			<?php dd($set); ?>
		</div>

		<div id="<?php echo $set->id; ?>">
			<pre>$data: {{ $data }}</pre>
		</div>

		<script>
		new Vue({
			el: "#<?php echo $set->id; ?>",
			vuetify: new Vuetify(),
			data: <?php echo json_encode($set); ?>,
		});
		</script>
		<?php
	}
};
