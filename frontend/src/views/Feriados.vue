<template>
  <v-main>
    <v-container class="mt-5">
      <v-row>
        <v-col
            cols="12"
            sm="6"
        >
          <h1>Feriados</h1>
        </v-col>
        <v-col
            cols="12"
            sm="6"
            class="text-end"
        >
          <CadastrarFeriado />
        </v-col>
        <v-col cols="12">
          <TabelaFeriado />
        </v-col>
      </v-row>
    </v-container>
  </v-main>
</template>

<script>
  import CadastrarFeriado from '../components/CadastrarFeriado'
  import TabelaFeriado from '../components/TabelaFeriado'

  export default {
    components: {
      CadastrarFeriado,
      TabelaFeriado
    }
  }
</script>
