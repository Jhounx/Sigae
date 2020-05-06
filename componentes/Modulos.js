var linhas = 0,
    conjuntos = 0,
    nodes = 0,
    arrayNodes = [],
    arrayLinhas = [],
    moduloInit

function sideMenus() {
    //Início
    linha1 = new Linha("Início", "home", null, "--inicio");

    //Calendário
    node1 = new Node("Calendários", "perm_contact_calendar")
    conjunto1 = new Conjunto()
    node1.add(conjunto1)
    linha2 = new Linha("Calendário de atendimentos", null, "#conjunto1", "--calendario");
    linha3 = new Linha("Meus atendimentos agendados", null, "#conjunto1", "--atendimentosAgendados");
    linha4 = new Linha("Meus atendimentos inscritos", null, "#conjunto1", "--atendimentosInscritos");

    //Alunos e professores
    node2 = new Node("Alunos e professores", "school")
    conjunto2 = new Conjunto()
    node2.add(conjunto2)
    linha5 = new Linha("Minha Turma", null, "#conjunto2", "--minhaTurma");
    linha6 = new Linha("Alunos", null, "#conjunto2", "--alunos");
    linha7 = new Linha("Docentes e monitores", null, "#conjunto2", "--docentes");

    //Meu usuário
    node3 = new Node("Meu usuário", "people")
    conjunto3 = new Conjunto()
    node3.add(conjunto3)
    linha8 = new Linha("Alterar meus dados", null, "#conjunto3", "--mudarDados");
    linha9 = new Linha("Alterar minha senha", null, "#conjunto3", "alterarSenhaShow()");

    //Configurações
    node4 = new Node("Configurações", "settings")
    conjunto4 = new Conjunto()
    node4.add(conjunto4)
    linha10 = new Linha("Alterar notificações", null, "#conjunto4", "alterarNotificacoesShow()");
    linha11 = new Linha("Abrir console", null, "#conjunto4", "abrirConsole()");
    linha12 = new Linha("Sobre o SiGAÊ", null, "#conjunto4", "sobreSigae()");

    //Finalizar sessão
    linha13 = new Linha("Finalizar sessão", "exit_to_app", null, "finalizarSessao()");

    modulos()
}

function modulos() {
    /* Modulo das linhas*/
    inicio = new Modulo("inicio", "../modulos/inicio", "Início", "home", true, true, linha1);
    calendario = new Modulo("calendario", "../modulos/calendario", "Calendário de atendimentos", "perm_contact_calendar", true, true, linha2);
    atendimentosAgendados = new Modulo("atendimentosAgendados", "../modulos/atendimentosAgendados", "Meus atendimentos agendados", "perm_contact_calendar", true, true, linha3);
    atendimentosAgendados.wait()
    atendimentosInscritos = new Modulo("atendimentosInscritos", "../modulos/atendimentosInscritos", "Meus atendimentos inscritos", "perm_contact_calendar", true, true, linha4);
    atendimentosInscritos.wait()
    minhaTurma = new Modulo("minhaTurma", "../modulos/pessoal/minhaTurma", "Minha Turma", "school", true, true, linha5);
    alunos = new Modulo("alunos", "../modulos/pessoal/alunos", "Alunos", "perm_contact_calendar", true, true, linha6);
    docentes = new Modulo("docentes", "../modulos/pessoal/docentes", "Docentes e monitores", "school", true, true, linha7);
    mudarDados = new Modulo("mudarDados", "../modulos/mudarDados", "Alterar dados", "build", true, true, linha8);
    /* Módulos de invocação*/
    agendarAtendimento = new Modulo("agendarAtendimento", "../modulos/agendarAtendimento", "Agendar atendimento", "perm_contact_calendar", true, true, null);
    agendarAtendimento.wait()

    atendimento = new Modulo("atendimento", "../modulos/atendimento", " ", "perm_contact_calendar", true, true, null);
    atendimento.wait()
        /* Módulos especiais */
    erro404 = new Modulo("erro404", "../modulos/erro404", "Módulo não encontrado", "error", true, false, null);

    setModuloParam()
}

