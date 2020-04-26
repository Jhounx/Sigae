var atendimento, id, dados

function init_atendimentoDocente() {
    carregarAtendimento()
}

function carregarAtendimento() {
    id = getParam("id")
    request = new Request()
    request.add("pegarAtendimentoByID", "")
    request.add("id", id)
    request.send("GET", ["JSON"], (resultado) => {
        resposta = resultado.resposta;
        erro = resultado.erro;
        if (resposta != null) {
            if (JSON.stringify(resposta) == "{}") {
                acionarErro("Esse atendimento não existe")
                linha1.rodar()
            } else {
                dados = resposta[id]
                atendimento = new Atendimento(dados)
                carregarDados()
            }
        } else {
            alert(erro)
            acionarErro("Requisição negada")
        }
    })
}

function carregarDados() {
    atendimentoDocente.setBreadcrumbs(atendimento.pegarNome())
    $("#aLinhAtendimento").attr("href", "./?modulo=agendarAtendimento&id=" + id)

    $("#r1").text(atendimento.pegarNome())
    if(atendimento.pegarDescricao() == "") {
        $("#r2-banner").show()
    } else {
        $("#r2").text(atendimento.pegarDescricao())
    }
    $("#r3").text(atendimento.pegarDocente())
    $("#r4").text(atendimento.pegarData())
    $("#r5").text(atendimento.pegarHorarioInicio())
    $("#r6").text(atendimento.pegarHorarioFim())
    $("#r7").text(atendimento.pegarDuracao())
    $("#r8").text(atendimento.pegarMateria())
    $("#r9").text(atendimento.pegarSala())

    $("#r10").text(atendimento.pegarTipo())
    $("#r11").text(atendimento.pegarEstado())
    if(atendimento.pegarEstado(true) == "NAO") {
        $("#r11").addClass("alert-warning")
    }
    if(atendimento.pegarEstado(true) == "CON") {
        $("#r11").addClass("alert-success")
    }
    if(atendimento.pegarEstado(true) == "CAN") {
        $("#r11").addClass("alert-danger")
    }
    $("#r12").text(atendimento.pegarCampus())

    if(atendimento.pegarLimite() == "SEM_LIMITE") {
        $("#r13").addClass("alert-info")
    } else {
        $("#r13").removeClass("alert banner")
        $("#r13").text(atendimento.pegarLimite() + " alunos")
    }
    $("#r14").text(atendimento.pegarTotalAlunos() + " alunos")
    $("#r15").text(atendimento.pegarTotalAlunosConfirmados() + " alunos")
    $("#r16").text(atendimento.pegarPorConfirmados() + "%")

    $("#r17").text(atendimento.pegarDataAgendamento())
    $("#r18").text(atendimento.pegarDataUltimaModificacao())
    atendimentoDocente.show()
    initMaterialize()
}

function initMaterialize() {
    $(document).ready(function () {
        $("ul.tabs").tabs();
    });
}