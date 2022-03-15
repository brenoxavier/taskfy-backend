<template>
  <v-container class="fill-height">
    <v-row justify="center">
      <v-col cols="11" sm="6" md="5" lg="4" xl="3">
        <v-card elevation="2" class="card-login">
          <!-- <v-img src="@/assets/newm.png" contain></v-img> -->
          <v-text class="login-title">Task<span>fy</span></v-text>
          <v-form v-model="validacoes.valido" class="mt-5">
            <v-text-field
                v-model="formularios.email"
                :rules="validacoes.email"
                label="E-mail"
                required
            ></v-text-field>
            <v-text-field
                v-model="formularios.senha"
                :rules="validacoes.senha"
                type="password"
                label="Senha"
                required
            ></v-text-field>
            <v-btn @click="login"
                   :disabled="!validacoes.valido"
                   block
                   large
                   color="primary"
                   class="mt-3"
            >
              Entrar
            </v-btn>
          </v-form>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
  export default {
    data: () => ({
      formularios: {
        email: '',
        senha: ''
      },
      validacoes: {
        valido: false,
        email: [
          v => !!v || 'O e-mail é obrigatório.',
          v => /.+@.+/.test(v) || 'O e-mail precisa ser válido.',
          v => v.length >= 5 || 'O e-mail não pode possuir menos de 5 caracteres.',
          v => v.length <= 255 || 'O e-mail não pode possuir mais de 255 caracteres.'
        ],
        senha: [
          v => !!v || 'A senha é obrigatória.',
          v => v.length >= 8 || 'A senha não pode possuir menos de 8 caracteres.',
          v => v.length <= 255 || 'A senha não pode possuir mais de 255 caracteres.'
        ]
      }
    }),
    methods: {
      async login () {
        try {
          const { data } = await this.$http.post('/autenticar', {
            email: this.formularios.email,
            senha: this.formularios.senha
          })

          localStorage.setItem('token', data.token)
          localStorage.setItem('usuario', JSON.stringify(data.usuario))
          this.$router.replace({ name: 'Ponto' })
        } catch (erro) {
          switch (erro.response.status) {
            case 404:
              this.$toast.error('E-mail ou senha incorretos.')
              break
            default:
              this.$toast.error('Não foi possivel realizar a autenticação.')
              break
          }
        }
      }
    }
  }
</script>

<style scoped>
.card-login {
  padding: 70px 30px;
}

.card-login .login-title {
  font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
  font-size: 40px;
}
.card-login .login-title span {
  color: rgb(255, 255, 255);
  background-color: rgb(12, 105, 226);
  padding: 0 12px;
  border-radius: 10px;
}
</style>