function setModuloParam() {
    if (paramExist("modulo")) {
        moduloParam = getParam("modulo")
        moduloInit = moduloParam
        var achou = false;
        /* PROCURAR MÓDULO */
        for (var i = 0; i < arrayModulos.length; i++) {
            var moduloDoArray = arrayModulos[i]
            if (moduloDoArray.id == moduloParam) {
                var achou = true;
                if (moduloDoArray.linha != null) {
                    moduloDoArray.linha.rodar()
                } else {
                    eval("invoker_" + moduloDoArray.id + "()")
                }
            }
        }
        if (achou == false) {
            erro404.invoker()
        }
    } else {
        linha1.rodar()
    }
}

/* funções da sidenav */
function closeAll(except) {
    for (var i = 0; i < arrayNodes.length; i++) {
        node = arrayNodes[i]
        if (node != except) {
            arrayNodes[i].close()
        }
    }
}

function closeNode(id) {
    for (var i = 0; i < arrayNodes.length; i++) {
        obj = arrayNodes[i]
        if (obj.id == id) {
            obj.close()
        }
    }
}

function selectLinha(linha) {
    for (var i = 0; i < arrayLinhas.length; i++) {
        obj = arrayLinhas[i]
        if (obj == linha) {
            obj.select()
            if (obj.conjunto != null) {
                node = arrayNodes[obj.conjuntoNumero - 1]
                node.open()
            }
        }
    }
}

function selectAtual() {
    removeAllSelection()
    selectLinha(moduloAtual.linha)
}

function getLinhaByNome(nome) {
    for (var i = 0; i < arrayLinhas.length; i++) {
        c = "--" + nome;
        if (arrayLinhas[i].action == c) {
            return arrayLinhas[i];
        }
    }
}

function nodeDaLinha(linha) {
    for (var i = 0; i < arrayLinhas.length; i++) {
        obj = arrayLinhas[i]
        if (obj == linha && obj.conjunto != null) {
            node = arrayNodes[obj.conjuntoNumero - 1]
            return node;
        }
    }
}

function removeAllSelection() {
    for (var i = 0; i < arrayLinhas.length; i++) {
        obj = arrayLinhas[i]
        obj.removeSelect()
    }
}

function irInicio() {
    linha1.rodar()
    removeAllParans()
}

class Linha {

    constructor(titulo, icone, conjunto, action) {
        linhas++;
        this.id = linhas;
        this.titulo = titulo
        this.icone = icone
        this.conjunto = conjunto
        this.action = action
        arrayLinhas.push(this)
        if (conjunto != null) {
            this.conjuntoNumero = conjunto.replace(/[^0-9]/g, '');
        }
        this.render()
    }

    getActionType() {
        if (this.action.startsWith("--")) {
            return "MODULO"
        }
        if (this.action.endsWith("()")) {
            return "FUNCTION"
        }
    }

    getModuloHREF() {
        if (this.getActionType() == "MODULO") {
            var modulo = this.action.substring(2)
            return "href=\"?modulo=" + modulo + "\""
        } else {
            return ""
        }
    }

    getFunction() {
        if (this.getActionType() == "FUNCTION") {
            var fun = this.action
            fun = fun.slice(0, -2);
            return fun;
        }
    }

    render() {
        var href = this.getModuloHREF()
        if (this.conjunto == null) {
            if (this.icone == null) {
                $(".divLinhas").append("\
                <a class=\"linkLinha\"" + href + ">\
                    <div class=\"linha\" id=\"linha" + linhas + "\" onclick=\"linha" + linhas + ".rodar('click')\">\
                        <div class=\"sideContainer\">\
                            <a href=\"#\"><span class=\"textoNode\">" + this.titulo + "</span></a>\
                        </div>\
                    </div>\
                </a>\
                ")
            } else {
                $(".divLinhas").append("\
                <a class=\"linkLinha\"" + href + ">\
                    <div class=\"linha\" id=\"linha" + linhas + "\" onclick=\"linha" + linhas + ".rodar('click')\">\
                        <div class=\"sideContainer\">\
                            <i class=\"material-icons iconeNode\">" + this.icone + "</i>\
                            <span class=\"textoNode\">" + this.titulo + "</span>\
                        </div>\
                    </div>\
                </a>\
                ")
            }
        } else {
            if (this.icone == null) {
                $(this.conjunto).append("\
                <a class=\"linkLinha\"" + href + ">\
                    <div class=\"linha hidden\" id=\"linha" + linhas + "\" onclick=\"linha" + linhas + ".rodar('click')\">\
                        <div class=\"sideContainer\">\
                            <span class=\"textoChildren\">" + this.titulo + "</span>\
                        </div>\
                    </div>\
                </a>\
                ")
            } else {
                $(this.conjunto).append("\
                <a class=\"linkLinha\"" + href + ">\
                    <div class=\"linha hidden\" id=\"linha" + linhas + "\" onclick=\"linha" + linhas + ".rodar('click')\">\
                        <div class=\"sideContainer\">\
                            <i class=\"material-icons iconeNode\">" + this.icone + "</i>\
                            <span class=\"textoChildren\">" + this.titulo + "</span>\
                        </div>\
                    </div>\
                </a>\
                ")
            }
        }
    }

