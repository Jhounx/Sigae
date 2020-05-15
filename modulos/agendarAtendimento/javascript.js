var editando = false, idAtendimento, aten

function init_agendarAtendimento() {
    loadAtendimento()
    loadJsonBase()
    eventos()
}

function loadAtendimento() {
    if (paramExist("id")) {
        idAtendimento = getParam("id")
        $("#salvarDadosText").text("Salvar alterações")
        editando = true
        request = new Request()
        request.add("pegarAtendimentoByID", "")
        request.add("id", idAtendimento)
        request.add("requerIdentidade", "")
        request.send("GET", ["JSON"], (resposta) => {
            if (JSON.stringify(resposta) == "{}") {
                acionarErro("Esse atendimento não existe")
                irInicio()
            } else {
                dados = resposta[idAtendimento]
                aten = new Atendimento(dados)
                $("#nomeAtendimento").val(aten.pegarNome())
                $("#descAtendimento").val(aten.pegarDescricao())
                $(".datepicker").val(aten.pegarData())
                $(".inicioPicker").val(aten.pegarHorarioInicio())
                $(".fimPicker").val(aten.pegarHorarioFim())
                if (aten.pegarLimite() != "-1") {
                    $("#limiteInput").val(aten.pegarLimite())
                }
                $(".selectSalas").selectpicker("val", aten.pegarSala());
                $(".selectDisciplinas").selectpicker("val", aten.pegarMateria());
                $(".selectTipo").selectpicker("val", aten.pegarTipo());
                initMaterialize()
                Materialize.updateTextFields()
                getModulo("agendarAtendimento").show()
            }
        }, (erro) => {
            alert(erro)
            acionarErro("Requisição negada")
        })
    } else {
        $("#nomeAtendimento").val("Atendimento#" + randomString("1234567890", 5))
        initMaterialize()
        getModulo("agendarAtendimento").show()
    }
}

function loadJsonBase() {
    d = getTodasSalas()
    Object.keys(d).forEach(function (key) {
        value = d[key]
        $(".selectSalas").append("<option>" + value + "</option>")

    });
    $(".selectSalas").selectpicker({
        liveSearchPlaceholder: "Pesquisa rápida",
        noneResultsText: "Nada foi encontrado",
        noneSelectedText: "Selecione ao menos uma opção"
    });

    d = disciplinaIDtoNome(getDisciplinas())
    Object.keys(d).forEach(function (key) {
        value = d[key]
        $(".selectDisciplinas").append("<option>" + value + "</option>")

    });
    $(".selectDisciplinas").selectpicker({
        liveSearchPlaceholder: "Pesquisa rápida",
        noneResultsText: "Nada foi encontrado",
        noneSelectedText: "Selecione ao menos uma opção"
    });
}

