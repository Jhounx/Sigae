function init_atendimentosAgendados() {
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
    request.add("pegarTodosAtendimentosDocente", "")
    request.send("GET", ["JSON"], (resultado) => {
        resposta = resultado.resposta;
        erro = resultado.erro;
        if (resposta != null) {
            if(JSON.stringify(resposta) == "{}") {
                mostrarErro()
            } else {
                Object.keys(resposta).forEach(function (nome) {
                    a = resposta[nome]
                    id = nome
                    data = a["data"]
                    inicio = a["horarioInicio"]
                    fim = a["horarioFim"]
                    sala = a["sala"]
                    estado = a["estado"]
                    if(estado == "NAO") {
                        estado = "NÃO CONFIRMADO"
                    }
                    if(estado == "CON") {
                        estado = "CONFIRMADO PELO DOCENTE"
                    }
                    renderizarLinha(id, data, inicio, fim, sala, estado)
                });
                mostrarTabela()
            } 
        } else {
            alert(erro)
            acionarErro("Requisição negada")
        }
    })
}

function renderizarLinha(id, data, inicio, fim, sala, estado) {
    a =
    "\
    <tr class=\"hover\" onclick=\"invoker_atendimentoDocente('" + id + "')\">\
        <td>" + data + "</td>\
        <td>" + inicio + "</td>\
        <td>" + fim + "</td>\
        <td class=\"salaLinha\">" + sala + "</td>\
        <td class=\"text-right\">" + estado + "</td>\
    </tr>\
    ";
    $(".tbody").append(a)
}