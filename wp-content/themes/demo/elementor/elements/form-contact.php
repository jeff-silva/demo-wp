<?php

boot_include('/vue/components/app-addr.php');

if (! class_exists('ElementorFormContactSupport')) {
	class ElementorFormContactSupport
	{
		static function rules()
		{
			$rules['required'] = (object) [
				'name' => 'Obrigatório',
				'callback' => function($value) {
					return !!$value;
				},
			];
			
			$rules['email'] = (object) [
				'name' => 'E-mail',
				'callback' => function($value) {
					return filter_var($value, FILTER_VALIDATE_EMAIL);
				},
			];
	
			return $rules;
		}
	
		static function rulesOptions()
		{
			return array_map(function($rule) {
				return $rule->name;
			}, self::rules());
		}
	
		static function inputs()
		{
			$inputs['v-text-field'] = (object) [
				'name' => 'Texto simples',
				'component' => 'v-text-field',
				'bind' => (object) [],
				'template' => '<v-text-field></v-text-field>',
			];
			
			$inputs['v-text-field-password'] = (object) [
				'name' => 'Password',
				'component' => 'v-text-field',
				'bind' => (object) [
					'type' => 'password',
				],
			];
			
			$inputs['v-text-field-number'] = (object) [
				'name' => 'Password',
				'component' => 'v-text-field',
				'bind' => (object) [
					'type' => 'password',
				],
			];
			
			$inputs['v-select'] = (object) [
				'name' => 'Select',
				'component' => 'v-select',
				'bind' => (object) [
					'type' => 'password',
				],
			];
			
			$inputs['v-textarea'] = (object) [
				'name' => 'Texto multilinha',
				'component' => 'v-textarea',
				'bind' => (object) [],
			];
			
			$inputs['app-addr'] = (object) [
				'name' => 'Endereço',
				'component' => 'app-addr',
				'bind' => (object) [],
			];
	
			return $inputs;
		}
	
		static function inputsOptions()
		{
			return array_map(function($rule) {
				return $rule->name;
			}, self::inputs());
		}
	}
}

