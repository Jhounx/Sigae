function init() {
    $("#campoChave").mask("000-000-000-00");
    $("#campoChave").focus(function () {
        removerErroCodigo()
    });
    $("#campoChave").on("input", function () {
        if ($("#campoChave").is(":focus") && $(this).val().length >= 14) {
            verificarChave()
        }
    });
    $codigoParam = get_parametro("id");
    if ($codigoParam != undefined) {
        $("#campoChave").val($codigoParam).trigger("input")
    }
    Materialize.updateTextFields();
    definirVersao();
}

function carregamento() {
    $(".divCarregamento").fadeIn(500);
    $(".sistema").fadeOut(1800);
}

function sairCarregamento() {
    $(".divCarregamento").fadeOut(500);
    $(".sistema").fadeIn(1800);
}

function verificarChave() {
    valor = $("#campoChave").val()
    if (valor == "") {
        erroCampoCodigo()
    } else {
        testarKey()
    }
}

function irParte1() {
    $(".parte1").show()
    $(".parte2").hide()
    $(".parte3").hide()
    $("#partes").text("Parte 1")
}

function irParte2() {
    $("#botaoConfirmar1").hide()
    $("#botaoConfirmar2").hide()
    ajaxDadosParte2()
    window.history.pushState("", "", "./");
}

function irParte3() {
    $("#botaoConfirmar1").hide()
    $("#botaoConfirmar2").hide()
    $(".parte1").hide()
    $(".parte2").hide()
    $(".parte3").show()
    $(".topo").hide()
    window.history.pushState("", "", "./");
    verificarSempreVerificado();
}

/* AJAX */

var jsonDados, jsonTurmas, jsonDisciplinas;

function testarKey() {
    chave = $("#campoChave").val().replace(/-/g, "")
    request = new Request()
    request.add("validarKey", "")
    request.add("codigo", chave)

    $("#erro").hide()
    $(".btnChave").addClass("disabled")
    $("#waiting").show()

    request.send("GET", ["JSON"], (resultado) => {
        resposta = resultado.resposta;
        erro = resultado.erro;
        if (resposta != null) {
            if (JSON.stringify(resposta) == "{}") {
                $("#erro").show()
                $("#erroTexto").text("Esse código não é válido")
                $("#waiting").hide()
                $(".btnChave").removeClass("disabled")
            } else {
                jsonDados = resposta
                if (resposta == undefined) {
                    dispararErro("Erro na requisição");
                    $("#waiting").hide()
                    $(".btnChave").removeClass("disabled")
                }
                if (jsonDados["estado"] == "ATV" || jsonDados["estado"] == "INA") {
                    $("#erro").show()
                    $("#erroTexto").text("Esse usuário já foi cadastrado")
                    $("#waiting").hide()
                    $(".btnChave").removeClass("disabled")
                }
                if (jsonDados["estado"] == "REG") {
                    irParte3()
                    $(".btnChave").removeClass("disabled")
                }
                if (jsonDados["estado"] == "NUL") {
                    irParte2()
                }
            }
        } else {
            dispararErro("Erro interno no sistema. Requisição negada")
            $("#waiting").hide()
            $(".btnChave").removeClass("disabled")
            alert(erro)
        }
    });
}

