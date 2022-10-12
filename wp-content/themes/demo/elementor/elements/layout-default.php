<?php

return new class extends \Elementor\Widget_Base {

	public function get_name() {
		return pathinfo(__FILE__, PATHINFO_FILENAME);
	}

	public function get_title() {
		return 'Demo: Layout Default';
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

		$repeater = new \Elementor\Repeater();

		$repeater->add_control('question', [
			'label' => 'Pergunta',
			'type' => \Elementor\Controls_Manager::WYSIWYG,
			'default' => '',
			'label_block' => true,
		]);

		$repeater->add_control('answer', [
			'label' => 'Pergunta',
			'type' => \Elementor\Controls_Manager::WYSIWYG,
			'default' => '',
			'label_block' => true,
		]);

		$this->add_control('questions', [
			'label' => 'Perguntas',
			'type' => \Elementor\Controls_Manager::REPEATER,
			'fields' => $repeater->get_controls(),
			'default' => [],
			'title_field' => '{{{ question }}}',
		]);

		$this->add_control('css', [
			'label' => 'CSS',
			'type' => \Elementor\Controls_Manager::CODE,
			'default' => '$root {}',
		]);

		$this->end_controls_section();
	}

	protected function render() {
		$set = json_decode(json_encode($this->get_settings()));

		?>
		<div>
			Custom content
			<!-- content -->
		</div>
		<?php
	}

	protected function content_template() {}
};