function eventos() {
    $("#nomeAtendimento").keyup(function () {
        var t = $("#nomeAtendimento").val();
        if (t.length >= 5 && t.length <= 30) {
            $("#nomeAtendimento").removeAttr("style")
            $("#erro2").text("")
        } else {
            $("#nomeAtendimento").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        }
        validacaoGeral()
    });
    $("#nomeAtendimento").focusout(function () {
        var t = $("#nomeAtendimento").val();
        if (t.length < 5) {
            $("#erro1").text("Deve haver no mínimo 5 caracteres")
            $("#nomeAtendimento").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        } else {
            if (t.length > 30) {
                $("#erro1").text("Limite máximo de caracteres atingido")
                $("#nomeAtendimento").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
            } else {
                $("#erro1").text("")
                $("#nomeAtendimento").removeAttr("style")
            }
        }
        if (t.length == 0) {
            $("#erro1").text("")
        }
    })
    $("#descAtendimento").keyup(function () {
        var t = $("#descAtendimento").val();
        if (t.length >= 5 && t.length <= 60) {
            $("#descAtendimento").removeAttr("style")
            $("#erro2").text("")
        } else {
            $("#descAtendimento").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        }
        validacaoGeral()
    });
    $("#descAtendimento").focusout(function () {
        var t = $("#descAtendimento").val();
        if (t.length < 5) {
            $("#erro2").text("Deve haver no mínimo 5 caracteres")
            $("#descAtendimento").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        } else {
            if (t.length > 60) {
                $("#erro2").text("Limite máximo de caracteres atingido")
                $("#descAtendimento").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
            } else {
                $("#erro2").text("")
                $("#descAtendimento").removeAttr("style")
            }
        }
        if (t.length == 0) {
            $("#erro2").text("")
            $("#descAtendimento").removeAttr("style")
        }
        validacaoGeral()
    });
    /* data */
    $(".datepicker").change(function () {
        validacaoGeral()
    });
    /* horário inicio */
    $(".inicioPicker").change(function () {
        var t = $(".inicioPicker").val().replace(" ", "");
        if (t.length == 0 || !compararHorario($(".inicioPicker").val(), $(".fimPicker").val())) {
            $("#erro5").text("O horário de início não pode ser inferior ao de finalização")
            $(".fimPicker").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        } else {
            $("#erro5").text("")
            $(".fimPicker").removeAttr("style")
        }
        validacaoGeral()
    });
    /* horário fim */
    $(".fimPicker").change(function () {
        var t = $(".inicioPicker").val().replace(" ", "");
        if (t.length == 0 || !compararHorario($(".inicioPicker").val(), $(".fimPicker").val())) {
            $("#erro5").text("O horário de início não pode ser inferior ao de finalização")
            $(".fimPicker").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        } else {
            $("#erro5").text("")
            $(".fimPicker").removeAttr("style")
        }
        validacaoGeral()
    });
    /* select salas */
    $(".selectSalas").on("changed.bs.select", function () {
        if (this.value != undefined) {
            validacaoGeral()
        }
    });
    /* select disciplinas */
    $(".selectDisciplinas").on("changed.bs.select", function () {
        if (this.value != undefined) {
            validacaoGeral()
        }
    });
    /* select tipo */
    $(".selectTipo").on("changed.bs.select", function () {
        if (this.value != undefined) {
            validacaoGeral()
        }
    });
    $("#limiteInput").keyup(function () {
        if(parseInt($("#limiteInput").val()) < aten.pegarTotalAlunos()) {
            $("#erro6").text("O limite de inscritos não pode ser menor do que o número de inscritos atualmente (total de inscritos: " + aten.pegarTotalAlunos() + " alunos)")
            $("#limiteInput").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        } else {
            $("#erro6").text("")
            $("#limiteInput").removeAttr("style")
        }
        validacaoGeral()
    });
    $('#limiteInput').mask("000")
}

function initMaterialize() {
    if (editando == false) {
        dia = dataHoje()
    } else {
        dia = stringDateToDate(aten.pegarData())
    }
    $(document).ready(function () {
        $(".datepicker").pickadate({
            selectMonths: true,
            selectYears: 1,
            today: "Hoje",
            clear: "Limpar",
            close: "Fechar",
            done: 'Confirmar',
            min: dia,
            max: somarAno(null, 1),
            closeOnSelect: false,
            monthsFull: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthsShort: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            weekdaysFull: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabádo'],
            weekdaysShort: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabádo'],
            weekdaysLetter: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
            labelMonthNext: 'Próximo mês',
            labelMonthPrev: 'Mês anterior',
            labelMonthSelect: 'Selecione um mês',
            labelYearSelect: 'Selecione um ano',
            format: 'dd/mm/yyyy',
            onOpen: function () {
                $(".navbar").addClass("navAux")
            },
            onClose: function () {
                $(".navbar").removeClass("navAux")
                $('.datepicker').blur();
                $('.picker').blur();
            }
        });
        $('.datepicker').on('mousedown', function (event) {
            event.preventDefault();
        })

        $(".inicioPicker, .fimPicker").pickatime({
            default: "now",
            fromnow: 0,
            twelvehour: false,
            donetext: "OK",
            cleartext: "Limpar",
            canceltext: "Cancelar",
            autoclose: false,
            ampmclickable: false,
        });
        $(".inicioPicker, .fimPicker").on("mousedown", function (event) {
            event.preventDefault();
        })
        $("#nomeAtendimento, #descAtendimento").characterCounter();
        Materialize.updateTextFields()
    });
};

