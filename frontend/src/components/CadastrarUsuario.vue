<template>
  <span>
    <v-btn color="primary" @click="() => dialog = true">
      {{ idUsuario ? 'Editar' : 'Novo Usuario' }}
      <v-icon v-if="idUsuario" right>mdi-pencil-outline</v-icon>
      <v-icon v-else right>mdi-account-plus-outline</v-icon>
    </v-btn>

    <v-dialog v-model="dialog" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <span class="text-h5">{{ idUsuario ? 'Editar Usuario' : 'Novo Usuario' }}</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-form v-model="validacoes.valido">
              <v-row>
                <v-col cols="12" v-if="!idUsuario">
                  <v-select
                      v-model="usuarioSelecionado"
                      :items="usuariosDisponiveis"
                      item-text="nome"
                      item-value="id_clockify"
                      label="Usuario Clockify"
                      :disabled="$store.state.carregando"
                  ></v-select>
                </v-col>
                <v-col cols="6" v-if="usuarioSelecionado">
                  <v-text-field
                      label="Nome"
                      v-model="usuario.nome"
                      required
                      :rules="validacoes.nome"
                  />
                </v-col>
                <v-col cols="6" v-if="usuarioSelecionado">
                  <v-text-field
                      label="E-mail"
                      v-model="usuario.email"
                      required
                      :rules="validacoes.email"
                  />
                </v-col>
                <v-col cols="12" v-if="usuarioSelecionado">
                  <v-text-field
                      v-model="usuario.senha"
                      label="Senha"
                      required
                      :rules="!idUsuario ? validacoes.senha : validacoes.senhaEditar"
                      :type="exibirSenha ? 'text' : 'password'"
                      :append-icon="exibirSenha ? 'mdi-eye' : 'mdi-eye-off'"
                      @click:append="exibirSenha = !exibirSenha"
                  />
                </v-col>
                <v-col cols="12" v-if="usuarioSelecionado">
                  <v-text-field
                      label="Foto de Perfil"
                      v-model="usuario.fotoPerfil"
                      required
                      :rules="validacoes.fotoPerfil"
                  />
                </v-col>
                <v-col v-if="usuarioSelecionado">
                  <v-text-field
                      label="Carga Horaria"
                      v-model="usuario.cargaHoraria"
                      required
                      :rules="validacoes.cargaHoraria"
                      maxlength="1"
                  />
                </v-col>
                <v-col v-if="usuarioSelecionado">
                  <v-text-field
                      label="Clockfy ID"
                      v-model="usuario.idClockify"
                      required
                      :rules="validacoes.idClockify"
                      :disabled="!usuarioSelecionado"
                  />
                </v-col>
                <v-col cols="12" v-if="usuarioSelecionado">
                  <v-row>
                    <v-col>
                      <v-switch
                          v-model="usuario.ativo"
                          label="Ativo"
                          :disabled="!usuarioSelecionado"
                      ></v-switch>
                    </v-col>
                    <v-col>
                      <v-switch
                          v-model="usuario.sabado"
                          label="Sabado"
                          :disabled="!usuarioSelecionado"
                      ></v-switch>
                    </v-col>
                    <v-col>
                      <v-switch
                          v-model="usuario.admin"
                          label="Admnistrador"
                          :disabled="!usuarioSelecionado"
                      ></v-switch>
                    </v-col>
                  </v-row>
                </v-col>
              </v-row>
            </v-form>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="dialog = false">Cancelar</v-btn>
          <v-btn color="blue darken-1" text @click="cadastrarUsuario" :disabled="!validacoes.valido || !usuarioSelecionado">{{ idUsuario ? 'Editar' : 'Cadastrar' }}</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </span>
</template>

