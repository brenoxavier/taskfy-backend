<template>
  <v-main>
    <v-container>
      <v-card elevation="0">
        <v-card-title class="mt-5">
          <h1>Usuarios</h1>
          <CadastrarUsuario class="ml-auto" />
        </v-card-title>
      </v-card>
      <ListaUsuarios v-if="admins.length > 0" :usuarios="admins" titulo="Admnistradores" />
      <ListaUsuarios v-if="funcionarios.length > 0" :usuarios="funcionarios" titulo="Funcionarios" />
      <ListaUsuarios v-if="inativos.length > 0" :usuarios="inativos" titulo="Inativos" />
    </v-container>
    <div class="text-center pb-5" v-if="totalPaginas > 1">
      <v-pagination
          v-model="pagina"
          :length="totalPaginas"
      ></v-pagination>
    </div>
  </v-main>
</template>

<script>
  import CadastrarUsuario from '../components/CadastrarUsuario.vue'
  import ListaUsuarios from '../components/ListaUsuarios.vue'

  export default {
    components: { CadastrarUsuario, ListaUsuarios },
    data: () => ({
      pagina: 1,
      totalPaginas: 1,
      admins: [],
      funcionarios: [],
      inativos: []
    }),
    watch: {
      pagina () {
        this.buscarUsuarios()
      },
      '$store.state.recarregar': function () {
        this.buscarUsuarios()
      }
    },
    mounted () {
      this.buscarUsuarios()
    },
    methods: {
      async buscarUsuarios () {
        try {
          const { data: resposta } = await this.$http.get(`/usuarios?page=${this.pagina}`)

          this.totalPaginas = resposta.last_page
          this.admins = []
          this.funcionarios = []
          this.inativos = []

          resposta.data.map(usuario => {
            if (usuario.admin && usuario.ativo) {
              this.admins.push(usuario)
              return
            }

            if (usuario.ativo) {
              this.funcionarios.push(usuario)
              return
            }

            this.inativos.push(usuario)
          })
        } catch (erro) {
          this.$toast.error('Não foi possivel buscar informações do servidor.')
          console.error(erro)
        }
      }
    }
  }
</script>
