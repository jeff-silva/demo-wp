<?php

return new class extends \Elementor\Widget_Base {

	use UtilsTrait;

	public function get_name() {
		return pathinfo(__FILE__, PATHINFO_FILENAME);
	}

	public function get_title() {
		return 'Calculadora IMC';
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

		$this->add_control('title', [
			'label' => 'Título',
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => 'Cálculo de IMC',
			'label_block' => true,
		]);

		$this->add_control('color', [
			'label' => 'Color de fundo',
			'type' => \Elementor\Controls_Manager::COLOR,
			'default' => '#000000',
		]);
		
		$this->add_control('peso', [
			'label' => 'Peso inicial',
			'type' => \Elementor\Controls_Manager::NUMBER,
			'default' => '75',
		]);
		
		$this->add_control('altura', [
			'label' => 'Altura inicial',
			'type' => \Elementor\Controls_Manager::NUMBER,
			'default' => '175',
		]);
		
		$this->add_control('resultado_abaixo', [
			'label' => 'Texto peso abaixo',
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => 'Peso abaixo do ideal',
			'label_block' => true,
		]);
		
		$this->add_control('resultado_regular', [
			'label' => 'Texto peso ideal',
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => 'Peso dentro do ideal',
			'label_block' => true,
		]);

		$this->add_control('resultado_sobrepeso', [
			'label' => 'Texto sobrepeso',
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => 'Sobrepeso',
			'label_block' => true,
		]);

		$this->add_control('resultado_obesidade', [
			'label' => 'Texto obesidade',
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => 'Obesidade',
			'label_block' => true,
		]);

		$this->add_control('resultado_obesidade_grave', [
			'label' => 'Texto obesidade grave',
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => 'Obesidade grave',
			'label_block' => true,
		]);

		$this->end_controls_section();
	}

	public function content($set) {
		$data = [
			'input' => [
				'peso' => intval($set->peso),
				'altura' => intval($set->altura),
			],
		];

		?>
		<div id="<?php echo $set->id; ?>">
			<v-app>
				<v-main>
					<v-container>
						<v-card color="<?php echo $set->color; ?>">
							<v-card-title><?php echo $set->title; ?></v-card-title>
							<v-card-text>
								<v-alert
									:color="imcResult.color"
									:type="imcResult.icon"
								>
									{{ imcResult.text }}
								</v-alert>
								<br>
								<v-row>
									<v-col cols="12" md="6">
										<v-text-field
											label="Peso"
											v-model.number="input.peso"
											type="number"
											suffix="Kg"
											min="0"
										></v-text-field>
									</v-col>
									<v-col cols="12" md="6">
										<v-text-field
											label="Altura"
											v-model.number="input.altura"
											type="number"
											suffix="Centimetros"
											min="0"
										></v-text-field>
									</v-col>
								</v-row>
							</v-card-text>
						</v-card>
					</v-container>
				</v-main>
			</v-app>
		</div>
		<?php
	}

	public function style() {
		return '
			--id .v-application--wrap {
				min-height: auto !important;
			}
		';
	}

	public function footer($set) {
		?>
		<script>
			new Vue({
				el: "#<?php echo $set->id; ?>",
				vuetify: new Vuetify(),
				data: <?php echo json_encode($data); ?>,
				computed: {
					imcResult() {
						let altura = this.input.altura / 100;
						let imc = Math.max(0, this.input.peso / (altura * altura));
						let text = 'Seu peso está dentro do ideal';
						let color = 'green';
						let icon = 'success';

						if (imc<18.5) {
							text = '<?php echo $set->resultado_abaixo; ?>';
							color = 'red';
							icon = 'error';
						}
						else if (imc>18.5 && imc<=25) {
							text = '<?php echo $set->resultado_regular; ?>';
							color = 'green';
							icon = 'success';
						}
						else if (imc>25 && imc<=30) {
							text = '<?php echo $set->resultado_sobrepeso; ?>';
							color = 'yellow';
							icon = 'warning';
						}
						else if (imc>30 && imc<=40) {
							text = '<?php echo $set->resultado_obesidade; ?>';
							color = 'red';
							icon = 'error';
						}
						else if (imc>40) {
							text = '<?php echo $set->resultado_obesidade_grave; ?>';
							color = 'red';
							icon = 'error';
						}

						return { imc, text, color, icon };
					},
				},
			});
		</script>
		<?php
	}
};
