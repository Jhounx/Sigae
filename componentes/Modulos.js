arrayNodes = [], arrayConjuntos = [], arrayLinhas = [], arrayPopup = [], arrayModulos = [],
    moduloParametro = null, moduloAtual = null, libsAtuais = [],
    sigae = {
        nodes: {
            node1: {
                titulo: "Calendários",
                icone: "perm_contact_calendar",
                conjunto: "conjunto1"
            },
            node2: {
                titulo: "Alunos e professores",
                icone: "school",
                conjunto: "conjunto2"
            },
            node3: {
                titulo: "Meu usuário",
                icone: "people",
                conjunto: "conjunto3"
            },
            node4: {
                titulo: "Configurações",
                icone: "settings",
                conjunto: "conjunto4"
            }
        },
        linhas: {
            inicio: {
                titulo: "Início",
                icone: "home",
                conjunto: null,
                modulo: "inicio",
                top: true
            },
            calendario: {
                titulo: "Calendário de atendimentos",
                icone: "null",
                conjunto: "conjunto1",
                modulo: "calendario"
            },
            atendimentosAgendados: {
                titulo: "Meus atendimentos agendados",
                icone: "null",
                conjunto: "conjunto1",
                modulo: "atendimentosAgendados",
                only: ["DOC", "MON"]
            },
            atendimentosInscritos: {
                titulo: "Meus atendimentos inscritos",
                icone: "null",
                conjunto: "conjunto1",
                modulo: "atendimentosInscritos",
                only: ["ALU"]
            },
            minhaTurma: {
                titulo: "Minha turma",
                icone: "null",
                conjunto: "conjunto2",
                modulo: "minhaTurma"
                ,
                only: ["ALU"]
            },
            alunos: {
                titulo: "Alunos",
                icone: "null",
                conjunto: "conjunto2",
                modulo: "alunos",
                only: ["DOC", "MON"]
            },
            docentes: {
                titulo: "Docentes e monitores",
                icone: "null",
                conjunto: "conjunto2",
                modulo: "docentes"
            },
            mudarDados: {
                titulo: "Alterar meus dados",
                icone: "null",
                conjunto: "conjunto3",
                modulo: "mudarDados"
            },
            alterarSenha: {
                titulo: "Alterar minha senha",
                icone: "null",
                conjunto: "conjunto3",
                modulo: "alterarSenhaShow()"
            },
            configurarNotificacoes: {
                titulo: "Configurar notificações",
                icone: "null",
                conjunto: "conjunto4",
                modulo: "alterarNotificacoesShow()"
            },
            abrirConsole: {
                titulo: "Abrir console",
                icone: "null",
                conjunto: "conjunto4",
                modulo: "abrirConsole()"
            },
            sobreSigae: {
                titulo: "Sobre o SiGAÊ",
                icone: "null",
                conjunto: "conjunto4",
                modulo: function () {
                    getPopup("sobreSigae").show()
                }
            },
            finalizarSessao: {
                titulo: "Finalizar sessão",
                icone: "exit_to_app",
                conjunto: null,
                modulo: function () {
                    window.location.href = "./?finalizarSessao";
                }
            },
        },
        popups: {
            sobreSigae: {
                titulo: "Sobre o SiGAÊ",
                largura: "500px",
                css: true,
                javascript: true,
                autoInvoker: true
            }
        },
        modulos: {
            inicio: {
                titulo: "Início",
                icone: "home",
                css: true,
                javascript: true,
                linha: "inicio"
            },
            calendario: {
                titulo: "Calendário de atendimentos",
                icone: "perm_contact_calendar",
                css: true,
                responsive: true,
                javascript: true,
                linha: "calendario",
                libs: [
                    "calendarize.js"
                ]
            },
            atendimentosAgendados: {
                titulo: "Meus atendimentos agendados",
                icone: "perm_contact_calendar",
                css: true,
                responsive: true,
                javascript: true,
                linha: "atendimentosAgendados",
                wait: true,
                only: ["DOC", "MON"]
            },
            atendimentosInscritos: {
                titulo: "Meus atendimentos inscritos",
                icone: "perm_contact_calendar",
                css: true,
                responsive: true,
                javascript: true,
                linha: "atendimentosInscritos",
                wait: true,
                only: ["ALU"]
            },
            minhaTurma: {
                titulo: "Minha turma",
                icone: "school",
                css: true,
                responsive: true,
                javascript: true,
                linha: "minhaTurma",
                only: ["ALU"]
            },
            alunos: {
                titulo: "Alunos",
                icone: "perm_contact_calendar",
                css: true,
                responsive: true,
                javascript: true,
                linha: "alunos",
                only: ["DOC", "MON"]
            },
            docentes: {
                titulo: "Docentes e monitores",
                icone: "school",
                css: true,
                responsive: true,
                javascript: true,
                linha: "docentes",
            },
            mudarDados: {
                titulo: "Alterar dados",
                icone: "build",
                css: true,
                responsive: true,
                javascript: true,
                files: [
                    "fotos.js"
                ],
                libs: [
                    "cropper.min.js",
                    "cropper.min.css"
                ],
                linha: "mudarDados",
            },
            agendarAtendimento: {
                titulo: "Agendar atendimento",
                icone: "perm_contact_calendar",
                css: true,
                javascript: true,
                wait: true,
                only: ["DOC", "MON"]
            },
            atendimento: {
                titulo: "Atendimento",
                icone: "perm_contact_calendar",
                css: true,
                responsive: true,
                javascript: true,
                wait: true
            },
            erro404: {
                titulo: "Módulo não encontrado",
                icone: "error",
                css: true
            },
        }
    }