    select() {
        $("#linha" + this.id).css("cssText", "color: rgb(141, 212, 93) !important");
    }

    removeSelect() {
        $("#linha" + this.id).css("color", "white");
    }

    rodar(click) {
        removeAllSelection()
        selectLinha(this)
        if (this.getActionType() == "MODULO") {
            var modulo = this.action.substring(2),
                achou = false;
            for (var i = 0; i < arrayModulos.length; i++) {
                var moduloDoArray = arrayModulos[i]
                if (moduloDoArray.id == modulo) {
                    achou = true;
                    moduloDoArray.invoker()
                    if (moduloDoArray.linha.id != 1) {
                        if (click == 'click') {
                            setParam("modulo", moduloDoArray.id, true, true)
                        }
                    } else {
                        removeAllParans()
                    }
                }
            }
            if (achou == false) {
                acionarErro("O módulo '" + modulo + "' não existe")
                removeAllSelection()
            }
        }
        if (this.getActionType() == "FUNCTION") {
            try {
                var fun = this.action
                fun = fun.slice(0, -2);
                window[fun]()
            } catch (err) {
                acionarErro("A função '" + this.action + "' não existe")
                removeAllSelection()
            }
        }
    }

}

class Node {

    constructor(titulo, icone) {
        var isOpen = false;
        nodes++;
        this.id = nodes;
        this.titulo = titulo;
        this.icone = icone;
        arrayNodes.push(this)
        this.render()
    }

    add(conjunto) {
        conjunto.setNode()
        this.conjunto = conjunto + "";
    }

    render() {
        $(".divLinhas").append("\
        <div class=\"node\" id=\"node" + nodes + "\" onclick=\"node" + nodes + ".rodar()\">\
            <div class=\"sideContainer\">\
                <i class=\"material-icons iconeNode\">" + this.icone + "</i>\
                <span class=\"textoNode\">" + this.titulo + "</span>\
                <div class=\"container\">\
                    <i id=\"arrow" + nodes + "\" class=\"material-icons arrow\">arrow_drop_up</i>\
                </div>\
            </div>\
        </div>")
    }

    rodar() {
        if (this.isOpen == true) {
            this.close()
        } else {
            this.open()
        }
    }

    open() {
        $("#node" + this.id).css("color", "rgb(127, 148, 197)");
        $("#node" + this.id).css("background-color", "rgb(50, 50, 61)");
        $("#arrow" + this.id).css("transform", "rotateZ(180deg)");
        $("#conjunto" + this.id).show(100);
        this.isOpen = true;
    }

    close() {
        $("#node" + this.id).css("color", "white");
        $("#node" + this.id).css("background-color", "rgb(59, 59, 75)");
        $("#arrow" + this.id).css("transform", "rotateZ(90deg)");
        $("#conjunto" + this.id).hide(100);
        this.isOpen = false;
    }
}

class Conjunto {

    constructor() {
        conjuntos++;
        this.render()
    }

    setNode(node) {
        this.node = node;
    }

    render() {
        $(".divLinhas").append("<div class=\"conjunto\" id=\"conjunto" + conjuntos + "\"></div>")
    }
}

function closeSide() {
    $('.button-collapse').sideNav('hide');
}