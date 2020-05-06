nLinhas = 1;

function init_atendimentosAgendados() {
    carregarDados()
}

function mostrarErro() {
    $(".tableDiv").css("flex-direction", "column")
    $(".nenhumResultado").css("display", "flex")
}

function ocultarErro() {
    $(".tableDiv").css("flex-direction", "row")
    $(".nenhumResultado").css("display", "none")
}

function verificarErro() {
    var n = $(".tabela > tbody > tr:visible").length
    if (n == 0) {
        mostrarErro()
    } else {
        ocultarErro()
    }
}

function carregarDados() {
    universalAbort()
    request = new Request()
    request.add("pegarTodosAtendimentosDocente", "")
    request.send("GET", ["JSON"], (resposta) => {
        if (JSON.stringify(resposta) == "{}") {
            verificarErro()
        } else {
            Object.keys(resposta).forEach(function (nome) {
                aten = new Atendimento(resposta[nome])
                id = nome
                data = aten.pegarData()
                inicio = aten.pegarHorarioInicio()
                fim = aten.pegarHorarioFim()
                sala = aten.pegarSala()
                estado = aten.pegarEstado()
                renderizarLinha(id, data, inicio, fim, sala, estado)
            });
            atendimentosAgendados.show()
            verificarErro()
        }
        eventos()
    }, (erro) => {
        alert(erro)
        acionarErro("Requisição negada")
    })
}

function renderizarLinha(id, data, inicio, fim, sala, estado) {
    fixo = ""
    if (aten.pegarEstado(true) == "CAN") {
        fixo = "cancel"
    }
    if (aten.pegarEstado(true) == "FIN") {
        fixo = "concluido"
    }
    a =
        "\
    <tr class=\"hover " + fixo + "\" onclick=\"invoker_atendimento('" + id + "')\">\
        <td><a class=\"linkNaTabela\" href=\"?modulo=atendimento&id=" + id + "\">" + data + "</a></td>\
        <td><a class=\"linkNaTabela\" href=\"?modulo=atendimento&id=" + id + "\">" + inicio + "</a></td>\
        <td><a class=\"linkNaTabela\" href=\"?modulo=atendimento&id=" + id + "\">" + fim + "</a></td>\
        <td><a class=\"linkNaTabela salaLinha\" href=\"?modulo=atendimento&id=" + id + "\">" + sala + "</a></td>\
        <td class=\"resposta bannerTd\">\
            <div id=\"linha-" + nLinhas + "\" class=\"alert banner\">" + estado + "</div>\
        </td>\
    </tr>\
    ";
    $(".tbody").append(a)
    if (aten.pegarEstado(true) == "NAO") {
        $("#linha-" + nLinhas).addClass("alert-warning")
        $("#linha-" + nLinhas).attr("id", "NAO");
    }
    if (aten.pegarEstado(true) == "CON") {
        $("#linha-" + nLinhas).addClass("alert-success")
        $("#linha-" + nLinhas).attr("id", "CON");
    }
    if (aten.pegarEstado(true) == "FIN") {
        $("#linha-" + nLinhas).addClass("alert-success")
        $("#linha-" + nLinhas).attr("id", "FIN");
    }
    if (aten.pegarEstado(true) == "CAN") {
        $("#linha-" + nLinhas).addClass("alert-danger")
        $("#linha-" + nLinhas).attr("id", "CAN");
    }
    nLinhas++;
}

function eventos() {
    checkCancel = false, checkRealizados = false;
    $("#mostrarCancelados").on("change", function (input) {
        checkCancel = !checkCancel
        if (checkCancel == true) {
            $(".cancel").show()
        }
        if (checkCancel == false) {
            $(".cancel").hide()
        }
        if ($(".inputFiltrar").val().length > 0) {
            filtrarTabela($(".inputFiltrar").val().toLowerCase())
        }
        verificarErro()
    })
    $("#mostrarRealizados").on("change", function (input) {
        checkRealizados = !checkRealizados
        if (checkRealizados == true) {
            $(".concluido").show()
        }
        if (checkRealizados == false) {
            $(".concluido").hide()
        }
        if ($(".inputFiltrar").val().length > 0) {
            filtrarTabela($(".inputFiltrar").val().toLowerCase())
        }
        verificarErro()
    })
    $(".linkNaTabela").click(function (e) {
        e.preventDefault()
    });
    $(".dropdown-menu").click(function (e) {
        e.stopPropagation();
    });
    $(".inputFiltrar").keyup(function () {
        var value = $(this).val().toLowerCase();
        filtrarTabela(value)
    });
}

function filtrarTabela(value) {
    $("#tabela > tbody > tr").filter(function () {
        e = $(this).find("td.bannerTd>div").attr("id")
        if (checkCancel == false && e == "CAN") {
            return;
        }
        if (checkRealizados == false && e == "FIN") {
            return;
        }
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        verificarErro()
    });
}