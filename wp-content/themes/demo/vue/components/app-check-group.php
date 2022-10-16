<?php

add_action('get_footer', function() {
  ?>
  <template id="app-check-group">
    <div>
      {{ label }}
      <v-radio-group
        v-model="value2"
        :multiple="multiple"
      >
        <template v-for="i in items">
          <v-radio
            :label="i.text"
            :value="i.value"
          ></v-radio>
        </template>
      </v-radio-group>
    </div>
  </template>

  <script>
    Vue.component('app-check-group', {
      template: '#app-check-group',
      props: {
        value: {default:'', type:[Boolean, Object, String]},
        label: {
          type: String,
          default: '',
        },
        multiple: {
          type: [Boolean],
          default: false,
        },
        items: {
          type: [Array],
          default: () => ([]),
        },
      },
      watch: {
        value2: {deep:true, handler(value) {
          value = JSON.parse(JSON.stringify(value));
          this.$emit('input', value);
          this.$emit('value', value);
        }},
      },
      data() {
        return {
          value2: (this.multiple? [this.value].filter(value => !!value): this.value),
        };
      },
    });
  </script>
  <?php
});
