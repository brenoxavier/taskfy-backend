<template>
  <v-dialog
      ref="dialog"
      v-model="dialog"
      persistent
      max-width="600px"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-btn
          width="100%"
          color="warning"
          v-bind="attrs"
          v-on="on"
      >
        Manual
        <v-icon right>mdi-pencil-outline</v-icon>
      </v-btn>
    </template>
    <v-card>
      <v-card-title>
        <span class="text-h5">Entrada Manual</span>
      </v-card-title>
      <v-card-actions>
        <v-container>
          <v-form>
            <v-row>
              <v-col cols="6">
                <v-menu
                    v-model="menuInicio"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    transition="scale-transition"
                    offset-y
                    min-width="auto"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                        v-model="dataInicio"
                        label="Início"
                        prepend-icon="mdi-calendar"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                    />
                  </template>
                  <v-date-picker
                      v-model="dataInicio"
                      @input="menuInicio = false"
                  />
                </v-menu>
              </v-col>
              <v-col cols="6">
                <v-menu
                    v-model="menuFim"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    transition="scale-transition"
                    offset-y
                    min-width="auto"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                        v-model="dataFim"
                        label="Fim"
                        prepend-icon="mdi-calendar"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                    />
                  </template>
                  <v-date-picker
                      v-model="dataFim"
                      @input="menuFim = false"
                  />
                </v-menu>
              </v-col>
              <v-col cols="6">
                <v-menu
                    ref="menuInicio"
                    v-model="menuInicioHoras"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    :return-value.sync="inicioHoras"
                    transition="scale-transition"
                    offset-y
                    max-width="290px"
                    min-width="290px"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                        v-model="inicioHoras"
                        label="Inicio"
                        prepend-icon="mdi-clock-time-four-outline"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                    ></v-text-field>
                  </template>
                  <v-time-picker
                      v-if="menuInicioHoras"
                      format="24hr"
                      v-model="inicioHoras"
                      full-width
                      @click:minute="$refs.menuInicio.save(inicioHoras)"
                  />
                </v-menu>
              </v-col>
              <v-col cols="6">
                <v-menu
                    ref="menuFim"
                    v-model="menuFimHoras"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    :return-value.sync="fimHoras"
                    transition="scale-transition"
                    offset-y
                    max-width="290px"
                    min-width="290px"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                        v-model="fimHoras"
                        label="Fim"
                        prepend-icon="mdi-clock-time-four-outline"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                    ></v-text-field>
                  </template>
                  <v-time-picker
                      v-if="menuFimHoras"
                      format="24hr"
                      v-model="fimHoras"
                      full-width
                      @click:minute="$refs.menuFim.save(fimHoras)"
                  />
                </v-menu>
              </v-col>
              <v-col cols="12">
                <v-textarea
                    label="Motivo"
                    v-model="motivo"
                />
              </v-col>
            </v-row>
          </v-form>
        </v-container>
      </v-card-actions>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
            color="blue darken-1"
            text
            @click="dialog = false"
        >
          Cancelar
        </v-btn>
        <v-btn
            color="blue darken-1"
            text
            @click="cadastrarEntradaManual"
        >
          Cadastrar
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    props: ['idUsuario'],
    data: () => ({
      dialog: false,
      menuInicio: false,
      menuFim: false,
      menuInicioHoras: false,
      menuFimHoras: false,
      dataInicio: '',
      dataFim: '',
      inicioHoras: '',
      fimHoras: '',
      motivo: ''
    }),
    methods: {
      async cadastrarEntradaManual () {
        try {
          await this.$http.post('/entrada', {
            id_usuario: this.idUsuario,
            inicio: `${this.dataInicio} ${this.inicioHoras}`,
            fim: `${this.dataFim} ${this.fimHoras}`,
            motivo: this.motivo
          })

          this.dialog = false
          this.$toast.success('Entrada cadastrada com sucesso!')
        } catch (erro) {
          this.$toast.error('Não foi possivel cadastrar a entrada manual.')
        }
      }
    }
  }
</script>
