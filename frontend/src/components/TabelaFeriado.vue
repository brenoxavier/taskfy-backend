<template>
  <v-card>
    <!--    <v-card-title>-->
    <!--      <span class="text-h5">Feriados</span>-->
    <!--    </v-card-title>-->
    <v-card-text>
      <v-simple-table v-if="feriados.length > 0">
        <template v-slot:default>
          <thead>
            <tr>
              <th class="text-left">Nome</th>
              <th class="text-left">Data</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr
                v-for="feriado in feriados"
                :key="feriado.data"
            >
              <td>{{ feriado.descricao }}</td>
              <td>{{ formatarData(feriado.data) }}</td>
              <td class="text-right">
                <a @click="deletarFeriado(feriado.id)">
                  <v-icon>mdi-delete</v-icon>
                </a>
              </td>
            </tr>
          </tbody>
        </template>
      </v-simple-table>
      <p v-else
         class="mb-0"
      >
        Não há feriados cadastrados.
      </p>
    </v-card-text>
  </v-card>
</template>

<script>
  import { format } from 'date-fns'

  export default {
    data: () => ({
      feriados: []
    }),
    watch: {
      '$store.state.recarregar': function () {
        this.buscarFeriados()
      }
    },
    mounted () {
      this.buscarFeriados()
    },
    methods: {
      async buscarFeriados () {
        try {
          const { data } = await this.$http.get('/feriado/listar')
          this.feriados = data
        } catch (erro) {
          console.error(erro)
          this.$toast.error('Não foi possivel buscar informações no servidor.')
        }
      },
      async deletarFeriado (id) {
        try {
          await this.$http.delete(`/feriado/${id}`)
          this.$store.commit('setRecarregar')
          this.$toast.success('Feriado deletado com sucesso!')
        } catch (erro) {
          this.$toast.error('Não foi possivel deletar o feriado.')
        }
      },
      formatarData (data) {
        return format(new Date(data), 'dd/MM/yyyy')
      }
    }
  }
</script>