function ajaxDadosParte2() {
    id = jsonDados["id"]
    if (jsonDados["tipo"] == "ALU") {
        request = new Request()
        request.add("id", id)
        request.add("getTurmasByCurso", "")
        request.add("curso", jsonDados["cursoID"])
        request.add("campus", jsonDados["campusID"])
        request.send("GET", ["JSON"], (resultado) => {
            resposta = resultado.resposta;
            erro = resultado.erro;
            if (resposta != null) {
                if (JSON.stringify(resposta) == "{}") {
                    $("#waiting").hide()
                    dispararErro("Requisição negada")
                } else {
                    jsonTurmas = resposta
                    $(".linhaTurma").removeAttr("style")
                    var i = 1;
                    for (var cursos in jsonTurmas) {
                        $(".selectTurma").append("<optgroup label=\"" + cursos + "\" id=\"opcao" + i + "\"></optgroup>")
                        var jsonCurso = jsonTurmas[cursos]
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
                    renderizarParte2()
                }
            } else {
                $("#waiting").hide()
                dispararErro("Erro interno no sistema. Requisição negada")
                alert(erro)
            }
        });
    }
    if (jsonDados["tipo"] == "DOC" || jsonDados["tipo"] == "MON") {
        request = new Request()
        request.add("id", id)
        request.add("getDisciplinas", "")
        request.send("GET", ["JSON"], (resultado) => {
            $("#waitingParte2").hide()
            resposta = resultado.resposta;
            erro = resultado.erro;
            if (resposta != null) {
                if (JSON.stringify(resposta) == "{}") {
                    $("#waiting").hide()
                    dispararErro("Requisição negada")
                } else {
                    jsonDisciplinas = resposta
                    $(".linhaDisciplina").removeAttr("style")
                    for (var disci in jsonDisciplinas) {
                        $(".selectDisciplinas").append("<option>" + jsonDisciplinas[disci] + "</option>")
                    }
                    $(".selectDisciplinas").selectpicker({
                        liveSearchPlaceholder: "Pesquisa rápida",
                        noneResultsText: "Nada foi encontrado",
                        noneSelectedText: "Escolha pelo menos uma opção"
                    }, "refresh");
                    renderizarParte2()
                }
            } else {
                $("#waiting").hide()
                dispararErro("Erro interno no sistema. Requisição negada")
                alert(erro)
            }
        });
    }
    if (jsonDados["tipo"] == "ADM") {
        renderizarParte2()
    }
}

function renderizarParte2() {
    arrayNomes = nomes(jsonDados["nome"])
    for (i = 0; i < arrayNomes.length; i++) {
        var name = arrayNomes[i];
        $("select.selectNome").append("<option>" + name + "</option>")
    }
    $(".selectNome").selectpicker("refresh");

    $("#resp1").text(jsonDados["nome"])
    $("#resp2").text(jsonDados["matricula"])
    $("#resp3").text(jsonDados["curso"])
    $("#resp4").text(jsonDados["campus"])
    if (jsonDados["tipo"] == "ALU") {
        $(".tipoParte2").text("Aluno")
    }
    if (jsonDados["tipo"] == "DOC") {
        $(".tipoParte2").text("Docente")
        $("#perg3").hide()
    }
    if (jsonDados["tipo"] == "MON") {
        $(".tipoParte2").text("Monitor")
        $("#perg3").hide()
    }
    if (jsonDados["tipo"] == "ADM") {
        $(".tipoParte2").text("Administrador")
        $("#perg3").hide()
    }
    $("#waiting").hide()
    $("#botaoConfirmar1").show()
    $("#botaoConfirmar2").show()
    $("#partes").text("Parte 2")
    $(".parte1").fadeOut()
    setTimeout(function () {
        $(".parte2").fadeIn(1000)
        $(".parte3").hide()
        $(".btnChave").removeClass("disabled")
    }, 300);
    setTimeout(function () {
        resizer()
        initValidacao()
    }, 500);
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
    for (i = 0; i < r.length; i++) {
        di = r[i]
        Object.keys(jsonDisciplinas).forEach(function (node) {
            var valor = jsonDisciplinas[node];
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
            request.add("id", jsonDados["id"])
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
            request.send("GET", ["EML", "NOME", "TURMA", "DISCI", "SENHA", "ID", "{}", "ERROR"], (resultado) => {
                resposta = resultado.resposta;
                erro = resultado.erro;
                if (resposta != null) {
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
                } else {
                    dispararErro("Erro interno no sistema. Requisição negada")
                    alert(erro)
                }
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
    request.add("id", jsonDados["id"])
    request.add("enviarEmailValidacao", "")
    request.send("GET", ["OK", "INV", "EML"], (resultado) => {
        resposta = resultado.resposta;
        erro = resultado.erro;
        if (resposta != null) {
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
        } else {
            dispararErro("Erro interno no sistema. Requisição negada")
            $("#botaoReeenviar").removeClass("disabled")
            $("#botaoCancelar").removeClass("disabled")
            alert(erro)
        }
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
            request.add("id", jsonDados["id"])
            request.add("cancelarInscricao", "")
            request.send("GET", ["OK", "INV"], (resultado) => {
                resposta = resultado.resposta;
                erro = resultado.erro;
                if (resposta != null) {
                    if (resposta == "OK") {
                        window.location.href = "./?id=" + jsonDados["key"];
                    }
                    if (resposta == "INV") {
                        dispararErro("Erro na requisição: ID inválido")
                        $("#botaoReeenviar").removeClass("disabled")
                        $("#botaoCancelar").removeClass("disabled")
                    }
                } else {
                    dispararErro("Erro interno no sistema. Requisição negada")
                    $("#botaoReeenviar").removeClass("disabled")
                    $("#botaoCancelar").removeClass("disabled")
                    alert(erro)
                }
            });
        }
    })
}

function verificarSempreVerificado() {
    window.onfocus = function () {
        request = new Request()
        request.add("id", jsonDados["id"])
        request.add("registroAcabou", "")
        request.send("GET", ["SIM", "NAO"], (resultado) => {
            resposta = resultado.resposta;
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