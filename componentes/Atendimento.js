class Atendimento {

    constructor(json) {
        this.json = json
    }

    /* Pegar dados */
    pegarNome() {
        return this.json["nome"]
    }

    pegarDescricao() {
        return this.json["descricao"]
    }

    pegarDocente() {
        return this.json["docente"]
    }

    pegarData() {
        return this.json["data"]
    }

    pegarHorarioInicio() {
        return this.json["horarioInicio"]
    }

    pegarHorarioFim() {
        return this.json["horarioFim"]
    }

    pegarDuracao() {
        return diferencaHorario(this.pegarHorarioInicio(), this.pegarHorarioFim())
    }

    pegarMateria() {
        if(Object.keys(this.json["materia"]).length) {
            return this.json["materia"][0]
        } else {
            return this.json["materia"]
        }
    }

    pegarSala() {
        return this.json["sala"]
    }

    pegarTipo(tag) {
        if (tag) {
            return this.json["tipo"]
        } else {
            if (this.json["tipo"] == "ATE") {
                return "Atendimento convencional"
            }
            if (this.json["tipo"] == "MON") {
                return "Monitoria"
            }
            if (this.json["tipo"] == "EXT") {
                return "Aula extra"
            }
        }
    }

    pegarEstado(tag) {
        if (tag) {
            return this.json["estado"]
        } else {
            if (this.json["estado"] == "NAO") {
                return "Sem confirmação do docente"
            }
            if (this.json["estado"] == "CON") {
                return "Confirmado pelo docente"
            }
            if (this.json["estado"] == "FIN") {
                return "Já realizado"
            }
            if (this.json["estado"] == "CAN") {
                return "Cancelado pelo docente"
            }
        }
    }

    pegarCampus() {
        return this.json["campus"]
    }

    pegarLimite() {
        if(this.json["limite"] == "") {
            return -1;
        } else {
            return this.json["limite"]
        }
    }

    pegarTotalAlunos() {
        var i = 0
        Object.keys(this.json["aluno"]).forEach(function(id) {
            i++
        });
        return i
    }

    pegarTotalAlunosConfirmados() {
        var i = 0,
            json = this.json
        Object.keys(json["aluno"]).forEach(function(id) {
            if (json["aluno"][id]["confirmado"] == "SIM") {
                i++
            }
        });
        return i
    }

    pegarPorConfirmados() {
        var total = this.pegarTotalAlunos(),
            conf = this.pegarTotalAlunosConfirmados()
        if (total == 0 || conf == 0) {
            return 0
        }
        var por = conf * 100 / total
        return por.toFixed(1)
    }

    pegarDataAgendamento() {
        return this.json["dataAgendamento"]
    }

    pegarDataUltimaModificacao() {
        return this.json["ultimaModificacao"]
    }

    pegarUsuarios() {
        return this.json["aluno"]
    }

    /*###############################*/
    /*     Envio de requisições      */
    /*###############################*/

    agendarAtendimento(retorno) {
        request = new Request()
        request.add("agendarAtendimento", "")
        request.add("nome", this.pegarNome())
        request.add("descricao", this.pegarDescricao())
        request.add("data", this.pegarData())
        request.add("horarioInicio", this.pegarHorarioInicio())
        request.add("horarioFim", this.pegarHorarioFim())
        request.add("sala", this.pegarSala())
        request.add("materia", this.pegarMateria())
        request.add("tipo", this.pegarTipo(true))
        request.add("limite", this.pegarLimite())
        request.send("GET", ["JSON"], (resposta) => {
            retorno(resposta)  
        }, (erro) => {
            console.log(erro)
            alert(erro)
            acionarErro("Requisição negada")
        })
    }
}

function verificarDisponibilidade(retorno) {
    request = new Request()
    request.add("verificarConflitosAtendimento", "")
    request.add("data", $(".datepicker").val())
    request.add("inicio", $(".inicioPicker").val())
    request.add("fim", $(".fimPicker").val())
    request.add("sala", $(".selectSalas option:selected").val())
    request.send("GET", ["JSON"], (resposta) => {
        retorno(resposta)
    }, (erro) => {
        alert(erro)
        acionarErro("Requisição negada")
    })

}