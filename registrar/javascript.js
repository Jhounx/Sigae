function init() {
    eventos()
    definirVersao();
    if (paramExist("id")) {
        parametro = getParam("id")
        $("#campoChave").val(parametro).trigger("input")
        Materialize.updateTextFields();
    }
}

function eventos() {
    $("#campoChave").mask("000-000-000-00");
    $("#campoChave").focus(function () {
        removerErroCodigo()
    });
    $("#campoChave").on("input", function () {
        if ($("#campoChave").is(":focus") && $(this).val().length >= 14) {
            testarKey()
        }
    });
    $(function () {
        var width = $(window).width();
        var height = $(window).height();
        if (width <= 730 || height <= 528) {
            var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
            $("html, body").css({ "height": h });
        }
    });
}

/* Tela de loading */
function carregamento() {
    $(".divCarregamento").fadeIn(500);
    $(".sistema").fadeOut(1800);
}

function sairCarregamento() {
    $(".divCarregamento").fadeOut(500);
    $(".sistema").fadeIn(1800);
}

function irParte2() {
    $("#botaoConfirmar1").hide()
    $("#botaoConfirmar2").hide()
    carregarDadosParte2()
    removeAllParans()
}

function irParte3() {
    $("#botaoConfirmar1").hide()
    $("#botaoConfirmar2").hide()
    $(".parte1").hide()
    $(".parte2").hide()
    $(".parte3").show()
    $(".topo").hide()
    verificarConclusao();
}

function mostrarErroParte1() {
    $("#erro").show()
    $("#waiting").hide()
    $(".btnChave").removeClass("disabled")
}

/* AJAX */
var jsonDados;

function testarKey() {
    chave = $("#campoChave").val().replace(/-/g, "")
    if (chave == "") {
        erroCampoCodigo()
        return
    }
    $("#erro").hide()
    $(".btnChave").addClass("disabled")
    $("#waiting").show()
    request = new Request()
    request.add("validarKey", "")
    request.add("codigo", chave)
    request.send("GET", ["JSON"], (resposta) => {
        if (JSON.stringify(resposta) == "{}") {
            mostrarErroParte1()
            $("#erroTexto").text("Esse código não é válido")
        } else {
            jsonDados = resposta
            if (jsonDados["estado"] == "ATV" || jsonDados["estado"] == "INA") {
                mostrarErroParte1()
                $("#erroTexto").text("Esse usuário já foi cadastrado")
            }
            if (jsonDados["estado"] == "REG") {
                irParte3()
                $(".btnChave").removeClass("disabled")
            }
            if (jsonDados["estado"] == "NUL") {
                irParte2()
            }
        }
    }, (erro) => {
        dispararErro("Erro interno no sistema. Requisição negada")
        $("#waiting").hide()
        $(".btnChave").removeClass("disabled")
        alert(erro)
    });
}