return new class extends \Elementor\Widget_Base {

	use UtilsTrait;

	public function get_name() {
		return pathinfo(__FILE__, PATHINFO_FILENAME);
	}

	public function get_title() {
		return 'Formulário de contato';
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

		$this->start_controls_section('section_steps', [
			'label' => 'Steps',
		]);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control('id', [
			'label' => 'Nome',
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => '',
			'label_block' => true,
		]);
		
		$repeater->add_control('name', [
			'label' => 'Nome',
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => '',
			'label_block' => true,
		]);

		$this->add_control('steps', [
			'label' => 'Steps',
			'type' => \Elementor\Controls_Manager::REPEATER,
			'fields' => $repeater->get_controls(),
			'default' => [],
			'title_field' => '{{{ name }}}',
		]);

		$this->end_controls_section();



		$this->start_controls_section('section_fields', [
			'label' => 'Campos',
		]);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control('label', [
			'label' => 'Label',
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => '',
			'label_block' => true,
		]);

		$repeater->add_control('cols', [
			'label' => 'Colunas',
			'type' => \Elementor\Controls_Manager::SELECT2,
			'default' => '6',
			'label_block' => true,
			'options' => [
				'12' => 'Inteiro',
				'6' => 'Metade',
				'4' => '1/3',
				'3' => '1/4',
			],
		]);
		
		$repeater->add_control('name', [
			'label' => 'Field Name',
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => '',
			'label_block' => true,
		]);
		
		$repeater->add_control('step', [
			'label' => 'Step',
			'type' => \Elementor\Controls_Manager::NUMBER,
			'default' => 0,
		]);

		$repeater->add_control('type', [
			'label' => 'Tipo',
			'type' => \Elementor\Controls_Manager::SELECT2,
			'default' => 'form-text',
			'label_block' => true,
			'options' => ElementorFormContactSupport::inputsOptions(),
		]);

		$repeater->add_control('value', [
			'label' => 'Valor padrão',
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => '',
			'label_block' => true,
		]);

		$repeater->add_control('rules', [
			'label' => 'Validações',
			'type' => \Elementor\Controls_Manager::SELECT2,
			'default' => [],
			'label_block' => true,
			'multiple' => true,
			'options' => ElementorFormContactSupport::rulesOptions(),
		]);

		$repeater->add_control('mask', [
			'label' => 'Máscara',
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => '',
			'label_block' => true,
		]);

		$repeater->add_control('prefix', [
			'label' => 'Prefixo',
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => '',
			'label_block' => true,
		]);

		$repeater->add_control('suffix', [
			'label' => 'Sufixo',
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => '',
			'label_block' => true,
		]);
		
		$repeater->add_control('multiple', [
			'label' => 'Ítens multiplos?',
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'default' => '',
			'label_on' => 'Múltiplos',
			'label_off' => 'Somente um',
			'return_value' => '1',
			'label_block' => true,
		]);
		
		$repeater->add_control('items', [
			'label' => 'Options items',
			'type' => \Elementor\Controls_Manager::TEXTAREA,
			'default' => '',
			'label_block' => true,
		]);

		$this->add_control('fields', [
			'label' => 'Campos',
			'type' => \Elementor\Controls_Manager::REPEATER,
			'fields' => $repeater->get_controls(),
			'default' => [],
			'title_field' => 'step {{{ step }}}: {{{ label }}}',
		]);

		$this->end_controls_section();



		$this->start_controls_section('section_actions', [
			'label' => 'Ações',
		]);

		$this->add_control('act_prev_label', [
			'label' => 'Label ação anterior',
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => 'Anterior',
			'label_block' => true,
		]);
		
		$this->add_control('act_next_label', [
			'label' => 'Label ação próximo',
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => 'Próximo',
			'label_block' => true,
		]);
		
		$this->add_control('act_finish_label', [
			'label' => 'Label ação finalizar',
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => 'Finalizar',
			'label_block' => true,
		]);

		$this->end_controls_section();


		$this->start_controls_section('section_success', [
			'label' => 'Sucesso',
		]);

		$this->add_control('success_message', [
			'label' => 'Mensagem de sucesso',
			'type' => \Elementor\Controls_Manager::TEXTAREA,
			'default' => 'Obrigado pelo contato! Responderemos a mensagem assim que possível.',
		]);

		$this->end_controls_section();


		$this->start_controls_section('section_test', [
			'label' => 'Testes',
		]);

		$this->add_control('test', [
			'label' => 'Ativar testes',
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'return_value' => '1',
			'default' => '1',
			'label_block' => true,
		]);
		
		$this->add_control('test_step', [
			'label' => 'Step',
			'type' => \Elementor\Controls_Manager::NUMBER,
			'default' => '0',
		]);

		$this->add_control('test_post', [
			'label' => 'Post',
			'type' => \Elementor\Controls_Manager::CODE,
			'default' => '{}',
		]);

		$this->end_controls_section();
	}


	public function settings($set) {
		$set->step = 0;

		$set->stepsCompletes = array_map(function($field) {
			return true;
		}, $set->steps);
		
		$set->fields = array_map(function($field) {

			// Value array
			if ($field['multiple']) {
				$value = json_decode($field['multiple'], true);
				$field['value'] = is_array($value)? $value: [];
			}

			// Items options
			$items = [];
			foreach(explode("\n", $field['items']) as $item) {
				list($value, $text) = explode('=', $item);
				if (!$value OR !$text) continue;
				$items[] = [
					'text' => $text,
					'value' => $value,
				];
			}
			$field['items'] = $items;

			// Render element
			$field['component'] = $field['type'];
			$field['bind'] = (object) [
				'label' => $field['label'],
				'prefix' => $field['prefix'],
				'suffix' => $field['suffix'],
				'rules' => $field['rules'],
				'multiple' => $field['multiple'],
				'items' => $field['items'],
			];
			return $field;
		}, $set->fields);

		// $set->post = [];
		// foreach($set->fields as $field) {
		// 	$name = $field['name']? $field['name']: $field['_id'];
		// 	$set->post[ $name ] = '';
		// }

		
		if ($set->is_edit AND $set->test) {
			$test_post = (object) [];
			if ($post = json_decode($set->test_post, true) AND is_array($post)) {
				$test_post = $post;
			}
			$set->test_post = $test_post;

			$set->step = intval($set->test_step);
		}
		else {
			$set->test_post = (object) [];
		}

		return $set;
	}


	public function content($set) {
		?>
		<div id="<?php echo $set->id; ?>">
			<v-app>
				<v-main>
					<v-container>
					<v-stepper v-model="step">
						<v-stepper-header>
							<template v-for="(stepItem, stepIndex) in steps">
								<v-stepper-step :complete="stepsCompletes[stepIndex]" :step="stepIndex">
									{{ stepItem.name }}
								</v-stepper-step>
								<v-divider v-if="stepIndex<steps.length-1" />
							</template>
						</v-stepper-header>
						<v-stepper-items>
							<template v-for="(stepItem, stepIndex) in steps">
								<v-stepper-content :step="stepIndex">
									<v-row>
										<template v-for="(fieldItem, fieldIndex) in fields">
											<template v-if="fieldItem.step==stepIndex">
												<v-col :cols="fieldItem.cols">
													<component
														:is="fieldItem.component"
														v-bind="fieldItem.bind"
														v-model="fieldItem.value"
													></component>
													<!-- <pre>{{ fieldItem }}</pre> -->
												</v-col>
											</template>
										</template>
									</v-row>
									<v-divider class="my-4"></v-divider>
									<div class="d-flex" style="gap:15px;">
										<v-btn color="primary" @click="step--" v-if="step>0">{{ act_prev_label }}</v-btn>
										<v-spacer></v-spacer>
										<v-btn color="primary" @click="step++" v-if="step<steps.length-1">{{ act_next_label }}</v-btn>
										<v-btn color="success" v-if="step==steps.length-1" @click="submit()">{{ act_finish_label }}</v-btn>
									</div>
								</v-stepper-content>
							</template>
						</v-stepper-items>
					</v-stepper>
					<v-row>
						<v-col cols="6">
							<pre>$data: {{ $data }}</pre>
						</v-col>
						<v-col cols="6">
							<pre>step: {{ step }}</pre>
							<pre>post: {{ post }}</pre>
							<pre>stepsCompletes: {{ stepsCompletes }}</pre>
						</v-col>
					</v-row>
					</v-container>
				</v-main>
			</v-app>
		</div>
		<?php
	}

	public function style($set) {
		return '
			--id .v-application--wrap {
				min-height: auto !important;
			}
		';
	}

	public function footer($set)
	{
		?>

		<script>
			new Vue({
				el: "#<?php echo $set->id; ?>",
				vuetify: new Vuetify(),
				data: <?php echo json_encode($set); ?>,
				methods: {
					submit() {
						alert('Aaa');
					},
				},
				computed: {
					post() {
						let post = this.test_post || {};

						this.fields.forEach(field => {
							post[ field.name || field._id ] = field.value;
						});

						return post;
					},
				},
			});
		</script>
		<?php
	}
};
