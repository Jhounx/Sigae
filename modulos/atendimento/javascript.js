var aten, id, dados, arrayPresentes

function init_atendimento() {
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
                aten = new Atendimento(dados)
                carregarDados()
                carregarLista()
            }
        } else {
            alert(erro)
            acionarErro("Requisição negada")
        }
    })
}

function carregarDados() {
    atendimento.setBreadcrumbs("Detalhes do atendimento: " + aten.pegarNome())
    $("#aLinhAtendimento").attr("href", "./?modulo=agendarAtendimento&id=" + id)

    $("#r1").text(aten.pegarNome())
    if (aten.pegarDescricao() == "") {
        $("#r2-banner").show()
    } else {
        $("#r2").text(aten.pegarDescricao())
    }
    $("#r3").text(aten.pegarDocente())
    $("#r4").text(aten.pegarData())
    $("#r5").text(aten.pegarHorarioInicio())
    $("#r6").text(aten.pegarHorarioFim())
    $("#r7").text(aten.pegarDuracao())
    $("#r8").text(aten.pegarMateria())
    $("#r9").text(aten.pegarSala())

    $("#r10").text(aten.pegarTipo())
    $("#r11").text(aten.pegarEstado())
    if (aten.pegarEstado(true) == "NAO") {
        $("#r11").addClass("alert-warning")
    }
    if (aten.pegarEstado(true) == "CON") {
        $("#r11").addClass("alert-success")
    }
    if (aten.pegarEstado(true) == "CAN") {
        $("#r11").addClass("alert-danger")
    }
    $("#r12").text(aten.pegarCampus())

    if (aten.pegarLimite() == "SEM_LIMITE") {
        $("#r13").addClass("alert-info")
    } else {
        $("#r13").removeClass("alert banner")
        $("#r13").text(aten.pegarLimite() + " alunos")
    }
    $("#r14").text(aten.pegarTotalAlunos() + " alunos")
    $("#r15").text(aten.pegarTotalAlunosConfirmados() + " alunos")
    $("#r16").text(aten.pegarPorConfirmados() + "%")

    $("#r17").text(aten.pegarDataAgendamento())
    $("#r18").text(aten.pegarDataUltimaModificacao())
    atendimento.show()
    initMaterialize()
}

function initMaterialize() {
    $(document).ready(function () {
        $("ul.tabs").tabs();
    });
    if (getTipo() == "ALU") {
        $("#inscreverAtendimentoBotao").show()
        $("#confirmarPresencaBotao").show()
        $("#cancelarInscricao").show()
    }
    if (getTipo() == "DOC" || getTipo() == "MON") {
        $("#editarBotao").show()
        $("#confirmarBotat").show()
        $("#cancelarAtendimentoBotao").show()
    }
}

/* Lista de presença */

function carregarLista() {
    jsonAlunos = aten.pegarUsuarios()
    Object.keys(jsonAlunos).forEach(function (id) {
        aluno = jsonAlunos[id]
        nome = aluno["nomeCompleto"]
        presente = aluno["presente"]
        renderizarLinha(id, nome, presente)
    });
}

nLinhas = 1

function renderizarLinha(id, nome, presente) {
    pr = ""
    if (presente == "SIM") {
        pr = 'checked"'
        arrayPresentes.push(nLinhas)
    }
    a =
        "<tr class=\"hover\")>\
        <td class=\"tdbBox\">\
            <input type=\"checkbox\" class=\"filled-in\" id=\"box-" + nLinhas + "\" " + pr + "/>\
            <label class=\"checkBoxLabel\" for=\"box-" + nLinhas + "\"></label>\
        </td>\
        <td onclick=\"selecionarLinhaAluno(" + nLinhas + ")\">\
           <a class=\"linkTabela\" href=\"javascript:void(0)\">\
                <div class=\"corpoNome\">\
                    <img class=\"circle circle-imagem\" src=\"../back-end/request.php?pegarFoto&id=" + id + "\" width=\"50\" height=\"50\">\
                    <div ondblclick=\"usuarioShow('" + id + "')\" class=\"nomeLabel\">" + nome + "</div>\
                </div>\
           </a>\
        </td>\
    </tr>\
    "
    nLinhas++
    $("#tabelaLista").append(a)
}

function selecionarLinhaAluno(linha) {
    $("#box-" + linha).click()
}

/* Requisições */

function editarAtendimentoCon() {
    
}

function confirmarAtendimentoCon() {
    Swal.fire({
        title: "Deseja confirmar este atendimento?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "Não",
        confirmButtonText: "Sim"
    }).then((result) => {
        if (result.value) {
            
        }
    })
}

function cancelarAtendimentoCon() {
    Swal.fire({
        title: "Deseja cancelar este atendimento?",
        html: "Ele ainda poderá ser visto por todos, e será apagado após 24 horas<br><br>Esta ação é irreversível!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "Não",
        confirmButtonText: "Sim"
    }).then((result) => {
        if (result.value) {
            
        }
    })
}

function inscreverAtendimentoCon() {

}

function confirmarPresencaCon() {

}

function cancelarInscricaoCon() {

}