function loadMainJson() {
    nodes = sigae["nodes"]
    linhas = sigae["linhas"]
    popups = sigae["popups"]
    modulos = sigae["modulos"]
    /* Leitura nodes */
    Object.keys(nodes).forEach(function (value) {
        v = nodes[value]
        n = new Node(value, v["titulo"], v["icone"], v["conjunto"])
        c = new Conjunto(v["conjunto"], n)
        n.render()
        c.render()
        arrayConjuntos.push(c)
        arrayNodes.push(n)
    });
    /* Leitura das linhas */
    Object.keys(linhas).forEach(function (value) {
        v = linhas[value]
        if (verificarPermissao(v["only"])) {
            l = new Linha(value, v["titulo"], v["icone"], v["conjunto"], v["modulo"], v["top"])
            l.render()
            arrayLinhas.push(l)
        }
    });
    /* Leitura dos popups */
    Object.keys(popups).forEach(function (value) {
        v = popups[value]
        p = new Popup(value, v["titulo"], v["css"], v["javascript"], v["scroll"], v["largura"], v["altura"], v["showButton"], v["closeOnClick"], v["autoInvoker"])
        arrayPopup.push(p)
    });
    /* Leitura dos módulos */
    Object.keys(modulos).forEach(function (value) {
        v = modulos[value]
        m = new Modulo(value, v["titulo"], v["icone"], v["css"], v["responsive"], v["javascript"], v["files"], v["libs"], v["linha"], v["wait"])
        arrayModulos.push(m)
    });
    initModulo()
}

function initModulo() {
    if (paramExist("modulo")) {
        p = getParam("modulo")
        moduloParametro = p
        moduloAtual = p
        m = getModulo(p)
        if (m != undefined) {
            l = getLinha(m.linha)
            if (l != undefined) {
                l.rodar()
                getConjunto(getLinha(m.linha).conjunto).node.open()
            } else {
                eval("invoker_" + m.nome + "()")
            }
        } else {
            getModulo("erro404").invoker()
        }
    } else {
        getModulo("inicio").invoker()
        moduloAtual = "inicio"
        selectModuloAtual()
    }
}

/*
###################
## Pegar objetos ##
###################
*/
function getNode(nome) {
    for (i = 0; i < arrayNodes.length; i++) {
        if (arrayNodes[i].nome == nome) {
            return arrayNodes[i]
        }
    }
}

