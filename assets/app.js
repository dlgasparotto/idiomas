$(start)

log = console.log

function start(){

  new Vue({
    el: "#app",
    data: {
      novoPerfil: '',
      user: { },
      perfis: [
        { id: 0, nome: 'Anonimo' },
        { id: 1, nome: 'Gasparotto' },
      ],
    },
    methods: {
      addPerfil(e){
        log(e.currentTarget)
      }
    }

  })

}