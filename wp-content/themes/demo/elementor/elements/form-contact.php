<?php

boot_include('/vue/components/app-addr.php');
boot_include('/vue/components/app-check-group.php');

$class = new class extends \Elementor\Widget_Base {

	use UtilsTrait;

	public function get_name() {
		return pathinfo(__FILE__, PATHINFO_FILENAME);
	}

	public function get_title() {
		return 'Demo: Formulário de contato';
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

		$this->start_controls_section('section_fields', [
			'label' => 'Campos',
		]);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control('label', [
			'label' => 'Label',
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => '',
			'label_block' => true,
			'separator' => 'before',
		]);

		$repeater->add_control('type', [
			'label' => 'Tipo',
			'type' => \Elementor\Controls_Manager::SELECT2,
			'default' => 'v-text-field',
			'label_block' => true,
			'options' => ElementorFormContactSupport::inputsOptions(),
			'separator' => 'after',
		]);

		$repeater->add_control('name', [
			'label' => 'Atributo name="%"',
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => '',
			'label_block' => true,
		]);

		$repeater->add_control('value', [
			'label' => 'Valor padrão',
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
			'separator' => 'after',
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
		
		$repeater->add_control('multiple', [
			'label' => 'Ítens multiplos?',
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'default' => '',
			'label_on' => 'Múltiplos',
			'label_off' => 'Somente um',
			'return_value' => '1',
			'label_block' => true,
			'separator' => 'after',
		]);
		
		$repeater->add_control('attrs', [
			'label' => 'Atributos',
			'type' => \Elementor\Controls_Manager::TEXTAREA,
			'default' => '',
			'label_block' => true,
		]);
		
		$repeater->add_control('items', [
			'label' => 'Options items',
			'type' => \Elementor\Controls_Manager::TEXTAREA,
			'default' => '',
			'label_block' => true,
		]);
		
		$repeater->add_control('html', [
			'label' => 'Descrição',
			'type' => \Elementor\Controls_Manager::CODE,
			'default' => '',
			'label_block' => true,
		]);

		$this->add_control('fields', [
			'label' => 'Campos',
			'type' => \Elementor\Controls_Manager::REPEATER,
			'fields' => $repeater->get_controls(),
			'default' => [],
			'title_field' => '{{{ label }}}',
			'prevent_empty' => false,
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


	public function multilineOptions($text, $assoc=false) {
		$options = [];
		foreach(explode("\n", $text) as $option) {
			list($value, $text) = explode('=', $option);
			if (!$value OR !$text) continue;

			if ($assoc) {
				$options[ $value ] = $text;
			}
			else {
				$options[] = [
					'text' => $text,
					'value' => $value,
				];
			}
		}
		return $options;
	}


	public function settings($set) {
		$set->step = 0;
		$set->steps = [];

		$set_steps = [];
		$set_fields = [];
		$set_steps_step = -1;
		
		foreach($set->fields as $index => $field) {
			if ($field['type'] == 'app-form-step') {
				$set_steps[] = $field;
				$set_steps_step++;
				continue;
			}

			// Items options
			$field['items'] = $this->multilineOptions($field['items'], false);
			$field['attrs'] = $this->multilineOptions($field['attrs'], true);

			// Render element
			$field['bind'] = array_merge([
				'is' => false,
				'label' => $field['label'],
				'rules' => $field['rules'],
				'multiple' => $field['multiple'],
				'items' => $field['items'],
			], $field['attrs']);

			foreach(ElementorFormContactSupport::inputs() as $key => $value) {
				if ($field['type'] != $key) continue;
				$field['bind']['is'] = $value->component;
			}

			// Value array
			if ($field['bind']['multiple']) {
				$value = json_decode($field['multiple'], true);
				$field['value'] = is_array($value)? $value: [];
			}
			else {
				unset($field['bind']['multiple']);
			}
			
			$field['step'] = max(0, $set_steps_step);
			$set_fields[] = $field;
		}

		if (empty($set_steps)) {
			$set_steps[] = [
				'_id' => uniqid(),
				'label' => 'Default',
				'type' => 'app-form-step',
			];
		}

		$set->steps = $set_steps;
		$set->fields = $set_fields;

		$set->stepsCompletes = array_map(function($field) {
			return true;
		}, $set->steps);

		
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


		$set->inputs = ElementorFormContactSupport::inputs();

		return $set;
	}


	public function content($set) {
		?>
		<div id="<?php echo $set->id; ?>">
			<v-app>
				<v-main>
					<v-container>
						<a href="">refresh</a><br><br>
						<v-stepper v-model="step">
							<v-stepper-header v-if="steps.length>1">
								<template v-for="(stepItem, stepIndex) in steps">
									<v-stepper-step :complete="stepsCompletes[stepIndex]" :step="stepIndex">
										{{ stepItem.label }}
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
															v-bind="componentBind(fieldItem)"
															v-model="fieldItem.value"
														></component>
														<div v-html="fieldItem.html"></div>
														<!-- <v-card class="pa-2"><pre>{{ fieldItem.bind }}</pre></v-card> -->
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
					</v-container>
				</v-main>
				<v-row>
					<v-col cols="4">
						<pre>steps: {{ steps }}</pre>
						<pre>inputs: {{ inputs }}</pre>
					</v-col>
					<v-col cols="4">
						<pre>fields: {{ fields }}</pre>
					</v-col>
					<v-col cols="4">
						<pre>step: {{ step }}</pre>
						<pre>post: {{ post }}</pre>
						<pre>stepsCompletes: {{ stepsCompletes }}</pre>
					</v-col>
				</v-row>
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
					componentBind(field) {
						let comp = this.inputs[field.type];
						return { ...comp.bind, ...field.bind };
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
			
			$rules['cpf'] = (object) [
				'name' => 'CPF',
				'callback' => function($value) {
					return true;
				},
			];
			
			$rules['cnpj'] = (object) [
				'name' => 'CNPJ',
				'callback' => function($value) {
					return true;
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
			$inputs['div'] = (object) [
				'name' => 'Nenhum',
				'component' => 'div',
				'bind' => (object) [],
			];
			
			$inputs['v-text-field'] = (object) [
				'name' => 'Texto simples',
				'component' => 'v-text-field',
				'bind' => (object) [],
			];
			
			$inputs['v-text-field-password'] = (object) [
				'name' => 'Password',
				'component' => 'v-text-field',
				'bind' => (object) [
					'type' => 'password',
				],
			];
			
			$inputs['v-text-field-number'] = (object) [
				'name' => 'Número',
				'component' => 'v-text-field',
				'bind' => (object) [
					'type' => 'number',
				],
			];
			
			$inputs['v-select'] = (object) [
				'name' => 'Select',
				'component' => 'v-select',
				'bind' => (object) [
					'type' => 'password',
				],
			];
			
			$inputs['app-check-group-checkbox'] = (object) [
				'name' => 'Checkbox group',
				'component' => 'app-check-group',
				'bind' => (object) [
					'multiple' => true,
				],
			];
			
			$inputs['app-check-group-radio'] = (object) [
				'name' => 'Radio group',
				'component' => 'app-check-group',
				'bind' => (object) [
					'multiple' => false,
				],
			];
			
			$inputs['v-textarea'] = (object) [
				'name' => 'Texto multilinha',
				'component' => 'v-textarea',
				'bind' => (object) [],
			];
			
			$inputs['v-file'] = (object) [
				'name' => 'Arquivo',
				'component' => 'v-file',
				'bind' => (object) [],
			];
			
			$inputs['v-switch'] = (object) [
				'name' => 'Switch',
				'component' => 'v-switch',
				'bind' => (object) [],
			];
			
			$inputs['app-addr'] = (object) [
				'name' => 'Endereço',
				'component' => 'app-addr',
				'bind' => (object) [],
			];
			
			$inputs['app-form-step'] = (object) [
				'name' => 'Separador step',
				'component' => 'app-form-step',
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

return $class;