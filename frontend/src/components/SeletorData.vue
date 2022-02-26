<template>
  <v-dialog
      ref="dialog"
      v-model="dialog"
      :return-value.sync="data"
      persistent
      width="290px"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-btn
          width="100%"
          color="success"
          v-bind="attrs"
          v-on="on"
      >
        Data
        <v-icon right>mdi-calendar-outline</v-icon>
      </v-btn>
    </template>
    <v-date-picker
        v-model="data"
        type="month"
        scrollable
    >
      <v-spacer></v-spacer>
      <v-btn
          text
          color="primary"
          @click="dialog = false"
      >
        Cancelar
      </v-btn>
      <v-btn
          text
          color="primary"
          @click="alterarData"
      >
        Confirmar
      </v-btn>
    </v-date-picker>
  </v-dialog>
</template>

<script>
  export default {
    props: ['dataInicial'],
    data: () => ({
      dialog: false,
      data: ''
    }),
    mounted () {
      const [ano, mes] = new Date(Date.now())
        .toISOString()
        .split('-')

      this.data = `${ano}-${mes}`
    },
    methods: {
      alterarData () {
        this.$refs.dialog.save(this.data)
        this.$emit('data', this.data)
      }
    }
  }
</script>