function getConjunto(nome) {
    for (i = 0; i < arrayConjuntos.length; i++) {
        if (arrayConjuntos[i].nome == nome) {
            return arrayConjuntos[i]
        }
    }
}

function getLinha(nome) {
    for (i = 0; i < arrayLinhas.length; i++) {
        if (arrayLinhas[i].nome == nome) {
            return arrayLinhas[i]
        }
    }
}

function getPopup(nome) {
    for (i = 0; i < arrayPopup.length; i++) {
        if (arrayPopup[i].nome == nome) {
            return arrayPopup[i]
        }
    }
}

function getModulo(nome) {
    for (i = 0; i < arrayModulos.length; i++) {
        if (arrayModulos[i].nome == nome) {
            return arrayModulos[i]
        }
    }
}

/*
########################
## Funções da sidebar ##
########################
*/

function selectModuloAtual() {
    if(moduloAtual != null) {
        for(i = 0; i < arrayLinhas.length; i++) {
            l = arrayLinhas[i]
            l.removeSelect()
        }
        getLinha(getModulo(moduloAtual).linha).select()
    }
}

function removeAllSelections() {
    for (var i = 0; i < arrayLinhas.length; i++) {
        obj = arrayLinhas[i]
        obj.removeSelect()
    }
}

/*
########################
## Classes            ##
########################
*/
class Node {

    constructor(nome, titulo, icone, conjunto) {
        this.nome = nome;
        this.titulo = titulo
        this.icone = icone
        this.conjunto = conjunto
        this.aberto = false
    }

    render() {
        var a = `
        <div class="node" id="` + this.nome + `" onclick="getNode('` + this.nome + `').alterarEstado()">
            <div class="sideContainer">
                <i class="material-icons iconeNode">` + this.icone + `</i>
                <span class="textoNode">` + this.titulo + `</span>
                <div class="container">
                    <i id="arrow_` + this.nome + `" class="material-icons arrow">arrow_drop_up</i>\
                </div>
            </div>
        </div>`
        $(".divLinhas").append(a)
    }

    alterarEstado() {
        this.aberto ? this.close() : this.open()
    }

    open() {
        $("#" + this.nome).css("color", "rgb(127, 148, 197)");
        $("#" + this.nome).css("background-color", "rgb(50, 50, 61)");
        $("#arrow_" + this.nome).css("transform", "rotateZ(180deg)");
        $("#" + this.conjunto).show(100);
        this.aberto = true;
    }

    close() {
        $("#" + this.nome).css("color", "white");
        $("#" + this.nome).css("background-color", "rgb(59, 59, 75)");
        $("#arrow_" + this.nome).css("transform", "rotateZ(90deg)");
        $("#" + this.conjunto).hide(100);
        this.aberto = false;
    }

}

class Conjunto {

    constructor(nome, node) {
        this.nome = nome
        this.node = node
    }

    render() {
        $(".divLinhas").append('<div class="conjunto" id="' + this.nome + '"></div>')
    }
}

class Linha {

    constructor(nome, titulo, icone, conjunto, modulo, top) {
        this.nome = nome
        this.titulo = titulo
        this.icone = icone
        this.conjunto = conjunto
        this.modulo = modulo
        this.top = top
    }

    getLink() {
        if (typeof this.modulo === "function" || this.modulo.endsWith("()")) {
            return ""
        }
        var modulo = 'href="?modulo=' + this.modulo + '"'
        return this.modulo.endsWith("()") ? '' : modulo
    }

    getModuloType() {
        if (typeof this.modulo === "function" || this.modulo.endsWith("()")) {
            return "function"
        } else {
            return "modulo"
        }
    }

