<template>
  <div>
    <v-app-bar
        v-if="!$vuetify.breakpoint.mdAndUp"
    >
      <v-app-bar-nav-icon @click="exibir = !exibir"></v-app-bar-nav-icon>
    </v-app-bar>
    <v-navigation-drawer
        v-model="exibir"
        :permanent="$vuetify.breakpoint.mdAndUp"
        :expand-on-hover="$vuetify.breakpoint.mdAndUp"
        :absolute="!$vuetify.breakpoint.mdAndUp"
        :temporary="!$vuetify.breakpoint.mdAndUp"
        app
    >
      <v-list>
        <v-list-item class="px-2">
          <v-list-item-avatar>
            <v-img :src="fotoPerfil"></v-img>
          </v-list-item-avatar>
        </v-list-item>
        <v-list-item link>
          <v-list-item-content>
            <v-list-item-title class="text-h6">{{ nome }}</v-list-item-title>
            <v-list-item-subtitle>{{ email }}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </v-list>
      <v-divider></v-divider>
      <v-list nav dense>
        <router-link :to="{ name: 'Ponto' }" style="text-decoration: none;">
          <v-list-item link>
            <v-list-item-icon>
              <v-icon>mdi-clock-outline</v-icon>
            </v-list-item-icon>
            <v-list-item-title>Ponto</v-list-item-title>
          </v-list-item>
        </router-link>
        <!--        <v-list-item link>-->
        <!--          <v-list-item-icon>-->
        <!--            <v-icon>mdi-cog-outline</v-icon>-->
        <!--          </v-list-item-icon>-->
        <!--          <v-list-item-title>Configurações</v-list-item-title>-->
        <!--        </v-list-item>-->
      </v-list>
      <span v-if="getUsuario.admin">
        <v-divider></v-divider>
        <v-list nav dense>
          <router-link :to="{ name: 'Usuarios' }" style="text-decoration: none;">
            <v-list-item link>
              <v-list-item-icon>
                <v-icon>mdi-account-multiple-outline</v-icon>
              </v-list-item-icon>
              <v-list-item-title>Usuarios</v-list-item-title>
            </v-list-item>
          </router-link>
          <router-link :to="{ name: 'Feriados' }" style="text-decoration: none;">
            <v-list-item link>
              <v-list-item-icon>
                <v-icon>mdi-calendar</v-icon>
              </v-list-item-icon>
              <v-list-item-title>Feriados</v-list-item-title>
            </v-list-item>
          </router-link>
        </v-list>
      </span>
    </v-navigation-drawer>
  </div>
</template>

<script>
  export default {
    data: () => ({
      exibir: false,
      nome: '',
      email: '',
      fotoPerfil: ''
    }),
    computed: {
      getUsuario () {
        return JSON.parse(localStorage.getItem('usuario'))
      }
    },
    watch: {
      '$vuetify.breakpoint.mdAndUp': function () {
        this.exibir = false
      }
    },
    mounted () {
      const usuario = JSON.parse(localStorage.getItem('usuario'))

      if (usuario) {
        this.nome = usuario.nome
        this.email = usuario.email
        this.fotoPerfil = usuario.foto_perfil
      }
    }
  }
</script>