<script>
  export default {
    props: ['idUsuario'],
    data: () => ({
      dialog: false,
      usuariosDisponiveis: [],
      usuarioSelecionado: '',
      exibirSenha: false,
      usuario: {
        nome: '',
        email: '',
        senha: '',
        fotoPerfil: '',
        cargaHoraria: '',
        sabado: false,
        ativo: false,
        admin: false,
        idClockify: ''
      },
      validacoes: {
        valido: false,
        nome: [
          v => !!v || 'O nome é obrigatório.',
          v => v.length >= 5 || 'O nome não pode possuir menos de 5 caracteres.',
          v => v.length <= 255 || 'O nome não pode possuir mais de 255 caracteres.'
        ],
        email: [
          v => !!v || 'O e-mail é obrigatório.',
          v => /.+@.+/.test(v) || 'O e-mail precisa ser válido.',
          v => v.length >= 5 || 'O e-mail não pode possuir menos de 5 caracteres.',
          v => v.length <= 255 || 'O e-mail não pode possuir mais de 255 caracteres.'
        ],
        senha: [
          v => !!v || 'A senha é obrigatório.',
          v => v.length >= 8 || 'A senha não pode possuir menos de 8 caracteres.',
          v => v.length <= 255 || 'A senha não pode possuir mais de 255 caracteres.'
        ],
        senhaEditar: [
          v => (!v || v.length >= 8) || 'A senha não pode possuir menos de 8 caracteres.',
          v => v.length <= 255 || 'A senha não pode possuir mais de 255 caracteres.'
        ],
        idClockify: [
          v => !!v || 'O ID Clockify é obrigatório.',
          v => v.length >= 20 || 'O ID Clockify não pode possuir menos de 20 caracteres.',
          v => v.length <= 28 || 'O ID Clockify não pode possuir mais de 28 caracteres.'
        ],
        fotoPerfil: [
          v => !!v || 'A foto de perfil é obrigatório.',
          v => v.length >= 5 || 'O foto de perfil não pode possuir menos de 5 caracteres.',
          v => v.length <= 255 || 'O foto de perfil não pode possuir mais de 255 caracteres.'
        ],
        cargaHoraria: [
          v => !!v || 'A carga horaria é obrigatório.',
          v => v >= 4 || 'A carga horaria não pode ser menor que 4',
          v => v <= 8 || 'A carga horaria não pode ser maior que 8'
        ]
      }
    }),
    watch: {
      async dialog (valor) {
        if (valor) {
          this.usuarioSelecionado = ''
          this.usuario.nome = ''
          this.usuario.email = ''
          this.usuario.senha = ''
          this.usuario.fotoPerfil = ''
          this.usuario.idClockify = ''
          this.usuario.cargaHoraria = ''
          this.usuario.ativo = false
          this.usuario.sabado = false
          this.usuario.admin = false

          this.exibirSenha = false

          try {
            if (this.idUsuario) {
              const { data: usuario } = await this.$http.get(`/usuarios/${this.idUsuario}`)

              this.usuarioSelecionado = usuario.nome
              this.usuario.nome = usuario.nome
              this.usuario.email = usuario.email
              this.usuario.fotoPerfil = usuario.foto_perfil
              this.usuario.idClockify = usuario.id_clockify
              this.usuario.cargaHoraria = usuario.carga_horaria
              this.usuario.ativo = usuario.ativo
              this.usuario.sabado = usuario.sabado
              this.usuario.admin = usuario.admin
            } else {
              const { data: usuariosDisponiveis } = await this.$http.get('/clockify/usuarios')

              if (usuariosDisponiveis.length === 0) {
                this.$toast.warning('Não há usuarios disponiveis no Clockify')
                this.dialog = false
                return
              }

              this.usuariosDisponiveis = usuariosDisponiveis
            }
          } catch (erro) {
            this.$toast.error('Não foi possivel buscar informações do servidor.')
          }
        }
      },
      usuarioSelecionado (valor) {
        this.usuariosDisponiveis.map(usuario => {
          if (usuario.id_clockify === valor) {
            this.usuario.nome = usuario.nome
            this.usuario.email = usuario.email
            this.usuario.senha = '12345678'
            this.usuario.fotoPerfil = usuario.foto_perfil
            this.usuario.cargaHoraria = '8'
            this.usuario.idClockify = usuario.id_clockify

            this.exibirSenha = true
          }
        })
      }
    },
    methods: {
      async cadastrarUsuario () {
        this.dialog = false

        try {
          const usuario = {
            nome: this.usuario.nome,
            email: this.usuario.email,
            senha: this.usuario.senha,
            foto_perfil: this.usuario.fotoPerfil,
            carga_horaria: this.usuario.cargaHoraria,
            sabado: this.usuario.sabado,
            ativo: this.usuario.ativo,
            admin: this.usuario.admin,
            id_clockify: this.usuario.idClockify
          }

          if (this.idUsuario) {
            const { data } = await this.$http.put(`/usuarios/${this.idUsuario}`, usuario)
            this.$toast.success(data.message)
          } else {
            const { data } = await this.$http.post('/usuarios', usuario)
            this.$toast.success(data.message)
          }

          this.$store.commit('setRecarregar')
        } catch (erro) {
          if (erro.response.status === 422) {
            const erros = erro.response.data.errors
            const chaves = Object.keys(erros)
            this.$toast.error(erros[chaves[0]][0])
            return
          }

          if (this.idUsuario) {
            this.$toast.error('Não foi possivel editar o usuario.')
            return
          }

          this.$toast.error('Não foi possivel cadastrar o usuario.')
        }
      }
    }
  }
</script>