    render() {
        var ico = "", classe, hidden = "";
        if (this.icone !== "null") {
            classe = "textoNode"
            ico = `<i class="material-icons iconeNode">` + this.icone + `</i>`
        } else {
            hidden = "hidden"
            classe = "textoChildren"
        }
        var a = `
        <a class="linkLinha" ` + this.getLink() + `">
            <div class="linha ` + hidden + `" id="linha_` + this.nome + `" onclick="getLinha('` + this.nome + `').rodar(true)">
                <div class="sideContainer">
                    ` + ico + `
                    <span class="` + classe + `">` + this.titulo + `</span>
                </div>
            </div>
        </a>`
        if (this.conjunto == "null" || this.conjunto == null) {
            if (this.top == true) {
                $(".divLinhas").prepend(a)
            } else {
                $(".divLinhas").append(a)
            }
        } else {
            $("#" + getConjunto(this.conjunto).nome).append(a)
        }
    }

    select() {
        $("#linha_" + this.nome).css("cssText", "color: rgb(141, 212, 93) !important");
    }

    removeSelect() {
        $("#linha_" + this.nome).css("color", "white");
    }

    rodar(invokedByLine) {
        removeAllSelections()
        this.select()
        if (this.getModuloType() == "modulo") {
            m = getModulo(this.modulo)
            if (m != undefined) {
                m.invoker()
                if (this.modulo == "inicio") {
                    removeAllParans()
                } else {
                    if (invokedByLine) {
                        universalAbort()
                        setParam("modulo", this.modulo, true, true)
                    }
                }
            } else {
                acionarErro("O módulo '" + this.modulo + "' não existe")
                removeAllSelection()
            }
            if (this.conjunto != null) {
                getNode(getConjunto(this.conjunto).node.nome).open()
            }
        }
        if (this.getModuloType() == "function") {
            if (typeof this.modulo === "function") {
                this.modulo()
            } else {
                try {
                    var fun = this.modulo
                    fun = fun.slice(0, -2);
                    window[fun]()
                } catch (err) {
                    acionarErro("A função '" + this.modulo + "' não existe")
                    removeAllSelections()
                }
            }
        }

    }
}

class Modulo {

    constructor(nome, titulo, icone, css, responsive, javascript, files, libs, linha, wait) {
        this.nome = nome
        this.titulo = titulo
        this.icone = icone
        this.css = css
        this.responsive = responsive
        this.javascript = javascript
        this.files = files
        this.libs = libs
        this.linha = linha
        this.wait = wait
    }

    invoker() {
        moduloAtual == this.nome
        if (moduloParametro == null) {
            $("modulo").remove();
            $(".content-head").empty()
            $(".breadcrumbs").hide();
            $("#carregamentoModulo").show();
            $(".button-collapse").sideNav('hide');
        }
        var classe = this;
        $.ajax({
            url: "../modulos/" + this.nome + "/index.html",
            type: 'get',
            dataType: 'html',
            async: true,
            success: function (data) {
                classe.render(data)
            }
        });
    }

    render(data) {
        var classe = this;
        if (this.css == true) {
            $(".content-head").append('<link rel="stylesheet" href="../modulos/' + this.nome + '/css.css">');
        }
        if (this.responsive == true) {
            $(".content-head").append('<link rel="stylesheet" href="../modulos/' + this.nome + '/responsive.css">');
        }
        if (this.javascript == true) {
            $(".content-head").append('<script src="../modulos/' + this.nome + '/javascript.js"></script>');
        }

        if (this.files != null && this.files != "null") {
            for (i = 0; i < this.files.length; i++) {
                var f = this.files[i]
                if (f.endsWith("js")) {
                    $(".content-head").append('<script src="../modulos/' + this.nome + '/' + f + '"></script>');
                } else {
                    $(".content-head").append('<link rel="stylesheet" href="../modulos/' + this.nome + '/' + f + '">');
                }
            }
        }
        if (this.libs != null && this.libs != "null") {
            for (i = 0; i < this.libs.length; i++) {
                var l = this.libs[i]
                if (!libsAtuais.includes(l)) {
                    if (l.endsWith("js")) {
                        $(".content-libs").append('<script src="../componentes/APIs/' + l + '"></script>');
                    } else {
                        $(".content-libs").append('<link rel="stylesheet" href="../componentes/APIs/' + l + '">');
                    }
                    libsAtuais.push(l)
                }
            }
        }
        setTimeout(function () {
            $(".headerPopup").empty();
            $("modulo").remove();
            $(".breadcrumbsTitulo").text(classe.titulo)
            $(".breadcrumbs-icone").text(classe.icone)
            $(".breadcrumbs").attr("data-tooltip", classe.titulo);
            $(".breadcrumbs-tooltipped").tooltip({ delay: 50 });
            $(".content").append(data)
            if (classe.wait == true) {
                $("modulo").hide()
            } else {
                classe.show()
            }
            if (classe.javascript == true) {
                if (eval("typeof init_" + classe.nome) == "function") {
                    window["init_" + classe.nome]()
                } else {
                    acionarErro("A função " + "'init_" + classe.nome + "' não existe")
                }
            }
        }, 500);
    }

