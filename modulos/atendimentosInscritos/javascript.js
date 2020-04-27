nLinhas = 1;

function init_atendimentosInscritos() {
    carregarDados()
}

function mostrarTabela() {
    $(".loaderDiv").hide()
    $(".tbody").show()
}

function mostrarErro() {
    $(".nenhumResultado").css("display", "flex")
    $(".tbody").empty();
    mostrarTabela()
}

function retirarErro() {
    $(".nenhumResultado").css("display", "none")
}

function carregarDados() {
    universalAbort()
    request = new Request()
    request.add("pegarTodosAtendimentosDiscente", "")
    request.send("GET", ["JSON"], (resultado) => {
        resposta = resultado.resposta;
        erro = resultado.erro;
        if (resposta != null) {
            if(JSON.stringify(resposta) == "{}") {
                mostrarErro()
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
                mostrarTabela()
                eventos()
            } 
        } else {
            alert(erro)
            acionarErro("Requisição negada")
        }
    })
}

function renderizarLinha(id, data, inicio, fim, sala, estado) {
    cancel = ""
    if(aten.pegarEstado(true) == "CAN") {
        cancel = "cancel"
    }
    a =
    "\
    <tr class=\"hover " + cancel + "\" onclick=\"invoker_atendimento('" + id + "')\">\
        <td>" + data + "</td>\
        <td>" + inicio + "</td>\
        <td>" + fim + "</td>\
        <td class=\"salaLinha\">" + sala + "</td>\
        <td class=\"resposta bannerTd\">\
            <div id=\"linha-" + nLinhas + "\" class=\"alert banner\">" + estado + "</div>\
        </td>\
    </tr>\
    ";
    $(".tbody").append(a)
    if(aten.pegarEstado(true) == "NAO") {
        $("#linha-" + nLinhas).addClass("alert-warning")
    }
    if(aten.pegarEstado(true) == "CON") {
        $("#linha-" + nLinhas).addClass("alert-success")
    }
    if(aten.pegarEstado(true) == "CAN") {
        $("#linha-" + nLinhas).addClass("alert-danger")
    }
    nLinhas++;
}

function eventos() {
    check = false;
    $(".checkboxInput").on("change", function(input) {
        check = !check
        if(check == true) {
            $(".cancel").show()
        }
        if(check == false) {
            $(".cancel").hide()
        }
    })
}