<!DOCTYPE html>
<html lang="en" style="height: 100%">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/icones/icones.css">
  <link rel="stylesheet" href="assets/as.css?<?= time() ?>">
  
  <script src ="assets/jq341.js"></script>
  <script src ="assets/vue232.js"></script>
  <script src ="assets/app.js?<?= time() ?>"></script>

  <title>Idioma Explorer</title>

  <style>
  
  </style>
</head>

<body>
  
  <div id="app" class="h100">

    <!-- Identificacao  -->
    <section v-show="tela == ''" class="identificacao diag-flex-col flex-jcc pad05">
      
      <div class="txt-center">
        <img src="assets/logo.png" class="logo">
      </div>
      
      <h2>Perfis de estudo disponíveis:</h2>

      <ul class="ul-limpa lista-perfis">
        <li v-for="perfil of perfis" :idperfil="perfil.id" @click="setPerfil(perfil)">
          <i v-if="!perfil.id" class="fa fa-user-secret fa-2x"></i>
          <i v-if="perfil.id" class="fa fa-user fa-2x"></i>
          <span class="item-perfil">{{ perfil.nome }}</span>
        </li>
      </ul>
      
      <h2 class="mgb10">Ou crie um novo perfil:</h2>
      <input type="text" v-model="novoPerfil" placeholder="Escolha um apelido">
      <button class="mgt10" @click="addPerfil">Iniciar estudos</button>

    </section>
    <!-- / Identificacao  -->

    <!-- Navegação livro -->
    <section v-show="tela == 'navegacao'" class="navegacao diag-flex-col">
    
      <div class="flex flex-jcsb flex-aic bgc-principal pad05">
        <div class="flex flex-aic fc-white">
          <i class="fa fa-angle-left fa-2x" @click="identificacao"></i>
          <span class="fc-white padl10">{{perfil.nome}}</span>
        </div>
        <i class="fa fa-user fa-2x" @click="identificacao"></i>
      </div>
      
      <div class="pad05">
        <h2 class="mgb10">Progresso por livro</h2>
        <div style="display: flex" >
          <div v-for="livroLista of livros" @click="setLivro(livroLista)" :class="['visu-livro', livroLista.id == livro.id ? 'livro-sel' : '']">
            {{ livroLista.id }}
          </div>
        </div>
      </div>
      
      <div v-if="spins.loadLivro" class="txt-center"><i class="fa fa-spinner fa-spin fa-2x"></i></div>

      <div v-if="livro.progresso" class="padh05">
        <div class="flex flex-aic w100 pad05 lista-licoes">
          <div class="fs20 padh05">{{livro.apr}}%</div>
          <div style="flex-grow: 1" class="resumo-licao">
            <div v-for="tipo of livro.progresso" class="row">
              <div class="col5">{{tipo.desc}}</div><div class="col5 txt-right">{{tipo.apr}}% / {{tipo.qtd}}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="padh05">
        <p v-if="!livro.progresso" class="txt-center">
          <i class="fa fa-angle-double-up fa-4x"></i> <br>
          Selecione um livro para iniciar os estudos
        </p>
        <h2 v-else class="mgb10">Aproveitamento por lição</h2>
      </div>

      <div v-if="livro.progresso"  style="flex-grow: 1; overflow: auto" class="pad05">
        <div v-for="licao of livro.licoes" class="flex flex-aic w100 pad05 lista-licoes">
          <div class="txt-center fs15 padh05">
            {{licao.licao}}
          </div>
          <div class="txt-center fs15 padh05">
            {{licao.apr}}%
          </div>
          <div style="flex-grow: 1" class="resumo-licao">
            <div v-for="tipo of licao.dados" class="row">
              <div @click="progresso(licao)" class="col5">{{tipo.desc}}</div><div class="col5 txt-right">{{tipo.apr}}% / {{tipo.qtd}}</div>
            </div>
          </div>
        </div>
      </div>

    
    </section>
    <!-- / Navegação -->

    <!-- Estudo -->
    <section v-show="tela == 'progresso'" class="estudo diag-flex-col">
    
      <div class="flex flex-jcsb flex-aic bgc-principal pad05">
        <div class="flex flex-aic fc-white">
          <i class="fa fa-angle-left fa-2x" @click="navegacao"></i>
          <span class="fc-white padl10">{{ perfil.nome }}</span>
        </div>
        <i class="fa fa-user fa-2x" @click="identificacao"></i>
      </div>

      <div class="pad05">
        <h2 class="mgb05">Explorar lição</h2>
        <select @change="selectLicao" v-model="licao.licao" class="mgb05">
          <option v-for="licao of livro.licoes" :value="licao.licao">Lição {{licao.licao}}</option>
        </select>
        
        <div class="flex flex-aic w100 pad05 lista-licoes">
          <div style="font-size: 2.5rem; margin: 0 1rem;">{{licao.apr}}%</div>
          <div style="flex-grow: 1" class="resumo-licao">
            <div v-for="tipo of licao.progresso" class="row">
              <div class="col5">{{tipo.desc}}</div><div class="col5 txt-right">{{tipo.apr}}% / {{tipo.qtd}}</div>
            </div>
          </div>
        </div>

      </div>

      <div class="flex padh05">
        <button @click="setModoProgresso('estudar')">Estudar</button>
        <button @click="setModoProgresso('teste')">Teste</button>
        <div v-if="spins.loadLicao" class="txt-center"><i class="fa fa-spinner fa-spin fa-2x"></i></div>
      </div>

      <div style="flex-grow: 1; overflow: auto" class="pad05">

        <section v-show="modoProgresso == 'estudar'">

          <div v-if="licao.progresso" style="flex-grow: 1; overflow: auto">
            
            <h2 @click="showBoxTermos('boxTV')">Verbos ({{licao.verbos.length}})</h2>
            <div class="hide" id="boxTV">
              <div v-for="termo of licao.verbos" class="flex flex-jcsb termo-dets">
                <div><span class="fgreen fs09">{{termo.termopt}} {{showParent(termo.prepopt)}} </span> <br>
                  <span class="fred fs09">{{termo.termoidi}}  {{termo.passadoidi}}  {{termo.participioidi}} {{showParent(termo.prepoidi)}} </span><br>
                  <i class="fs08">{{termo.obs}}</i>
                </div>
                <div class="txt-right fs09">{{+termo.qtdTeste}}/{{+termo.acertos}} 
                  <i :class="['fa', 'fa-circle', +(termo.sequencia || '')[0] ? 'fgreen' : 'fred']"></i>
                  <i :class="['fa', 'fa-circle', +(termo.sequencia || '')[1] ? 'fgreen' : 'fred']"></i>
                  <i :class="['fa', 'fa-circle', +(termo.sequencia || '')[2] ? 'fgreen' : 'fred']"></i>
                </div>
              </div>
            </div>

            <h2 @click="showBoxTermos('boxSubs')" class="mgt10">Substantivos ({{licao.substantivos.length}})</h2>
            <div class="hide" id="boxSubs">
              <div v-for="termo of licao.substantivos" class="flex flex-jcsb termo-dets">
                <div>
                  <span class="fgreen fs09">{{termo.termopt}}</span> | <span class="fred fs09">{{termo.termoidi}}</span> 
                  <i class="fs08 fblue">{{termo.plural ? '[Plural]' : ''}}</i>
                  <i class="fs08 fyellow">{{termo.unisex ? '[Unisex]' : ''}}</i>
                  <br>
                  <i class="fs08">{{termo.obs}}</i>
                </div>
                <div class="txt-right fs09">{{+termo.qtdTeste}}/{{+termo.acertos}} 
                  <i :class="['fa', 'fa-circle', +(termo.sequencia || '')[0] ? 'fgreen' : 'fred']"></i>
                  <i :class="['fa', 'fa-circle', +(termo.sequencia || '')[1] ? 'fgreen' : 'fred']"></i>
                  <i :class="['fa', 'fa-circle', +(termo.sequencia || '')[2] ? 'fgreen' : 'fred']"></i>
                </div>
              </div>
            </div>

            <h2 @click="showBoxTermos('boxExps')" class="mgt10">Expressões ({{licao.expressoes.length}})</h2>
            <div class="hide" id="boxExps">
              <div v-for="termo of licao.expressoes" class="flex flex-jcsb termo-dets">
                <div><span class="fgreen fs09">{{ termo.termopt }} {{ showParent(termo.prepopt) }}</span> <br>
                  <span class="fred fs09">{{ termo.termoidi }} {{ showParent(termo.prepoidi) }}</span> <br>
                  <i class="fs08 fblue">{{termo.plural ? '[Plural]' : ''}}</i>
                  <i class="fs08 fyellow">{{termo.unisex ? '[Unisex]' : ''}}</i>
                  <i class="fs08">{{termo.obs}}</i>
                </div>
                <div class="txt-right fs09">{{+termo.qtdTeste}}/{{+termo.acertos}} 
                  <i :class="['fa', 'fa-circle', +(termo.sequencia || '')[0] ? 'fgreen' : 'fred']"></i>
                  <i :class="['fa', 'fa-circle', +(termo.sequencia || '')[1] ? 'fgreen' : 'fred']"></i>
                  <i :class="['fa', 'fa-circle', +(termo.sequencia || '')[2] ? 'fgreen' : 'fred']"></i>
                </div>
              </div>
            </div>

            <h2 @click="showBoxTermos('boxFrases')" class="mgt10">Frases ({{licao.frases.length}})</h2>
            <div class="hide" id="boxFrases">
              <div v-for="termo of licao.frases" class="flex flex-jcsb termo-dets">
                <div><span class="fgreen fs09">{{termo.termopt}} </span><br> 
                  <span class="fred fs09">{{termo.termoidi}} </span><br>
                  <i class="fs08">{{termo.obs}}</i>
                </div>
                <div class="txt-right fs09">{{ +termo.qtdTeste }}/{{ +termo.acertos }} 
                  <i :class="['fa', 'fa-circle', +(termo.sequencia || '')[0] ? 'fgreen' : 'fred']"></i>
                  <i :class="['fa', 'fa-circle', +(termo.sequencia || '')[1] ? 'fgreen' : 'fred']"></i>
                  <i :class="['fa', 'fa-circle', +(termo.sequencia || '')[2] ? 'fgreen' : 'fred']"></i>
                </div>
              </div>
            </div>

          </div>

        </section>

        <section v-show="modoProgresso == 'teste'" class="prova-mental diag-flex-col">
    
            <select @change="configTeste" class="mgb10" v-model="teste.tipoTermo">
              <option value="T">Todos</option>
              <option value="verbos">Verbos</option>
              <option value="expressoes">Expressoes</option>
              <option value="substantivos">Substantivos</option>
              <option value="frases">Frases</option>
            </select>
    
            <p class="mgb10">
              <input @click="configTeste" type="radio" value="T" v-model="teste.tipoProgresso"> Todos
              <input @click="configTeste" type="radio" value="E" v-model="teste.tipoProgresso"> Somente com erros
            </p>
            
            <div class="flex flex-jcsb flex-aic w100 mgb10  pad05 round borda">
              <i @click="backTermo" class="fa fa-undo fa-2x"></i>
              <div class="txt-center fs15 bold">
                <div>Termos selecionados</div>
                <span v-if="teste.on" class="fs15 bold">{{teste.ind + 1}} / </span>{{teste.termos.length}}
              </div>
              <i v-if="!teste.on" @click="startTeste" class="fa fa-play fa-2x"></i>
              <i v-if="teste.on" @click="restartTeste" class="fa fa-refresh fa-2x"></i>
            </div>
            
            <section v-if="teste.on">

              <p class="pad10 fs12 round mgb10 txt-center" style="background-color: antiquewhite;">
                <span class="">{{teste.termoAtual.termopt}} {{showParent(teste.termoAtual.prepopt)}}</span> <br>
                <i class="fs08">{{teste.termoAtual.obs}}</i>
              </p>
              
              <div @click="toggleResposta" class="mgb10 fs12 pad10 round txt-center" style="background-color: lightblue">
                <i v-if="!teste.exibeResposta" class="fa fa-eye fa-lg"></i>
                <span v-if="teste.exibeResposta" class="fs12">
                  {{teste.termoAtual.termoidi}} {{teste.termoAtual.passadoidi}} {{teste.termoAtual.participioidi}} {{showParent(teste.termoAtual.prepoidi)}}
                </span>
              </div>
              
              <div class="flex flex-jcsb w100 mgb10 round">
                <div class="txt-center col4 round borda">
                  <div class="fs15 bold pad05">{{teste.acertos}}</div>
                  <button @click="nextTermo(true)" class="bgc-green"><i class="fa fa-check fa-2x"></i></button>
                </div>
                <div class="txt-center col4 round borda">
                  <div class="fs15 bold pad05">{{teste.erros}}</div>
                  <button @click="nextTermo(false)" class="bgc-red"><i class="fa fa-close fa-2x"></i></button>
                </div>
              </div>

              <button v-if="teste.concluido" @click="saveTeste" class="bgc-blue">
                <i class="fa fa-file"></i> Salvar dados <i v-if="spins.saveTeste" class="fa fa-spin fa-spinner"></i>
              </button>

            
            </section>
    
        </section>
      
      </div>

    </section>
    <!-- / Estudo -->

  </div>

</body>
</html>