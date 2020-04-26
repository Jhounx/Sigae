var m1 = false, m2 = false, m3 = false, m4 = false;

function init_agendarAtendimento() {
    initMaterialize()
    loadJson()
    eventos()
}

function initMaterialize() {
    $(".collapsible").collapsible();
    $(".datepicker").pickadate({
        selectMonths: true,
        selectYears: 1,
        today: "Hoje",
        clear: "Limpar",
        close: "Fechar",
        done: 'Confirmar',
        min: dataHoje(),
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
    Materialize.updateTextFields()
}

function loadJson() {
    d = getTodasSalas()
    r = []
    Object.keys(d).forEach(function (key) {
        value = d[key]
        existe = false;
        for (i = 0; i < r.length; i++) {
            if (value == r[i]) {
                existe = true;
            }
        }
        if (existe == true) {
            $(".selectSalas").append("<option selected>" + value + "</option>")
        } else {
            $(".selectSalas").append("<option>" + value + "</option>")
        }
    });
    $(".selectSalas").selectpicker({
        liveSearchPlaceholder: "Pesquisa rápida",
        noneResultsText: "Nada foi encontrado",
        noneSelectedText: "Selecione ao menos uma opção"
    });

    d = getTodasDisciplinas()
    r = []
    Object.keys(d).forEach(function (key) {
        value = d[key]
        existe = false;
        for (i = 0; i < r.length; i++) {
            if (key == r[i]) {
                existe = true;
            }
        }
        if (existe == true) {
            $(".selectDisciplinas").append("<option selected>" + value + "</option>")
        } else {
            $(".selectDisciplinas").append("<option>" + value + "</option>")
        }
    });
    $(".selectDisciplinas").selectpicker({
        liveSearchPlaceholder: "Pesquisa rápida",
        noneResultsText: "Nada foi encontrado",
        noneSelectedText: "Selecione ao menos uma opção"
    });
    agendarAtendimento.show()
}

function eventos() {
    /* Nome atendimento */
    $("#nomeAtendimento").keyup(function () {
        var t = $("#nomeAtendimento").val().replace(" ", "");
        if (t.length == 0 || t.length > 25) {
            $("#erro1").show()
        } else {
            $("#erro1").hide()
        }
    });
    /* data */
    $(".datepicker").change(function () {
        var t = $(".datepicker").val().replace(" ", "");
        if (t.length == 0) {
            $("#erro2").show()
        } else {
            $("#erro2").hide()
        }
    });
    /* horário inicio */
    $(".inicioPicker").change(function () {
        var t = $(".inicioPicker").val().replace(" ", "");
        if (t.length == 0) {
            $("#erro3").show()
        } else {
            $("#erro3").hide()
        }
    });
    /* horário fim */
    $(".fimPicker").change(function () {
        var t = $(".inicioPicker").val().replace(" ", "");
        if (t.length == 0 || !compararHorario($(".inicioPicker").val(), $(".fimPicker").val())) {
            $("#erro4").show()
        } else {
            $("#erro4").hide()
        }
    });
    /* select salas */
    $(".selectSalas").on("changed.bs.select", function () {
        if (this.value != undefined) {

        }
    });
    /* select disciplinas */
    $(".selectDisciplinas").on("changed.bs.select", function () {
        if (this.value != undefined) {

        }
    });
}

function select(x) {
    $("#l" + x + ">.collapsible-header").css("cssText", "color:rgb(38, 166, 154);");
    $("#l" + x + ">.collapsible-header>.material-icons").css("cssText", "color:rgb(38, 166, 154)!important;");
    $("#ed" + x).show()
    eval("m" + x + "=true");
    if (m1 == true || m2 == true || m3 == true || m4 == true || m5 == true) {
        $("#salvarDados").removeAttr("disabled", true)
    }
}

function deselect(x) {
    $("#l" + x + ">.collapsible-header").removeAttr("style")
    $("#l" + x + ">.collapsible-header>.material-icons").removeAttr("style")
    $("#ed" + x).hide()
    eval("m" + x + "=false");
    if (m1 == false & m2 == false & m3 == false & m4 == false & m5 == false) {
        $("#salvarDados").attr("disabled", true)
    }
}