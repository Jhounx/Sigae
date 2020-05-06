var
    pagina = 1,
    numeroRegistros = -1,
    numeroPaginas = -1,
    valorBusca = null,
    carregando = false;

/* Init */

function init_alunos() {
    $(".selectFiltro").selectpicker();
    $("#campusTitle").text(getCampus())
    if (paramExist("pagina")) {
        var n = Math.floor(Number(getParam("pagina")));
        if (n !== Infinity && String(n) == getParam("pagina") && n > 0) {
            pagina = n;
        } else {
            pagina = 1;
            setParam("pagina", pagina)
        }
    }
    eventos()
    carregarNumeroPaginas()
}

function eventos() {
    $(".campoFiltro").on("keypress", function (e) {
        if (e.which == 13) {
            $(".campoFiltro").blur();
            valorBusca = $(".campoFiltro").val()
            if (valorBusca.length > 0) {
                carregarNumeroPaginas()
                setParam("valorBusca", valorBusca)
            } else {
                carregarNumeroPaginas()
                removeParam("valorBusca")
            }
        }
    });
    if (paramExist("valorBusca")) {
        valorBusca = getParam("valorBusca");
        $(".campoFiltro").val(valorBusca)
    }
}

/* funções de alternância */
function mostrarTabela() {
    $(".loaderDiv").hide()
    $(".tbody").show()
}

function esconderTabela() {
    $(".loaderDiv").show()
    $(".tbody").hide()
    $(".nenhumResultado").css("display", "none")
}

function mostrarErro() {
    $(".nenhumResultado").css("display", "flex")

    $(".tbody").empty();
    mostrarTabela()
}

function retirarErro() {
    $(".nenhumResultado").css("display", "none")
}

function mostrarPaginacao() {
    $(".divPagination").show()
}

function esconderPaginacao() {
    $(".divPagination").hide()
}

function proximoPage() {
    i = pagina + 1;
    if (i <= numeroPaginas) {
        carregarPagina(i)
    }
}

function anteriorPage() {
    i = pagina - 1;
    if (i >= 1) {
        carregarPagina(i)
    }
}

function ultimaPage() {
    carregarPagina(numeroPaginas)
}

function carregarNumeroPaginas() {
    esconderTabela()
    esconderPaginacao()
    request = new Request()
    request.add("quantidadeDeRegistrosDiscentes", "")
    request.add("id", getID())
    request.add("campus", getCampusID())
    if (valorBusca != null && valorBusca != "") {
        request.add("busca", valorBusca)
    }
    request.send("GET", ["INTEGER", "NEG"], (resposta) => {
        numeroRegistros = resposta;
        numeroPaginas = Math.ceil(numeroRegistros / 10)
        $("#totalPessoas").text(numeroRegistros)
        $("#totalPaginas").text(numeroPaginas)
        if (numeroRegistros > 0) {
            if (pagina > numeroPaginas) {
                pagina = numeroPaginas;
            }
            retirarErro()
            carregarPagina(pagina)
        } else {
            mostrarErro()
            mostrarTabela()
        }

    }, (erro) => {
        if (erro == "NEG") {
            removeAllParans()
            linha1.rodar()
        } else {
            alert(erro)
        }
        acionarErro("Requisição negada")
    })
}

function carregarPagina(p) {
    if (carregando == false) {
        carregando = true;
        pagina = p;
        esconderTabela()
        $(".tbody").empty();
        setParam("pagina", pagina)
        definirPaginacao()

        request = new Request()
        request.add("pegarTodosDiscentes", "")
        request.add("pagina", pagina)
        request.add("id", getID())
        request.add("campus", getCampusID())
        if (valorBusca != null && valorBusca != "") {
            request.add("busca", valorBusca)
        }
        request.send("GET", ["JSON"], (resposta) => {
            Object.keys(resposta).forEach(function (nome) {
                var id = resposta[nome]["id"];
                var turma = resposta[nome]["turma"];
                var tipo = resposta[nome]["tipo"];
                renderizarLinha(id, nome, turma, tipo)
            });
            mostrarTabela()
            mostrarPaginacao()
            carregando = false;
        }, (erro) => {
            alert(erro)
            acionarErro("Requisição negada")
        })
    }
}

function definirPaginacao() {
    $(".pagAtual").text(pagina)
    $(".pagTotal").text(numeroPaginas)
    if (pagina == 1) {
        $(".setaEsquerda, .setaEsquerda > a").addClass("disabled", true)
    } else {
        $(".setaEsquerda, .setaEsquerda > a").removeClass("disabled")
    }
    if (pagina == numeroPaginas) {
        $(".setaDireita, .setaDireita > a").addClass("disabled", true)
    } else {
        $(".setaDireita, .setaDireita > a").removeClass("disabled")
    }
}

function renderizarLinha(id, nome, turma, tipo) {
    a =
        "<tr class=\"hover\")>\
        <td>\
           <a class=\"linkTabela\" onclick=\"usuarioShow('" + id + "')\" href=\"javascript:void(0)\">\
                <div class=\"corpoNome\">\
                    <img class=\"circle circle-imagem\" src=\"../back-end/request.php?pegarFoto&id=" + id + "\" width=\"80\" height=\"80\">\
                    <div class=\"nomeLabel\">" + nome + "</div>\
                </div>\
           </a>\
        </td>\
        <td>" + turma + "</td>\
        <td class=\"text-right\">" + getTipoNome(tipo) + "</td>\
    </tr>\
    "
    $(".tbody").append(a)
}