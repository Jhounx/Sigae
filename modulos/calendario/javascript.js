var ano;

/* Filtros */

var filtroLivre, filtroCampus, filtroTurma, filtroDisciplina, filtroProfessor, filtroHorario,
    diaP, mesP, anoP

function init_calendario() {
    anoAtual()
    popups()
}

function popups() {
    filtroCalendario = new Popup("filtroCalendario", "../modulos/filtroCalendario", "Adicionar um filtro", "700px", null);
    filtroCalendario.setScroll(true)
    filtroCalendario.setCss(true)
    filtroCalendario.setJS(true)
    filtroCalendario.invoker()

    diaPopup = new Popup("diaPopup", "../modulos/diaPopup", "Dia: null", "700px", "98%");
    diaPopup.setScroll(true)
    diaPopup.setCss(true)
    diaPopup.setJS(true)
    diaPopup.invoker()
}

function invokerDia(d, m, a) {
    diaP = d
    mesP = m
    anoP = a
    diaPopup.show()
}

function renderCalendario(ano) {
    $("#calendar").empty()
    $(".anoCalendario").text(ano)
    var calendario = document.getElementById("calendar")
    var calendarize = new Calendarize()
    calendarize.buildYearCalendar(calendario, ano)
}

function anoVoltar() {
    if (ano > 2000) {
        ano = ano - 1;
        renderCalendario(ano)
    } else {
        alerta("O limite do calendário foi atingido")
    }
}

function anoAvancar() {
    if (ano < 2100) {
        ano = ano + 1;
        renderCalendario(ano)
    } else {
        alerta("O limite do calendário foi atingido")
    }
}

function anoAtual() {
    ano = new Date().getFullYear();
    renderCalendario(ano)
}

function reloadCalendar() {
    // alert(filtroLivre)
    // alert(filtroCampus)
    // alert(filtroTurma)
    // alert(filtroDisciplina)
    // alert(filtroProfessor)
    // alert(filtroHorario)
}