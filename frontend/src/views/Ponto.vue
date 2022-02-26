<template>
  <v-main>
    <v-container class="mt-2">
      <v-row>
        <v-col
            cols="12"
            sm="12"
            :md="admin ? 6 : 8"
            class="pb-0 pb-sm-3"
        >
          <h1>Ponto Virtual</h1>
          <p v-if="relatorio.usuario.nome">{{ relatorio.usuario.nome }} - {{ dataRelatorio }}</p>
        </v-col>
        <v-col
            v-if="admin"
            cols="12"
            sm="4"
            md="2"
        >
          <EntradaManual :id-usuario="relatorio.usuario.id" />
        </v-col>
        <v-col
            cols="12"
            :sm="admin ? 4 : 6"
            md="2"
            class="py-0 py-sm-3"
        >
          <SeletorData :data-inicial="relatorio.data" @data="relatorio.data = $event" />
        </v-col>
        <v-col
            cols="12"
            :sm="admin ? 4 : 6"
            md="2"
        >
          <a
              :href="urlDownloadRelatorio"
              style="text-decoration: none !important;"
          >
            <v-btn
                width="100%"
                color="primary"
                :disabled="!token"
            >
              Baixar
              <v-icon right>mdi-cloud-download-outline</v-icon>
            </v-btn>
          </a>
        </v-col>
      </v-row>
      <ResumoMes
          :relatorio="relatorio"
      />
      <v-row>
        <GraficoHoras
            :dias-do-mes="relatorio.diasDoMes"
            :horas-trabalhadas="relatorio.horasTrabalhadas"
        />
        <TabelaHoras
            :horas-trabalhadas="relatorio.horasTrabalhadas"
        />
      </v-row>
    </v-container>
  </v-main>
</template>

<script>
  import EntradaManual from '../components/EntradaManual'
  import SeletorData from '../components/SeletorData'
  import ResumoMes from '../components/ResumoMes'
  import GraficoHoras from '../components/GraficoHoras'
  import TabelaHoras from '../components/TabelaHoras'

  export default {
    components: {
      EntradaManual,
      SeletorData,
      ResumoMes,
      GraficoHoras,
      TabelaHoras
    },
    data: () => ({
      token: '',
      admin: JSON.parse(localStorage.getItem('usuario')).admin,
      relatorio: {
        data: new Date(Date.now()).toISOString(),
        usuario: {
          id: 0,
          nome: '',
          fotoPerfil: ''
        },
        bancoHoras: {
          horas: 0,
          minutos: 0
        },
        diasDoMes: {
          dias: [],
          horasUteis: 0
        },
        horasTrabalhadas: {
          horasDiurnas: {
            horas: 0,
            minutos: 0
          },
          horasNoturnas: {
            horas: 0,
            minutos: 0
          },
          horasTotais: {
            horas: 0,
            minutos: 0
          },
          dias: []
        },
        saldoMensal: {
          horas: 0,
          minutos: 0,
          horasTotais: 0,
          minutosTotais: 0
        }
      }
    }),
    computed: {
      dataRelatorio () {
        const [ano, mes] = this.relatorio.data.split('-')
        return `${mes}/${ano}`
      },
      urlDownloadRelatorio () {
        let url = '/relatorio'
        url += this.$route.params.idUsuario ? `/${this.$route.params.idUsuario}` : ''

        if (this.relatorio.data) {
          const [ano, mes] = this.relatorio.data.split('-')
          url += `?data=${new Date(parseInt(ano), parseInt(mes) - 1).toISOString()}`
        }

        return `${this.$http.defaults.baseURL}${url}&pdf=1&token=${this.token}`
      }
    },
    watch: {
      'relatorio.data': function () {
        this.buscarRelatorio()
      }
    },
    mounted () {
      this.buscarRelatorio()
      this.gerarTokenAutenticacao()
    },
    methods: {
      async buscarRelatorio () {
        try {
          let url = '/relatorio'
          url += this.$route.params.idUsuario ? `/${this.$route.params.idUsuario}` : ''

          if (this.relatorio.data) {
            const [ano, mes] = this.relatorio.data.split('-')
            url += `?data=${new Date(parseInt(ano), parseInt(mes) - 1).toISOString()}`
          }

          const { data: resposta } = await this.$http.get(url)

          this.relatorio.usuario.id = resposta.usuario.id
          this.relatorio.usuario.nome = resposta.usuario.nome
          this.relatorio.usuario.fotoPerfil = resposta.usuario.fotoPerfil

          this.relatorio.bancoHoras.horas = resposta.banco_horas.horas
          this.relatorio.bancoHoras.minutos = resposta.banco_horas.minutos

          this.relatorio.diasDoMes.dias = resposta.dias_do_mes.dias
          this.relatorio.diasDoMes.horasUteis = resposta.dias_do_mes.horas_uteis

          this.relatorio.horasTrabalhadas.horasTotais.horas = resposta.horas_trabalhadas.horas_totais.horas
          this.relatorio.horasTrabalhadas.horasTotais.minutos = resposta.horas_trabalhadas.horas_totais.minutos
          this.relatorio.horasTrabalhadas.horasDiurnas.horas = resposta.horas_trabalhadas.horas_diurnas.horas
          this.relatorio.horasTrabalhadas.horasDiurnas.minutos = resposta.horas_trabalhadas.horas_diurnas.minutos
          this.relatorio.horasTrabalhadas.horasNoturnas.horas = resposta.horas_trabalhadas.horas_noturnas.horas
          this.relatorio.horasTrabalhadas.horasNoturnas.minutos = resposta.horas_trabalhadas.horas_noturnas.minutos
          this.relatorio.horasTrabalhadas.dias = resposta.horas_trabalhadas.dias

          this.relatorio.saldoMensal.horasTotais = resposta.saldo_mensal.horas_totais
          this.relatorio.saldoMensal.minutosTotais = resposta.saldo_mensal.minutos_totais
          this.relatorio.saldoMensal.horas = resposta.saldo_mensal.horas
          this.relatorio.saldoMensal.minutos = resposta.saldo_mensal.minutos

          this.$store.commit('setRecarregar')
        } catch (erro) {
          console.error(erro)
          this.$toast.error('Não foi possivel buscar informações do servidor.')
        }
      },
      async gerarTokenAutenticacao () {
        try {
          const { data } = await this.$http.get('/token')
          this.token = data.token
        } catch (erro) {
          this.$toast.error('Devido a um problema inesperado, não será possivel gerar o relatorio.')
        }
      }
    }
  }
</script>
