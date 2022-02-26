<template>
  <v-card height="100%">
    <v-card-title>
      <div>
        <div class="text-caption">19 de agosto de 2021</div>
        <div class="text-h4">
          <cronometro :ativo="$store.state.ponto.ativo" :tempo-inicial="$store.state.ponto.horario" />
        </div>
      </div>
    </v-card-title>
    <v-card-actions>
      <v-btn
          :disabled="$store.state.ponto.ativo"
          @click="iniciar"
          color="success"
      >
        Entrar
        <v-icon right>mdi-timer-outline</v-icon>
      </v-btn>
      <v-btn
          :disabled="!$store.state.ponto.ativo"
          @click="parar"
          color="error"
      >
        Sair
        <v-icon right>mdi-timer-off-outline</v-icon>
      </v-btn>
      <isercao-manual class="ml-2" />
    </v-card-actions>
  </v-card>
</template>

<script>
  import Cronometro from './Cronometro.vue'
  import InsercaoManual from './InsercaoManual.vue'

  export default {
    components: {
      cronometro: Cronometro,
      'isercao-manual': InsercaoManual
    },
    data: () => ({
      horas: 0,
      minutos: 0,
      segundos: 0,
      contador: undefined
    }),
    methods: {
      iniciar () {
        this.$store.commit('setPontoAtivo', true)
        this.$store.commit('setHorario')
      },
      parar () {
        this.$store.commit('setPontoAtivo', false)
      }
    }
  }
</script>
