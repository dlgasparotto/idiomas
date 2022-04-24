$(start)



log = console.log

function start(){

  new Vue({
    el: "#app",
    data: {
      spins: { loadLivro: false, loadLicao: false, saveTeste:false },
      tela: '',
      novoPerfil: '',
      perfil: {},
      perfis: [],
      livros: [],
      livro: { },
      licao: { },
      modoProgresso: 'estudar',
      teste: {
        termos: [], 
        tipoTermo: 'T' ,
        tipoProgresso: 'T', 
        ind: 0,
        termoAtual: {},
        acertos: 0,
        erros: 0,
        on: false,
        concluido: false,
        exibeResposta: false,
        respostas: [],
      }
    },
    
    mounted(){
      this.loadPerfis()
      this.loadLivros()
    },

    methods: {

      showBoxTermos(idBox){
        $('#' + idBox).toggle('fast')
      },

      startTeste(){
        log('start')
        this.teste.on = true
        this.setTermoAtual()
      },
      
      backTermo(){
        log(this.teste)
        if (!this.teste.on || !this.ind) return

        this.teste.exibeResposta = false
        this.teste.concluido = false

        let ultimaResposta = this.teste.respostas.pop() 

        log('UR', ultimaResposta)
        if (ultimaResposta.result) {
          this.teste.acertos--
        } else {
          this.teste.erros--
        }

        this.teste.ind--

        this.setTermoAtual()

      },

      nextTermo(result){
        if (this.teste.concluido){
          alert('Teste já concluido')
          return
        }
        if (!this.teste.exibeResposta){
          alert('Verifique a resposta')
          this.teste.exibeResposta = true
          return
        }
        log(this.teste)
        if (result) {
          this.teste.acertos++
        } else {
          this.teste.erros++
        }
        this.teste.exibeResposta = false

        //let { idTermo: id, idProgresso } = this.teste.termoAtual
        let resposta = { 
          idTermo: this.teste.termoAtual.id, 
          idProgresso: this.teste.termoAtual.idProgresso, 
          result: +result 
        }
        this.teste.respostas.push(resposta)
        log('R', this.teste.respostas)

        if (this.teste.ind + 1 >= this.teste.termos.length){
          this.teste.concluido = true
        } else {
          this.teste.ind++
          this.setTermoAtual()
        }
      },

      setTermoAtual(){
        this.teste.termoAtual = this.teste.termos[this.teste.ind]
      },

      saveTeste(){
        log('save')

        let teste = {
          idPerfil: this.perfil.id,
          termos: this.teste.respostas
        }
        log('t', teste)
        this.spins.saveTeste = true
        ajaxPostBody('api/provas', teste, msg => {
          log(msg)
          this.spins.saveTeste = false
          this.loadLicao()
          this.loadLivro()
        })

      },

      restartTeste(){
        log('restart')
        this.teste.acertos = 0
        this.teste.erros = 0
        this.teste.ind = 0
        this.teste.exibeResposta = false
        this.teste.concluido = false
        this.teste.respostas = []
        this.setTermoAtual()
      },

      toggleResposta(){
        this.teste.exibeResposta = !this.teste.exibeResposta
      },

      configTeste(){

        log('licao', this.licao)
        log('teste', this.teste)

        let termos = []

        if (this.teste.tipoTermo == 'T'){
          log('tdos')
          termos = [].concat(this.licao.verbos, this.licao.substantivos, this.licao.expressoes, this.licao.frases)
        } else {
          log('tipo')
          termos = [].concat(this.licao[this.teste.tipoTermo])
        }
        
        log('meio', termos)
        
        if (this.teste.tipoProgresso == 'T'){
          termosFim = [].concat(termos)
        } else {
          termosFim = termos.filter(termo => (termo.sequencia || '').slice(0, 3) != '111' )
        }
        
        log('fim', termosFim)
        this.teste.termos = termosFim

        this.teste.on = false
        this.restartTeste()


      },

      setModoProgresso(modo){
        this.modoProgresso = modo
        if (modo == 'teste'){
          this.configTeste()
        }
      },


      // Licao
      loadLicao(){
        this.spins.loadLicao = true
        $.get(`api/licaos/${this.livro.id}/${this.licao.licao}/${this.perfil.id}`, msg => {
          log('lendo licao', msg)
          this.spins.loadLicao = false
          this.licao.apr = msg.apr
          this.licao.progresso = msg.progresso
          this.licao.verbos = msg.termos.filter(termo => termo.tipo == 'V')
          this.licao.substantivos = msg.termos.filter(termo => termo.tipo == 'S')
          this.licao.expressoes = msg.termos.filter(termo => termo.tipo == 'E')
          this.licao.frases = msg.termos.filter(termo => termo.tipo == 'F')

          if (this.modoProgresso == 'teste'){
            this.configTeste()
          }
          
        })
      },

      selectLicao(e){
        this.licao.licao = e.currentTarget.value
        this.loadLicao()
        

      },

      // Livros
      loadLivros(){
        $.get('api/livros', msg => {
          this.livros = msg
        })
      },
      loadLivro(){
        this.spins.loadLivro = true
        $.get(`api/livros/${this.livro.id}/${this.perfil.id}`, msg => {
          log('atualiza livro', msg)
          this.livro = msg
          this.spins.loadLivro = false
        })
      },

      setLivro(livro){
        log(livro)
        this.livro.id = livro.id
        this.loadLivro()
      },

      // Perfils
      addPerfil(e){
        if (!this.novoPerfil){
          alert('Informe o novo perfil')
          return
        }
        $.post('api/perfils', { nome: this.novoPerfil }, msg => {
          this.loadPerfis()
          this.novoPerfil = ''
        })
      },
      loadPerfis(){
        $.get('api/perfils', msg => {
          msg.unshift({ id: 0, nome: 'Anônimo'} )
          this.perfis = msg
        })
      },
      setPerfil(perfil){
        this.perfil = perfil
        this.navegacao()
      },


      // Telas
      identificacao(){
        this.tela = ''
      },
      navegacao(){
        log('volta', this.livro)
        this.tela = 'navegacao'
      },
      progresso(licao){
        log(licao)
        this.tela = 'progresso'
        this.licao.licao = licao.licao
        this.loadLicao()

      },


      showParent(txt){
        return txt ? `(${txt})` : ''
      }
      
    }

  })

}


function ajaxPostBody(url, payload, callback){
  $.ajax({ 
    url, 
    type: 'POST',
    data: JSON.stringify(payload), 
    processData: false,
    success: function(msg){
      if (callback) callback(msg)
    }
  })
}