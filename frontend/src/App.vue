<template>
  <v-app>
    <navbar v-if="$route.name !== 'Login'" />
    <v-main>
      <router-view />
      <v-overlay :value="$store.state.carregando" style="z-index: 999">
        <v-progress-circular indeterminate size="64" />
      </v-overlay>
    </v-main>
  </v-app>
</template>

<script>
  import Navbar from './components/Navbar.vue'

  export default {
    name: 'App',
    components: {
      navbar: Navbar
    }
  }
</script>
