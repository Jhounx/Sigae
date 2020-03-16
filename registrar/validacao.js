function validarDados() {
    eventosCampos()
    erro = false;
    if (validarSelect() == true) { erro = true }
    if (validarEmail() == true) { erro = true }
    if (compararSenhas() == true) { erro = true }
    if (validarSenha1() == true) { erro = true }
    if (validarSenha2() == true) { erro = true }
    return erro;
}

function validarSelect() {
    $(".selectNome input").removeAttr("style");
    var erro = false;

    if ($(".selectNome option:selected").index() == 0) {
        $("#erro1").text("Selecione uma opção")
        $(".selectNome input").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        erro = true;
    } else {
        $("#erro1").text("")
    }

    if (jsonDados["tipo"] == "ALU") {
        $(".selectTurma input").removeAttr("style");
        if ($("#selectTurma").val() == null) {
            $("#erro2").text("Selecione uma opção")
            $(".selectTurma input").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
            erro = true;
        } else {
            $("#erro2").text("")
        }
    }
    if (jsonDados["tipo"] == "DOC") {
        $(".selectDisciplinas input").removeAttr("style");
        var numero = 0;
        $(".selectDisciplinas option:selected").each(function () {
            numero++;
        });
        if (numero <= 0) {
            $("#erro3").text("Selecione uma opção")
            $(".selectDisciplinas input").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
            erro = true;
        } else {
            $("#erro3").text("")
        }
    }
    if (erro == true) {
        return true;
    }
}

function validarEmail() {
    $("#email").removeAttr("style");
    $("#erro4").text("")
    var patternEmail = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
    if (patternEmail.test($("#email").val()) == false) {
        $("#erro4").text("Digite uma e-mail válido")
        $("#email").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        return true;
    }
}

function validarSenha1() {
    $("#erro5").text("")
    $("#senha1").removeAttr("style");
    compararSenhas("#senha1")
    if ($("#senha1").val().length < 6) {
        $("#erro5").text("A senha deve conter mais de 6 caracteres")
        $("#senha1").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        return true;
    }
    if ($("#senha1").val().length > 30) {
        $("#erro5").text("A senha deve conter menos de 30 caracteres")
        $("#senha1").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        return true;
    }
}

function validarSenha2() {
    $("#senha2").removeAttr("style");
    $("#erro6").text("")
    compararSenhas("#senha2")
    if ($("#senha2").val().length < 6) {
        $("#erro6").text("A senha deve conter mais de 6 caracteres")
        $("#senha2").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        return true;
    }
    if ($("#senha2").val().length > 30) {
        $("#erro6").text("A senha deve conter menos de 30 caracteres")
        $("#senha2").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        return true;
    }
}

function compararSenhas() {
    if ($("#senha1").val().length > 0) {
        $("#senha1").removeAttr("style");
        $("#erro5").text("")
    }
    if ($("#senha2").val().length > 0) {
        $("#senha2").removeAttr("style");
        $("#erro6").text("")
    }
    if ($("#senha1").val() != $("#senha2").val()) {
        $("#erro5").text("Ambas as senhas devem ser iguais")
        $("#erro6").text("Ambas as senhas devem ser iguais")
        $("#senha1").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        $("#senha2").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        return true;
    }
}

function eventosCampos() {
    $(".selectNome").on("change", function () {
        validarSelect()
    });
    $(".selectTurma").on("change", function () {
        validarSelect()
    });
    $(".selectDisciplinas").on("change", function () {
        validarSelect()
    });
}

function perdeuFocus(comp) {
    if (comp == "#email") {
        validarEmail()
    }
    if (comp == "#senha1") {
        validarSenha1()
    }
    if (comp == "#senha2") {
        validarSenha2()
    }
    if (comp == "#selectDisciplinas") {
        validarMaterias()
    }
}

function erroCampoCodigo() {
    $("#campoChave").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;color: #F44336!important");
    $("body > div.tudo > div.parte1.parte > div > div > div.input-field.inputChave > label").css("cssText", "color: #F44336!important");
}

function removerErroCodigo() {
    $("#campoChave, body > div.tudo > div.parte1.parte > div > div > div.input-field.inputChave > label").removeAttr("style");
    $("#erro").hide(500)
}