function carregarDadosParte2() {
    request = new Request()
    request.add("dadosEssenciais", "")
    request.add("tipo", jsonDados["tipo"])
    request.send("GET", ["JSON"], (resposta) => {
        if (jsonDados["tipo"] == "ALU") {
            var i = 1;
            for (var cursos in resposta) {
                $(".selectTurma").append("<optgroup label=\"" + cursos + "\" id=\"opcao" + i + "\"></optgroup>")
                var jsonCurso = resposta[cursos]
                for (turmas in jsonCurso) {
                    $("#opcao" + i).append("<option>" + jsonCurso[turmas] + "</option>")
                }
                i++;
            }
            $(".selectTurma").selectpicker({
                liveSearchPlaceholder: "Pesquisa rápida",
                noneResultsText: "Nada foi encontrado",
                noneSelectedText: "Escolha uma opção"
            }, "refresh");
            $(".linhaTurma").show()
        }
        if (jsonDados["tipo"] == "DOC" || jsonDados["tipo"] == "MON") {
            jsonDados["disci"] = resposta;
            for (var disci in resposta) {
                $(".selectDisciplinas").append("<option>" + resposta[disci] + "</option>")
            }
            $(".selectDisciplinas").selectpicker({
                liveSearchPlaceholder: "Pesquisa rápida",
                noneResultsText: "Nada foi encontrado",
                noneSelectedText: "Escolha pelo menos uma opção",
            }, "refresh");
            $(".linhaDisciplina").show()
        }

        arrayNomes = nomes(jsonDados["nome"])
        for (i = 0; i < arrayNomes.length; i++) {
            var name = arrayNomes[i];
            $("select.selectNome").append("<option>" + name + "</option>")
        }
        $(".selectNome").selectpicker("refresh");

        $("#td1").text(jsonDados["nome"])
        $("#td2").text(jsonDados["matricula"])
        $("#td3").text(jsonDados["curso"])
        $("#td4").text(jsonDados["campus"])
        if (jsonDados["tipo"] == "ALU") {
            $(".tipoParte2").text("Aluno")
        }
        if (jsonDados["tipo"] == "DOC") {
            $(".tipoParte2").text("Docente")
            $(".tr3").hide()
        }
        if (jsonDados["tipo"] == "MON") {
            $(".tipoParte2").text("Monitor")
            $(".tr3").hide()
        }
        if (jsonDados["tipo"] == "ADM") {
            $(".tipoParte2").text("Administrador")
            $(".tr3").hide()
        }
        $("#partes").text("Parte 2")
        $(".parte1").css("position","absolute")
        $(".parte1").hide()
        $(".parte2").fadeIn(1000)
        initValidacao()
    }, (erro) => {
        alert(erro)
        dispararErro("Requisição negada")
    });
}

function nomes(completo) {
    var nomesArray = completo.split(" ")
    nome = nomesArray[0]
    combinacoes = []
    for (var i = 1; i < nomesArray.length; i++) {
        combinacoes.push(nome + " " + nomesArray[i])
    }
    return combinacoes
}

function pegarIDdisciplina(disci) {
    Object.keys(jsonDisciplinas).forEach(function (node) {
        var valor = jsonDisciplinas[node];
        if (disci == valor) {
            return node + "";
        }
    });
}

function pegarDisciplinas() {
    r = $("#selectDisciplinas").val()
    r2 = []
    disciJson = jsonDados["disci"];
    for (i = 0; i < r.length; i++) {
        di = r[i]
        Object.keys(disciJson).forEach(function (node) {
            var valor = disciJson[node];
            if (di == valor) {
                r2.push(node)
            }
        });
    }
    return r2.join("-")
}

function inscreverUsuario() {
    Swal.fire({
        title: "Continuar inscrição?",
        text: "Verifique se seus dados estão corretos",
        type: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, tudo está certo",
        cancelButtonText: "Verificar novamente"
    }).then((result) => {
        if (result.value) {
            carregamento()
            request = new Request()
            request.add("registrarUsuario", "")
            request.add("nomePreferencial", $("select.selectNome").val())
            request.add("email", $("#email").val())
            request.add("senha", $("#senha1").val())
            if (jsonDados["tipo"] == "ALU") {
                request.add("turma", $("#selectTurma").val())
            } else {
                request.add("turma", "null")
            }
            if (jsonDados["tipo"] == "DOC" || jsonDados["tipo"] == "MON") {
                request.add("disciplinas", pegarDisciplinas())
            } else {
                request.add("disciplinas", "null")
            }
            request.send("GET", ["EML", "NOME", "TURMA", "DISCI", "SENHA", "ID", "{}", "ERROR"], (resposta) => {
                if (resposta == "EML") {
                    dispararErro("Esse email já foi usado anteriormente")
                    sairCarregamento()
                }
                if (resposta == "NOME") {
                    dispararErro("Erro na requisição: nome inválido")
                    sairCarregamento()
                }
                if (resposta == "TURMA") {
                    dispararErro("Erro na requisição: turma inválida")
                    sairCarregamento()
                }
                if (resposta == "DISCI") {
                    dispararErro("Erro na requisição: disciplina inválida")
                    sairCarregamento()
                }
                if (resposta == "SENHA") {
                    dispararErro("Erro na requisição: senha inválida")
                    sairCarregamento()
                }
                if (resposta == "ID") {
                    dispararErro("Erro na requisição: id inválido")
                    sairCarregamento()
                }
                if (resposta == "ERROR") {
                    dispararErro("Erro na requisição: erro grave")
                    sairCarregamento()
                }
                if (resposta == "{}") {
                    enviarEmailConfirmação()
                }
            }, (erro) => {
                dispararErro("Erro interno no sistema. Requisição negada")
                alert(erro)
            });
        }
    })
}

