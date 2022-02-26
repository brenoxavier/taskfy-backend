<template>
  <v-col cols="12">
    <v-card>
      <v-card-actions>
        <v-data-table
            :headers="cabecalho"
            :items="valores"
            :single-expand="true"
            :expanded.sync="expandido"
            item-key="dia"
            :show-expand="$vuetify.breakpoint.mdAndUp"
            disable-sort
            hide-default-footer
            :loading="$store.state.carregando"
            style="width: 100%"
        >
          <template v-slot:top>
            <v-toolbar flat>
              <v-toolbar-title class="font-weight-bold">Histórico do Mês</v-toolbar-title>
            </v-toolbar>
          </template>
          <template v-slot:expanded-item="{ headers, item }">
            <td :colspan="headers.length">
              <v-container fluid>
                <v-simple-table dense elevation="0">
                  <template v-slot:default>
                    <thead>
                      <tr>
                        <th>Inicio</th>
                        <th>Fim</th>
                        <th>Horas Trabalhadas</th>
                        <th>Horas Diurnas</th>
                        <th>Horas Noturnas</th>
                        <th>Manual</th>
                        <th
                            v-if="admin"
                            class="text-right"
                        >
                          Excluir
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="entrada in item.entradas" :key="entrada.inicio">
                        <td>{{ formatarDataTempo(entrada.inicio) }}</td>
                        <td>{{ formatarDataTempo(entrada.fim) }}</td>
                        <td>{{ formatarTempo(entrada.horas_totais.horas) }}:{{ formatarTempo(entrada.horas_totais.minutos) }}</td>
                        <td>{{ formatarTempo(entrada.horas_diurnas.horas) }}:{{ formatarTempo(entrada.horas_diurnas.minutos) }}</td>
                        <td>{{ formatarTempo(entrada.horas_noturnas.horas) }}:{{ formatarTempo(entrada.horas_noturnas.minutos) }}</td>
                        <td v-if="entrada.motivo">
                          <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                              <a
                                  v-bind="attrs"
                                  v-on="on"
                              >Sim</a>
                            </template>
                            <span>{{ entrada.motivo }}</span>
                          </v-tooltip>
                        </td>
                        <td v-else>Não</td>
                        <td
                            v-if="admin"
                            class="text-right"
                        >
                          <a @click="deletarEntrada(entrada.id)">
                            <v-icon>mdi-delete</v-icon>
                          </a>
                        </td>
                      </tr>
                    </tbody>
                  </template>
                </v-simple-table>
              </v-container>
            </td>
          </template>
        </v-data-table>
      </v-card-actions>
    </v-card>
  </v-col>
</template>

<script>
  import { format } from 'date-fns'

  export default {
    props: ['horasTrabalhadas'],
    data: () => ({
      admin: JSON.parse(localStorage.getItem('usuario')).admin,
      expandido: [],
      cabecalho: [
        { text: 'Dia', sortable: false, value: 'dia' },
        { text: 'Entradas de Tempo', sortable: false, value: 'entradasTempo' },
        { text: 'Horas trabalhadas', value: 'horasTrabalhadas' },
        { text: 'Horas Diurnas', value: 'horasDiurnas' },
        { text: 'Horas Noturnas', value: 'horasNoturnas' }
      ],
      valores: []
    }),
    watch: {
      '$store.state.recarregar': function () {
        this.preencherTabela()
      }
    },
    methods: {
      async deletarEntrada (idEntrada) {
        try {
          await this.$http.delete(`/entrada/${idEntrada}`)
          this.$toast.success('Entrada deletada com sucesso!')
        } catch (erro) {
          this.$toast.error('Não foi possivel deletar a entrada.')
        }
      },
      preencherTabela () {
        const dias = Object.keys(this.horasTrabalhadas.dias)
        this.valores = []

        dias.reverse().forEach(chave => {
          const dia = this.horasTrabalhadas.dias[chave]

          this.valores.push({
            dia: format(new Date(dia.entradas_de_tempo[0].inicio), 'dd/MM/yyyy'),
            entradasTempo: dia.entradas_de_tempo.length,
            horasTrabalhadas: `${this.formatarTempo(dia.horas_totais.horas)}:${this.formatarTempo(dia.horas_totais.minutos)}`,
            horasDiurnas: `${this.formatarTempo(dia.horas_diurnas.horas)}:${this.formatarTempo(dia.horas_diurnas.minutos)}`,
            horasNoturnas: `${this.formatarTempo(dia.horas_noturnas.horas)}:${this.formatarTempo(dia.horas_noturnas.minutos)}`,
            entradas: dia.entradas_de_tempo
          })
        })
      },
      formatarTempo (valor) {
        return (valor < 10 && valor >= 0) ? '0' + valor : valor
      },
      formatarDataTempo (data) {
        return format(new Date(data), 'dd/MM/yyyy - HH:mm')
      }
    }
  }
</script>
