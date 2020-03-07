function validarDados() {
    eventosCampos()
    erro = false;
    if (validarSelect() == true) { erro = true }
    if (validarEmail() == true) { erro = true }
    if (compararSenhas() == true) { erro = true }
    if (validarSenha1() == true) { erro = true }
    if (validarSenha2() == true) { erro = true }
}

function validarSelect() {
    $(".select-dropdown").removeAttr("style");
    $("#erro1").text("")
    if ($(".selectNome option:selected").index() == 0) {
        $("#erro1").text("Selecione uma opção")
        $(".select-dropdown").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        return true;
    }
}

function validarEmail() {
    $("#email").removeAttr("style");
    $("#erro2").text("")
    var patternEmail = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
    if (patternEmail.test($("#email").val()) == false) {
        $("#erro2").text("Digite uma e-mail válido")
        $("#email").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        return true;
    }
}

function validarSenha1() {
    $("#erro3").text("")
    $("#senha1").removeAttr("style");
    compararSenhas("#senha1")
    if ($("#senha1").val().length < 6) {
        $("#erro3").text("A senha deve conter mais de 6 caracteres")
        $("#senha1").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        return true;
    }
    if ($("#senha1").val().length > 30) {
        $("#erro3").text("A senha deve conter menos de 30 caracteres")
        $("#senha1").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        return true;
    }
}

function validarSenha2() {
    $("#senha2").removeAttr("style");
    $("#erro4").text("")
    compararSenhas("#senha2")
    if ($("#senha2").val().length < 6) {
        $("#erro4").text("A senha deve conter mais de 6 caracteres")
        $("#senha2").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        return true;
    }
    if ($("#senha2").val().length > 30) {
        $("#erro4").text("A senha deve conter menos de 30 caracteres")
        $("#senha2").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        return true;
    }
}

function compararSenhas() {
    if($("#senha1").val().length > 0) {
        $("#senha1").removeAttr("style");
        $("#erro3").text("")
    }
    if($("#senha2").val().length > 0) {
        $("#senha2").removeAttr("style");
        $("#erro4").text("")
    }
    if ($("#senha1").val() != $("#senha2").val()) {
        $("#erro3").text("Ambas as senhas devem ser iguais")
        $("#erro4").text("Ambas as senhas devem ser iguais")
        $("#senha1").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        $("#senha2").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        return true;
    }
}

function eventosCampos() {
    $(".selectNome").on("change", function () {
        validarSelect()
    });
}

function perdeuFocus(comp) {
    if(comp == "#email") {
        validarEmail()
    }
    if(comp == "#senha1") {
        validarSenha1()
    }
    if(comp == "#senha2") {
        validarSenha2()
    }
}