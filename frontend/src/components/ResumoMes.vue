<template>
  <v-row>
    <v-col cols="12" sm="6" md="3">
      <v-card>
        <v-card-title>Horas Realizadas</v-card-title>
        <v-card-text>
          <v-row>
            <v-col class="text-h4">
              {{ formatarTempo(relatorio.horasTrabalhadas.horasTotais.horas) }}:{{ formatarTempo(relatorio.horasTrabalhadas.horasTotais.minutos) }}
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-col>
    <v-col cols="12" sm="6" md="3">
      <v-card>
        <v-card-title>Saldo do Mês</v-card-title>
        <v-card-text>
          <v-row>
            <v-col class="text-h4">
              {{ formatarTempoNegativo(relatorio.saldoMensal.horasTotais, relatorio.saldoMensal.minutosTotais) }}{{ formatarTempo(Math.abs(relatorio.saldoMensal.horasTotais)) }}:{{ formatarTempo(Math.abs(relatorio.saldoMensal.minutosTotais)) }}
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-col>
    <v-col cols="12" sm="6" md="3">
      <v-card>
        <v-card-title>Horas Uteis</v-card-title>
        <v-card-text>
          <v-row>
            <v-col class="text-h4">
              {{ relatorio.diasDoMes.horasUteis }}:00
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-col>
    <v-col cols="12" sm="6" md="3">
      <v-card>
        <v-card-title>Banco de Horas</v-card-title>
        <v-card-text>
          <v-row>
            <v-col class="text-h4">
              {{ formatarTempoNegativo(relatorio.bancoHoras.horas, relatorio.bancoHoras.minutos) }}{{ formatarTempo(Math.abs(relatorio.bancoHoras.horas)) }}:{{ formatarTempo(Math.abs(relatorio.bancoHoras.minutos)) }}
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
  export default {
    props: ['relatorio'],
    methods: {
      formatarTempo (valor) {
        return (valor < 10 && valor >= 0) ? '0' + valor : valor
      },
      formatarTempoNegativo (horas, minutos) {
        return (horas < 0 || minutos < 0) ? '-' : ''
      }
    }
  }
</script>