function validacaoGeral() {
    valido = true;
    var nomeAtendimento = $("#nomeAtendimento").val();
    if (nomeAtendimento.length < 5 || nomeAtendimento.length > 30) {
        valido = false;
    }
    var descAtendimento = $("#descAtendimento").val();

    if (descAtendimento.length != 0 && descAtendimento.length < 5 || descAtendimento.length > 60) {
        valido = false;
    }
    if ($(".datepicker").val().length == 0) {
        valido = false;
    }
    if ($(".inicioPicker").val().length == 0 || !compararHorario($(".inicioPicker").val(), $(".fimPicker").val())) {
        valido = false;
    }
    if ($(".fimPicker").val().length == 0 || !compararHorario($(".inicioPicker").val(), $(".fimPicker").val())) {
        valido = false;
    }

    if ($(".selectSalas option:selected").val() == undefined) {
        valido = false;
    }
    if ($(".selectDisciplinas option:selected").val() == undefined) {
        valido = false;
    }
    if(parseInt($("#limiteInput").val()) < aten.pegarTotalAlunos()) {
        valido = false;
    }
    if (editando == false) {
        if (valido == false) {
            $("#salvarDados").attr("disabled", true)
        } else {
            $("#salvarDados").removeAttr("disabled", true)
        }
    } else {
        if (valido == false) {
            $("#salvarDados").attr("disabled", true)
        } else {
            if (verificarMudanca()) {
                $("#salvarDados").attr("disabled", true)
            } else {
                $("#salvarDados").removeAttr("disabled", true)
            }
        }
    }
}

function verificarMudanca() {
    return $("#nomeAtendimento").val() == aten.pegarNome() &&
        $("#descAtendimento").val() == aten.pegarDescricao() &&
        $(".datepicker").val() == aten.pegarData() &&
        $(".inicioPicker").val() == aten.pegarHorarioInicio() &&
        $(".fimPicker").val() == aten.pegarHorarioFim() &&
        $(".selectSalas option:selected").val() == aten.pegarSala() &&
        $(".selectDisciplinas option:selected").val() == aten.pegarMateria() &&
        $(".selectTipo option:selected").val() == aten.pegarTipo() &&
        $("#limiteInput").val() == aten.pegarLimite(true)
}

function acaoBotao() {
    $("#salvarDados").attr("disabled", true)
    idAtendimentoEdicao = editando ? idAtendimento : null
    verificarDisponibilidade(function (json) {
        if (jsonVazio(json)) {
            aten = new Atendimento(JSON.parse(JSON.stringify(obj = {
                nome: $("#nomeAtendimento").val(),
                descricao: $("#descAtendimento").val(),
                data: $(".datepicker").val(),
                horarioInicio: $(".inicioPicker").val(),
                horarioFim: $(".fimPicker").val(),
                sala: $(".selectSalas option:selected").val(),
                materia: disciplinaNomeToID([$(".selectDisciplinas option:selected").val()]),
                tipo: tipoNomeToID($(".selectTipo option:selected").val()),
                limite: $("#limiteInput").val(),
            })))
            if (editando) {
                aten.alterarAtendimento(idAtendimento, function (resposta) {
                    if (Object.keys(resposta).length == 0) {
                        acionarErro("Modificação negada pelo servidor")
                    } else {
                        id = resposta["id"]
                        invoker_atendimento(id)
                        setParam("atendimentoModificado");
                    }
                    $("#salvarDados").removeAttr("disabled", true)
                })
            } else {
                aten.agendarAtendimento(function (resposta) {
                    if (Object.keys(resposta).length == 0) {
                        acionarErro("Agendamento negado pelo servidor")
                    } else {
                        id = resposta["id"]
                        invoker_atendimento(id)
                        setParam("atendimentoAgendado");
                    }
                    $("#salvarDados").removeAttr("disabled", true)
                })
            }
        } else {
            popupConflito(json["data_formatada"], json["horarioInicio"], json["horarioFim"], json["sala"])
            $("#salvarDados").removeAttr("disabled", true)
        }
    }, idAtendimentoEdicao)
}

function popupConflito(data, inicio, fim, sala) {
    htm = `
    <div style="text-align:center">Existe um atendimento já marcado conflitando com seu horário</div>
    <br>
    <div style="text-align:center"><b>Seu atendimento</b></div>
    <div style="text-align:center">Data: ` + $(".datepicker").val() + `, das ` + $(".inicioPicker").val() + ` às ` + $(".fimPicker").val() + `<br> Sala ` + $(".selectSalas option:selected").val() + `</div>
    <br>
    <div style="text-align:center"><b>Atendimento em conflito</b></div>
    <div style="text-align:center">Data: ` + data + `, das ` + inicio + ` às ` + fim + `<br> Sala ` + sala + `</div>
    `;
    Swal.fire({
        type: 'error',
        title: 'Conflito de horários!',
        html: htm,
    })
}