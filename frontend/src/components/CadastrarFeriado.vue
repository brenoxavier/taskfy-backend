<template>
  <span>
    <v-btn class="ml-auto" color="primary" @click="() => dialog = true">
      Novo Feriado
      <v-icon right>mdi-calendar-plus</v-icon>
    </v-btn>

    <v-dialog v-model="dialog" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <span class="text-h5">Novo Feriado</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col>
                <v-text-field label="Nome" v-model="feriado.descricao" required />
              </v-col>
              <v-col>
                <v-menu
                    v-model="menu"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    transition="scale-transition"
                    offset-y
                    min-width="auto"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                        v-model="feriado.data"
                        label="Data"
                        prepend-icon="mdi-calendar"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                      v-model="feriado.data"
                      @input="menu = false"
                  ></v-date-picker>
                </v-menu>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="dialog = false">Cancelar</v-btn>
          <v-btn color="blue darken-1" text @click="cadastrarUsuario">Cadastrar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </span>
</template>

<script>
  export default {
    data: () => ({
      dialog: false,
      menu: false,
      feriado: {
        data: undefined,
        descricao: undefined
      }
    }),
    methods: {
      async cadastrarUsuario () {
        this.dialog = false

        try {
          await this.$http.post('/feriado', this.feriado)
          this.$store.commit('setRecarregar')
          this.$toast.success('Feriado cadastrado com sucesso!')
        } catch (erro) {
          this.$toast.error('Falha ao cadastrar feriado.')
        }
      }
    }
  }
</script>