    show() {
        $("#carregamentoModulo").hide();
        $(".breadcrumbs").show();
        $("modulo").show()
        if (moduloParametro != null) {
            mostrarInterface()
        }
    }

    setBreadcrumbs(text) {
        $(".breadcrumbsTitulo").text(text)
        $(".breadcrumbs").attr("data-tooltip", text);
        $(".breadcrumbs-tooltipped").tooltip({ delay: 50 });
    }
}

class Popup {

    constructor(nome, titulo, css, javascript, scroll, largura, altura, showButton, closeOnClick, autoInvoker) {
        this.nome = nome
        this.titulo = titulo
        this.css = css
        this.javascript = javascript
        this.scroll = scroll
        this.largura = largura
        this.altura = altura
        this.showButton = showButton
        this.closeOnClick = closeOnClick
        this.autoInvoker = autoInvoker
        this.invoked = false
        if (autoInvoker != undefined) this.invoker()
        if (showButton == undefined) this.showButton = true
        if (closeOnClick == undefined) this.closeOnClick = true
    }

    invoker() {
        if (this.invoked) return;
        var classe = this;
        $.ajax({
            url: "../modulos/$popups/" + this.nome + "/index.html",
            type: 'get',
            dataType: 'html',
            async: false,
            success: function (data) {
                classe.html = data
                classe.invoked = true
            }
        });
    }

    show() {
        if (!this.invoked) this.invoker();
        if (this.css == true) {
            $(".headerPopup").append(`<link rel="stylesheet" href="../modulos/$popups/` + this.nome + `/css.css">`);
        }
        if (this.javascript == true) {
            $(".headerPopup").append(`<script src="../modulos/$popups/` + this.nome + `/javascript.js"></script>`);
        }
        var classe = this, alturaScroll;
        alturaScroll = $('body').scrollTop();
        Swal.fire({
            title: '<img src="../icones/sigae.svg" width="150" height="91"><br><h1 class="tituloPopup" style="font-size: 18px">' + this.titulo + '</h1>',
            animation: false,
            cancelButtonText: 'Fechar',
            showCancelButton: this.showButton,
            cancelButtonColor: '#d33',
            showConfirmButton: false,
            allowOutsideClick: this.closeOnClick,
            allowEnterKey: false,
            onOpen: function () {
                $("body").scrollTop(alturaScroll);
                if (classe.javascript == true) runFunction("init_" + classe.nome, true)
            },
            onClose: function () {
                if (classe.javascript == true) runFunction("close_" + classe.nome)
                $(".headerPopup").empty();
                $("body").animate({ scrollTop: alturaScroll }, 100);
                selectModuloAtual()
            },
            html: `${classe.html}`
        })
        if (this.altura != null) {
            $(".swal2-popup").css("cssText", "height: " + this.altura + " !important; width: " + this.largura + " !important; display:flex");
        } else {
            $(".swal2-popup").css("cssText", "width: " + this.largura + " !important; display:flex");
        }
        if (this.scroll == true) {
            $(".swal2-content").css("cssText", "overflow-y: auto; !important");
        } else {
            $(".swal2-content").css("cssText", "overflow-y: hidden");
        }
    }

}