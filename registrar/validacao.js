function initValidacao() {
    eventosCampos()
    fazerValidacao()
}

function validarEmail() {
    $("#email").removeAttr("style");
    var patternEmail = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
    if (patternEmail.test($("#email").val())) {
        return true;
    } else {
        if ($("#email").val().length > 0) {
            $("#email").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        }
        return false;
    }
}

function eventosCampos() {
    $("#email").on("input", function () {
        fazerValidacao()
    });
    $("#email").focusout(function () {
        if ($("#email").val().length > 0) {
            if(validarEmail()) {
                $("#erro1").text("")
            } else {
                $("#erro1").text("Digite uma e-mail válido")
            } 
        }
    })
    $("#senha1").on("input", function () {
        nota = notaDaSenha($("#senha1").val())
        definirNota(nota)
        if (senhaValida(nota)) {
            $("#senha1").removeAttr("style")
        } else {
            $("#senha1").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        }
        if($("#senha2").val().length > 0) {
            if (senhaIguais()) {
                $("#senha1").removeAttr("style")
                $("#erro2").text("")
            } else {
                $("#senha1").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
                $("#erro2").text("As duas senhas não coincidem")
            }
        }
        fazerValidacao()
    });
    $("#senha2").on("input", function () {
        if (senhaValida(nota) && senhaIguais()) {
            $("#erro2").text("")
            $("#senha2").removeAttr("style")
        } else {
            $("#senha2").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        }
        fazerValidacao()
    });
    $("#senha2").focusout(function () {
        if (senhaIguais()) {
            $("#erro2").text("")
        } else {
            $("#erro2").text("As duas senhas não coincidem")
        }
    })

    $(".selectNome").on("change", function () {
        fazerValidacao()
    });
    $(".selectTurma").on("change", function () {
        fazerValidacao()
    });
    $(".selectDisciplinas").on("change", function () {
        fazerValidacao()
    });
}

function fazerValidacao() {
    var valido = true;

    /* selects */
    if ($(".selectNome option:selected").index() == 0) {
        valido = false
    }
    if (jsonDados["tipo"] == "ALU") {
        $(".selectTurma input").removeAttr("style");
        if ($("#selectTurma").val() == null) {
            valido = false
        }
    }
    if (jsonDados["tipo"] == "DOC") {
        $(".selectDisciplinas input").removeAttr("style");
        l = $("#selectDisciplinas").val().length
        if (l == 0) {
            valido = false
        }
    }

    /* email */
    if (!validarEmail()) {
        valido = false
    } else {
        $("#erro1").text("")
    }

    /* senha */
    nota = notaDaSenha($("#senha1").val())
    if (!senhaValida(nota) || !senhaIguais()) {
        valido = false
    }

    if (valido == true) {
        $("#botaoFinalizar").removeAttr("disabled")
    } else {
        $("#botaoFinalizar").attr("disabled", true)
    }
}

function definirNota(nota) {
    if (nota == 0) {
        $("#força").text("Inválida")
        $("#força").css("cssText", "color:crismon");
        $("#senha1").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
    }
    if (nota == 20) {
        $("#força").text("Ridícula")
        $("#força").css("cssText", "color:crismon");
        $("#senha1").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
    }
    if (nota == 50) {
        $("#força").text("Fraca")
        $("#força").css("cssText", "color:rgb(220, 167, 20)");
        $("#senha1").removeAttr("style");
    }
    if (nota == 75) {
        $("#força").text("Forte")
        $("#força").css("cssText", "color:rgb(20, 220, 110)");
        $("#senha1").removeAttr("style");
    }
    if (nota == 100) {
        $("#força").text("Muito forte")
        $("#força").css("cssText", "color:forestgreen");
        $("#senha1").removeAttr("style");
    }
    $(".determinate").css("width", nota + "%")
}

function senhaValida(nota) {
    if (nota >= 50) {
        return true;
    } else {
        return false;
    }
}

function senhaIguais() {
    if ($("#senha1").val() == $("#senha2").val()) {
        return true;
    } else {
        return false;
    }
}

function ganhouFocus(a) {
    if (a == "#senha1" || a == "#senha2") {
        $(".popupShow1").show()
    }
}

function perdeuFocus(comp) {
    if (comp == "#email") {
        fazerValidacao()
    }
    if (comp == "#senha1") {
        $(".popupShow1").fadeOut(250)
    }
    if (comp == "#senha2") {
        $(".popupShow1").fadeOut(250)
    }
}

/* Parte 1 */

function erroCampoCodigo() {
    $("#campoChave").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;color: #F44336!important");
    $("body > div.tudo > div.parte1.parte > div > div > div.input-field.inputChave > label").css("cssText", "color: #F44336!important");
}

function removerErroCodigo() {
    $("#campoChave, body > div.tudo > div.parte1.parte > div > div > div.input-field.inputChave > label").removeAttr("style");
    $("#erro").hide(500)
}