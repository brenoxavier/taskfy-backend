<template>
  <v-card elevation="0">
    <v-card-title>{{ titulo }}</v-card-title>
    <v-card-actions>
      <v-expansion-panels>
        <v-expansion-panel v-for="(usuario, indice) in usuarios" :key="indice">
          <v-expansion-panel-header>
            <div>
              <v-avatar size="38">
                <v-img :src="usuario.foto_perfil" />
              </v-avatar>
              <span class="ml-5 text-h6">{{ usuario.nome }}</span>
            </div>
          </v-expansion-panel-header>
          <v-expansion-panel-content>
            <v-container>
              <v-row class="mb-3">
                <div>
                  <div>
                    <b>E-mail:</b> {{ usuario.email }}
                  </div>
                  <div>
                    <b>Carga Horaria:</b> {{ usuario.carga_horaria }}
                  </div>
                  <div>
                    <b>Trabalha Sabado:</b> {{ usuario.sabado ? 'Sim' : 'Não' }}
                  </div>
                  <div>
                    <b>Data do Cadastro:</b> {{ formatarDataTempo(usuario.created_at) }}
                  </div>
                  <div>
                    <b>Clockify ID:</b> {{ usuario.id_clockify }}
                  </div>
                </div>
              </v-row>
              <v-row>
                <router-link :to="{ name: 'Ponto', params: { idUsuario: usuario.id } }" style="text-decoration: none;">
                  <v-btn color="success" class="mr-3">
                    Banco de Horas
                    <v-icon right>mdi-clock-outline</v-icon>
                  </v-btn>
                </router-link>
                <CadastrarUsuario :id-usuario="usuario.id" />
              </v-row>
            </v-container>
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-expansion-panels>
    </v-card-actions>
  </v-card>
</template>

<script>
  import { format } from 'date-fns'
  import CadastrarUsuario from '../components/CadastrarUsuario'

  export default {
    components: { CadastrarUsuario },
    props: ['usuarios', 'titulo'],
    methods: {
      formatarDataTempo (data) {
        return format(new Date(data), 'dd/MM/yyyy - hh:mm')
      }
    }
  }
</script>
