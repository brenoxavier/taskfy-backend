<template>
  <span>{{ _horas }}:{{ _minutos }}:{{ _segundos }}</span>
</template>

<script>
  import { differenceInSeconds } from 'date-fns'

  export default {
    props: ['ativo', 'tempoInicial'],
    data: () => ({
      horas: 0,
      minutos: 0,
      segundos: 0,
      contador: undefined
    }),
    computed: {
      _horas () {
        return this.horas < 10 ? '0' + this.horas : this.horas
      },
      _minutos () {
        return this.minutos < 10 ? '0' + this.minutos : this.minutos
      },
      _segundos () {
        return this.segundos < 10 ? '0' + this.segundos : this.segundos
      }
    },
    watch: {
      ativo (novoValor) {
        if (novoValor) {
          this.iniciar()
        } else {
          this.parar()
        }
      }
    },
    mounted () {
      if (this.ativo) {
        this.iniciar()
      }
    },
    methods: {
      iniciar () {
        let segundosIniciais = differenceInSeconds(Date.now(), new Date(this.tempoInicial))

        this.horas = Math.floor(segundosIniciais / 3600)
        segundosIniciais %= 3600
        this.minutos = Math.floor(segundosIniciais / 60)
        this.segundos = segundosIniciais % 60

        this.contador = setInterval(() => {
          this.segundos += 1

          if (this.segundos >= 60) {
            this.segundos = 0
            this.minutos += 1
          }

          if (this.minutos >= 60) {
            this.minutos = 0
            this.horas += 1
          }
        }, 1000)
      },
      parar () {
        clearInterval(this.contador)
        this.horas = 0
        this.minutos = 0
        this.segundos = 0
      }
    }
  }
</script>
