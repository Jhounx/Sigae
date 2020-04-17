function init_usuario() {

}

function ajaxUsuario(id) {
    request = new Request()
    request.add("pegarDadoUsuario", "")
    request.add("id", getID())
    request.add("pedido", id)
    request.add("turma", getTurma())
    request.send("GET", ["JSON"], (resultado) => {
        resposta = resultado.resposta;
        erro = resultado.erro;
        if (resposta != null) {
            if (JSON.stringify(resposta) != "{}") {
                var nome = resposta["nomePreferencia"];
                var matricula = resposta["matricula"];
                var tipo = resposta["tipo"];
                var curso = resposta["curso"];
                var turma = resposta["turma"];
                var disciplinas = getDisciplinasNome(resposta["disciplinas"])
                var campus = resposta["campus"];

                if (tipo == "ALU") {
                    $("#Plinha5").hide()
                    $("#perg3").text(curso)
                    $("#perg4").text(turma)
                }
                if (tipo == "DOC" || tipo == "MON") {
                    $("#Plinha3").hide()
                    $("#Plinha4").hide()
                    $("#perg5").text(disciplinas.join(", "))
                }
                if (tipo == "ADM") {
                    $("#Plinha3").hide()
                    $("#Plinha4").hide()
                    $("#Plinha5").hide()
                }

                $("#perg1").text(nome)
                $("#perg2").text(matricula)
                $("#perg6").text(campus)


                $(".tituloPopup").text(nome)
                $(".imagemUsuario").attr("src", "../back-end/request.php?pegarFoto&id=" + id);
                $('.imagemUsuario').materialbox();

                $(".waitingUsuario").hide()
                $(".conteudoUsuario").css("display", "flex")
            } else {
                acionarErro("Requisição negada")
                usuario.close()
            }
        } else {
            alert(erro)
            acionarErro("Requisição negada")
        }
    });
}