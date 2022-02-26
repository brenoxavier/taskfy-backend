<template>
  <v-col cols="12" v-if="$vuetify.breakpoint.smAndUp">
    <v-card height="100%" :loading="$store.state.carregando">
      <v-card-title>Grafico de Horas</v-card-title>
      <VueApexCharts
          ref="grafico"
          type="bar"
          height="100%"
          :options="configuracoes"
          :series="dados"
      />
    </v-card>
  </v-col>
</template>

<script>
  import VueApexCharts from 'vue-apexcharts'
  import { getDate } from 'date-fns'

  export default {
    components: {
      VueApexCharts
    },
    props: ['diasDoMes', 'horasTrabalhadas'],
    data: () => ({
      dados: [
        {
          name: 'Horas Trabalhadas',
          data: []
        }
      ],
      configuracoes: {
        chart: {
          toolbar: {
            show: false
          },
          height: 350,
          type: 'bar'
        },
        plotOptions: {
          bar: {
            columnWidth: '60%'
          }
        },
        colors: ['#1976d2'],
        dataLabels: {
          enabled: false
        }
      }
    }),
    watch: {
      '$store.state.recarregar': function () {
        this.preencherGrafico()
      }
    },
    methods: {
      preencherGrafico () {
        const data = []

        this.diasDoMes.dias.forEach((dia) => {
          const diaMes = getDate(new Date(dia.data))
          let horas = 0

          if (this.horasTrabalhadas.dias[diaMes]) {
            horas = this.horasTrabalhadas.dias[diaMes].horas_totais.horas
          }

          data.push({
            x: diaMes,
            y: horas,
            goals: [
              {
                name: 'Carga Horaria',
                value: dia.horas_uteis,
                strokeColor: '#4caf50'
              }
            ]
          })
        })

        this.$refs.grafico.updateSeries([{ data }])
      }
    }
  }
</script>
