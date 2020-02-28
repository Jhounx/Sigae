var linhas = 0, conjuntos = 0, nodes = 0, arrayNodes = []; arrayLinhas = []

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
    linha5 = new Linha("Alunos", null, "#conjunto2", "--alunos");
    linha6 = new Linha("Docentes e monitores", null, "#conjunto2", "--docentes");

    //Meu usuário
    node3 = new Node("Meu usuário", "people")
    conjunto3 = new Conjunto()
    node3.add(conjunto3)
    linha7 = new Linha("Alterar meus dados", null, "#conjunto3", "--alterarDados");
    linha8 = new Linha("Alterar minha senha", null, "#conjunto3", "mudarSenha()");

    //Configurações
    node4 = new Node("Configurações", "settings")
    conjunto4 = new Conjunto()
    node4.add(conjunto4)
    linha9 = new Linha("Alterar configurações", null, "#conjunto4", "--alterarConfig");
    linha10 = new Linha("Abrir console", null, "#conjunto4", "abrirConsole()");
    linha11 = new Linha("Sobre o SiGAÊ", null, "#conjunto4", "sobreSigae()");

    //Finalizar sessão
    linha12 = new Linha("Finalizar sessão", "exit_to_app", null, "finalizarSessao()");

    modulos()
}

function modulos() {
    inicio = new Modulo("inicio", "../modulos/inicio", "Início", "home", true, false, linha1);
    calendario = new Modulo("calendario", "../modulos/calendario", "Calendário de atendimentos", "perm_contact_calendar", true, true, linha2);
    //calendarioMinhaTurma = new Modulo("calendarioMinhaTurma", "../modulos/atendimentosAgendados", "Meus atendimentos agendados", "perm_contact_calendar", false, false, linha3);

    setModuloParam()
}

function setModuloParam() {
    var moduloParam = getParam()["modulo"];
    if (moduloParam == undefined) {
        linha1.do()
    } else {
        for (var i = 0; i < arrayModulos.length; i++) {
            var moduloDoArray = arrayModulos[i]
            if (moduloDoArray.id == moduloParam) {
                moduloDoArray.linha.do()
            }
        }
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

class Linha {

    id; titulo; icone; conjunto; conjuntoNumero; action;

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

    render() {
        if (this.conjunto == null) {
            if (this.icone == null) {
                $(".divLinhas").append("\
                <div class=\"linha\" id=\"linha" + linhas + "\" onclick=\"linha" + linhas + ".do('click')\">\
                    <div class=\"sideContainer\">\
                        <span class=\"textoNode\">" + this.titulo + "</span>\
                    </div>\
                </div>")
            } else {
                $(".divLinhas").append("\
                <div class=\"linha\" id=\"linha" + linhas + "\" onclick=\"linha" + linhas + ".do('click')\">\
                    <div class=\"sideContainer\">\
                        <i class=\"material-icons iconeNode\">" + this.icone + "</i>\
                        <span class=\"textoNode\">" + this.titulo + "</span>\
                    </div>\
                </div>")
            }
        } else {
            if (this.icone == null) {
                $(this.conjunto).append("\
                <div class=\"linha hidden\" id=\"linha" + linhas + "\" onclick=\"linha" + linhas + ".do('click')\">\
                    <div class=\"sideContainer\">\
                        <span class=\"textoChildren\">" + this.titulo + "</span>\
                    </div>\
                </div>")
            } else {
                $(this.conjunto).append("\
                <div class=\"linha hidden\" id=\"linha" + linhas + "\" onclick=\"linha" + linhas + ".do('click')\">\
                    <div class=\"sideContainer\">\
                        <i class=\"material-icons iconeNode\">" + this.icone + "</i>\
                        <span class=\"textoChildren\">" + this.titulo + "</span>\
                    </div>\
                </div>")
            }
        }
    }

    select() {
        $("#linha" + this.id).css("cssText", "color: rgb(141, 212, 93) !important");
    }

    removeSelect() {
        $("#linha" + this.id).css("color", "white");
    }

    do(click) {
        removeAllSelection()
        closeAll(nodeDaLinha(this))
        selectLinha(this)
        if (this.action.startsWith("--")) {
            var modulo = this.action.substring(2), achou = false;
            for (var i = 0; i < arrayModulos.length; i++) {
                var moduloDoArray = arrayModulos[i]
                if (moduloDoArray.id == modulo) {
                    achou = true;
                    moduloDoArray.invoker()
                    if (moduloDoArray.linha.id != 1) {
                        if (click == 'click') {
                            addParam("modulo", moduloDoArray.id)
                        }
                    } else {
                        removeParam()
                    }
                }
            }
            if(achou == false) {
                erro("O módulo '" + modulo + "' não existe")
                removeAllSelection()
            }
        }
        if (this.action.endsWith("()")) {
            try {
                var fun = this.action
                fun = fun.slice(0, -2);
                window[fun]()
            } catch (err) {
               erro("A função '" + this.action + "' não existe")
               removeAllSelection()
            }
        }
    }

}

class Node {

    id; titulo; icone; conjunto; isOpen = false;

    constructor(titulo, icone) {
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
        <div class=\"node\" id=\"node" + nodes + "\" onclick=\"node" + nodes + ".do()\">\
            <div class=\"sideContainer\">\
                <i class=\"material-icons iconeNode\">" + this.icone + "</i>\
                <span class=\"textoNode\">" + this.titulo + "</span>\
                <div class=\"container\">\
                    <i id=\"arrow" + nodes + "\" class=\"material-icons arrow\">arrow_drop_up</i>\
                </div>\
            </div>\
        </div>")
    }

    do() {
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

    node;

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