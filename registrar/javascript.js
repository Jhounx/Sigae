function init() {
    $('#campoChave').mask('000-000-000-00');
}

// function enviarRecuperacao() {
//     $("#sucessoEmail").hide()
//     $("#erroEmail").hide()
//     $("#waitingEmail").show()
//     setTimeout(function () {
//         var email = $('#recuperarEmail').val();
//         if (email == "pedrocmota1@hotmail.com") {
//             $("#sucessoEmail").show()
//             $("#erroEmail").hide()
//             $("#waitingEmail").hide()
//         } else {
//             $("#sucessoEmail").hide()
//             $("#erroEmail").show()
//             $("#waitingEmail").hide()
//         }
//     }, 1000);
// }

function verificarChave() {
    // $(".waiting").show()
    irParte4()
}

function irParte1() {
    $(".parte1").show()
    $(".parte2").hide()
    $(".parte3").hide()
    $("#partes").text("Parte 1")
}

function irParte2() {
    $(".parte1").hide()
    $(".parte2").show()
    $(".parte3").hide()
    $(".parte4").hide()
    $("#partes").text("Parte 2")
    resizer()
}

function irParte3() {
    $(".parte1").hide()
    $(".parte2").hide()
    $(".parte3").show()
    $(".parte4").hide()
    $("#partes").text("Parte 3")
    resizer()
}

function irParte3() {
    $(".parte1").hide()
    $(".parte2").hide()
    $(".parte3").show()
    $(".parte4").hide()
    $("#partes").text("Parte 4")
}

function irParte4() {
    $(".parte1").hide()
    $(".parte2").hide()
    $(".parte3").hide()
    $(".parte4").show()
    $("#partes").text("Parte 4")
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