function enviarEmailConfirmação(novamente) {
    if (novamente == true) {
        $("#botaoReeenviar").addClass("disabled")
        $("#botaoCancelar").addClass("disabled")
    }
    request = new Request()
    request.add("enviarEmailValidacao", "")
    request.send("GET", ["OK", "INV", "EML"], (resposta) => {
        if (resposta == "OK") {
            if (novamente == true) {
                $("#botaoReeenviar").removeClass("disabled")
                $("#botaoCancelar").removeClass("disabled")
                dispararAlerta("E-mail enviado com sucesso!")
            } else {
                irParte3()
                sairCarregamento()
            }
        }
        if (resposta == "INV") {
            dispararErro("Erro na requisição: ID inválido")
            $("#botaoReeenviar").removeClass("disabled")
            $("#botaoCancelar").removeClass("disabled")
        }
        if (resposta == "EML") {
            dispararErro("Erro ao enviar o e-mail")
            $("#botaoReeenviar").removeClass("disabled")
            $("#botaoCancelar").removeClass("disabled")
        }

    }, (erro) => {
        dispararErro("Erro interno no sistema. Requisição negada")
        $("#botaoReeenviar").removeClass("disabled")
        $("#botaoCancelar").removeClass("disabled")
        alert(erro)
    });
}

function refazerInscrição() {
    Swal.fire({
        title: "Tem certeza?",
        text: "Sua inscrição será cancelada, mas você poderá refazê-la",
        type: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Refazer",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.value) {
            $("#botaoReeenviar").addClass("disabled")
            $("#botaoCancelar").addClass("disabled")
            request = new Request()
            request.add("cancelarInscricao", "")
            request.send("GET", ["OK", "INV"], (resposta) => {
                if (resposta == "OK") {
                    window.location.href = "./?id=" + jsonDados["codigo_acesso"];
                }
                if (resposta == "INV") {
                    dispararErro("Erro na requisição: ID inválido")
                    $("#botaoReeenviar").removeClass("disabled")
                    $("#botaoCancelar").removeClass("disabled")
                }
            }, (erro) => {
                dispararErro("Erro interno no sistema. Requisição negada")
                $("#botaoReeenviar").removeClass("disabled")
                $("#botaoCancelar").removeClass("disabled")
                alert(erro)
            });
        }
    })
}

function verificarConclusao() {
    window.onfocus = function () {
        request = new Request()
        request.add("registroAcabou", "")
        request.send("GET", ["SIM", "NAO"], (resposta) => {
            if (resposta != null) {
                if (resposta == "SIM") {
                    window.location.href = "../../?reg=true";
                }
            } else {
                this.alert(resposta)
                dispararErro("Erro interno no sistema")
            }
        });
    };
}

function dispararErro(texto) {
    var toast = $("<i class=\"material-icons toast-erro\">cancel</i><span class=\"toastTexto\">" + texto + "</span>");
    Materialize.toast(toast, 5000);
}

function dispararAlerta(texto) {
    var toast = $("<i class=\"material-icons toast-ok\">thumb_up_alt</i><span class=\"toastTexto\">" + texto + "</span>");
    Materialize.toast(toast, 5000);
}