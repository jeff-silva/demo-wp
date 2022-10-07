<?php

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
		$inputs['form-text'] = (object) [
			'name' => 'Texto simples',
			'template' => '<v-text-field></v-text-field>',
			'vue' => '{
				template: "#form-text",
				props: {},
			}',
		];
		
		$inputs['form-textarea'] = (object) [
			'name' => 'Texto multilinha',
			'template' => '<div>Textarea</div>',
			'vue' => '{
				template: "#form-textarea",
			}',
		];
		
		$inputs['form-email'] = (object) [
			'name' => 'E-mail',
			'template' => '<v-text-field type="email"></v-text-field>',
			'vue' => '{
				template: "#form-email",
			}',
		];
		
		$inputs['form-phone'] = (object) [
			'name' => 'Telefone',
			'template' => '<v-text-field type="phone"></v-text-field>',
			'vue' => '{
				template: "#form-phone",
			}',
		];
		
		$inputs['form-password'] = (object) [
			'name' => 'Senha',
			'template' => '<v-text-field type="password"></v-text-field>',
			'vue' => '{
				template: "#form-password",
			}',
		];
		
		$inputs['form-number'] = (object) [
			'name' => 'Numérico',
			'template' => '<v-text-field type="number"></v-text-field>',
			'vue' => '{
				template: "#form-number",
			}',
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
		$this->start_controls_section('section_heading', [
			'label' => 'Configurações',
		]);

		// $this->add_control('title', [
		// 	'label' => 'Título',
		// 	'type' => \Elementor\Controls_Manager::TEXT,
		// 	'default' => 'Cálculo de IMC',
		// 	'label_block' => true,
		// ]);

		// $this->add_control('color', [
		// 	'label' => 'Color de fundo',
		// 	'type' => \Elementor\Controls_Manager::COLOR,
		// 	'default' => '#000000',
		// ]);
		
		// $this->add_control('peso', [
		// 	'label' => 'Peso inicial',
		// 	'type' => \Elementor\Controls_Manager::NUMBER,
		// 	'default' => '75',
		// ]);
		
		// $this->add_control('altura', [
		// 	'label' => 'Altura inicial',
		// 	'type' => \Elementor\Controls_Manager::NUMBER,
		// 	'default' => '175',
		// ]);
		
		// $this->add_control('resultado_abaixo', [
		// 	'label' => 'Texto peso abaixo',
		// 	'type' => \Elementor\Controls_Manager::TEXT,
		// 	'default' => 'Peso abaixo do ideal',
		// 	'label_block' => true,
		// ]);
		
		// $this->add_control('resultado_regular', [
		// 	'label' => 'Texto peso ideal',
		// 	'type' => \Elementor\Controls_Manager::TEXT,
		// 	'default' => 'Peso dentro do ideal',
		// 	'label_block' => true,
		// ]);

		// $this->add_control('resultado_sobrepeso', [
		// 	'label' => 'Texto sobrepeso',
		// 	'type' => \Elementor\Controls_Manager::TEXT,
		// 	'default' => 'Sobrepeso',
		// 	'label_block' => true,
		// ]);

		// $this->add_control('resultado_obesidade', [
		// 	'label' => 'Texto obesidade',
		// 	'type' => \Elementor\Controls_Manager::TEXT,
		// 	'default' => 'Obesidade',
		// 	'label_block' => true,
		// ]);

		// $this->add_control('resultado_obesidade_grave', [
		// 	'label' => 'Texto obesidade grave',
		// 	'type' => \Elementor\Controls_Manager::TEXT,
		// 	'default' => 'Obesidade grave',
		// 	'label_block' => true,
		// ]);

		$this->end_controls_section();



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

		$this->start_controls_section('section_test', [
			'label' => 'Testes',
		]);

		$this->end_controls_section();
	}

	public function render_full() {
		$set = $this->computed_settings();
		$set->step = 0;

		$set->fields = array_map(function($field) {
			$field['component'] = $field['type'];
			$field['bind'] = (object) [
				'rules' => $field['rules'],
			];
			return $field;
		}, $set->fields);

		$set->post = [];
		foreach($set->fields as $field) {
			$set->post[ $field['_id'] ] = '';
		}

		?>
		<div id="<?php echo $set->id; ?>">
			<v-app>
				<v-main>
					<v-container>
					<v-stepper v-model="step">
						<v-stepper-header>
							<template v-for="(stepItem, stepIndex) in steps">
								<v-stepper-step :complete="true" :step="stepIndex">
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
												<v-col cols="12" md="6">
													<component
														:is="fieldItem.component"
														v-bind="fieldItem.bind"
														v-model="post[fieldItem._id]"
													></component>
													<!-- <pre>{{ fieldItem }}</pre> -->
												</v-col>
											</template>
										</template>
									</v-row>
									<div class="d-flex mt-4 mb-1" style="gap:15px;">
										<v-btn color="primary" @click="step--" v-if="step>0">{{ act_prev_label }}</v-btn>
										<v-spacer></v-spacer>
										<v-btn color="primary" @click="step++" v-if="step<steps.length-1">{{ act_next_label }}</v-btn>
										<v-btn color="success" v-if="step==steps.length-1">{{ act_finish_label }}</v-btn>
									</div>
								</v-stepper-content>
							</template>
						</v-stepper-items>
					</v-stepper>
					<pre>$data: {{ $data }}</pre>
					</v-container>
				</v-main>
			</v-app>
		</div>

		<?php foreach(ElementorFormContactSupport::inputs() as $name => $input): ?> 
		<template id="<?php echo $name; ?>">
			<?php echo $input->template; ?> 
		</template>
		<script>
			Vue.component("<?php echo $name; ?>", <?php echo $input->vue; ?>);
		</script>
		<?php endforeach; ?>

		<script>
		new Vue({
			el: "#<?php echo $set->id; ?>",
			vuetify: new Vuetify(),
			data: <?php echo json_encode($set); ?>,
		});
		</script>
		<?php
	}

	public function render_style() {
		return '
			--id .v-application--wrap {
				min-height: auto !important;
			}
		';
	}
};
