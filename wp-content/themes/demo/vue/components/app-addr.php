<?php

add_action('get_footer', function() {
  ?>
  <template id="app-addr">
    <div>
      <v-menu offset-y>
        <template #activator="{ on, attrs }">
          <v-text-field
              :label="label"
              hide-details
              v-on="on"
              v-bind="attrs"
              v-model="search.params.q"
              :loading="search.loading"
              :rules="rules"
              :value="value"
              @input="searchRequest()"
          />
        </template>
        
        <v-list class="mt-2">
          <v-list-item v-if="search.items.length==0 && search.try==0">
            Digite o nome de uma cidade
          </v-list-item>
          <v-list-item v-if="search.items.length==0 && search.try!=0 && !search.loading">
            Nenhuma cidade encontrada
          </v-list-item>
          <template v-for="(place, index) in search.items">
            <v-divider v-if="index>0"></v-divider>
            <v-list-item @click="placeSelect(place)" class="py-2">
              {{ place.route }}, {{ place.district }}, {{ place.city }}, {{ place.state }}
            </v-list-item>
          </template>
        </v-list>
      </v-menu>

      <v-row v-if="valueSet" class="mt-4">
        <v-col cols="10"><v-text-field :hide-details="true" v-model="value2.route" label="Rua"></v-text-field></v-col>
        <v-col cols="2"><v-text-field :hide-details="true" v-model="value2.number" label="Nº"></v-text-field></v-col>
        <v-col cols="6"><v-text-field :hide-details="true" v-model="value2.postcode" label="CEP"></v-text-field></v-col>
        <v-col cols="6"><v-text-field :hide-details="true" v-model="value2.district" label="Bairro"></v-text-field></v-col>
        <v-col cols="6"><v-text-field :hide-details="true" v-model="value2.city" label="Cidade"></v-text-field></v-col>
        <v-col cols="6"><v-text-field :hide-details="true" v-model="value2.state" label="Estado"></v-text-field></v-col>
      </v-row>
    </div>
  </template>

  <script>
    Vue.component('app-addr', {
      template: '#app-addr',
      props: {
        value: {default:'', type:[Object, String]},
        label: {default:''},
        rules: {type:Array, default:()=>([])},
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
          timeout: false,
          value2: {},
          valueSet: false,
          search: {
            loading: false,
            params: {
              q: '',
              format: 'json',
              limit: 10,
              country: 'BR',
              addressdetails: 1,
            },
            items: [],
          },
        };
      },
      methods: {
        async searchRequest() {
          this.search.loading = true;
          if (this.timeout) clearTimeout(this.timeout);
          this.timeout = setTimeout(async () => {
            try {
              const { params } = this.search;
              this.search.loading = false;
              jQuery.get('https://nominatim.openstreetmap.org/search.php', params, (items) => {
                this.search.items = items.map(item => {
                  let row = { display_name: item.display_name };
                  row = { ...row, ...item.address };
                  row.route = item.address.route || item.address.road;
                  row.district = item.address.neighbourhood || item.address.suburb;
                  row.city = item.address.city || item.address.town || item.address.village || item.address.county;
                  ['road', 'neighbourhood', 'suburb', 'town', 'village', 'county', 'municipality', 'state_district'].forEach(attr => {
                    if (typeof row[attr] != 'undefined') delete row[attr];
                  });
                  return row;
                });
              });
            }
            catch(err) {
              this.search.loading = false;
              console.log(err);
            }
          }, 1500);
        },
        placeSelect(place) {
          this.valueSet = true;
          this.value2 = place;
        },
      },
    });
  </script>
  <?php
});
