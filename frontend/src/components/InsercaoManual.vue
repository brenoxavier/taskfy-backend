<template>
  <div>
    <v-dialog v-model="dialog" max-width="600px">
      <template v-slot:activator="{ bind, on }">
        <v-btn :disabled="$store.state.ponto.ativo" v-bind="bind" v-on="on" color="primary">
          Manual
          <v-icon right>mdi-pencil-outline</v-icon>
        </v-btn>
      </template>
      <v-card>
        <v-card-title>
          <span class="text-h5">Inserção Manual</span>
        </v-card-title>
        <v-card-text>
          <v-radio-group v-model="acao" row>
            <v-radio label="Entrada" value="entrada"></v-radio>
            <v-radio label="Saida" value="saida"></v-radio>
          </v-radio-group>
          <v-row>
            <v-col>
              <v-menu
                  ref="menu"
                  v-model="menuData"
                  :close-on-content-click="false"
                  :return-value.sync="data"
                  transition="scale-transition"
                  offset-y
                  min-width="auto"
              >
                <template v-slot:activator="{ bind, on }">
                  <v-text-field
                      v-model="data"
                      label="Data"
                      prepend-icon="mdi-calendar"
                      readonly
                      v-bind="bind"
                      v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker v-model="data" no-title scrollable>
                  <v-spacer></v-spacer>
                  <v-btn
                      text
                      color="primary"
                      @click="menuData = false"
                  >
                    Cancelar
                  </v-btn>
                  <v-btn
                      text
                      color="primary"
                      @click="$refs.menu.save(data)"
                  >
                    Confirmar
                  </v-btn>
                </v-date-picker>
              </v-menu>
            </v-col>
            <v-col>
              <v-menu
                  ref="menu"
                  v-model="menuHorario"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  :return-value.sync="horario"
                  transition="scale-transition"
                  offset-y
                  max-width="290px"
                  min-width="290px"
              >
                <template v-slot:activator="{ bind, on }">
                  <v-text-field
                      v-model="horario"
                      label="Horário"
                      prepend-icon="mdi-clock-time-four-outline"
                      readonly
                      v-bind="bind"
                      v-on="on"
                  ></v-text-field>
                </template>
                <v-time-picker
                    v-if="menuHorario"
                    v-model="horario"
                    format="24hr"
                    full-width
                    @click:minute="$refs.menu.save(horario)"
                ></v-time-picker>
              </v-menu>
            </v-col>
          </v-row>
          <v-textarea
              v-model="motivo"
              label="Motivo"
              rows="1"
          ></v-textarea>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" @click="dialog = false" text>Cancelar</v-btn>
          <v-btn color="blue darken-1" @click="dialog = false" text>Confirmar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
  export default {
    data: () => ({
      dialog: false,
      menuData: false,
      menuHorario: false,
      horario: '',
      data: '',
      motivo: '',
      acao: 'entrada'
    })
  }
</script>
