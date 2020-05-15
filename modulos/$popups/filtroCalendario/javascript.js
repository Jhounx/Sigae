function init_filtroCalendario() {
    $(document).ready(function () {
        $(".collapsible").collapsible();
        $(".timepicker").pickatime({
            default: "now",
            fromnow: 0,
            twelvehour: false,
            donetext: "OK",
            cleartext: "Limpar",
            canceltext: "Cancelar",
            autoclose: false,
            ampmclickable: false,
        });

        $(".timepicker").on("mousedown", function (event) {
            event.preventDefault();
        })

        $("input.autocomplete").autocomplete({
            data: {
                "Salvador": null,
                "Jequié": null,
                "Porto Seguro": null
            },
        });

        $(".chip1").material_chip({
            placeholder: "Digite o campus",
        });

        $(".chip2").material_chip({
            placeholder: "Digite a turma",
        });

        $(".chip3").material_chip({
            placeholder: "Digite a disciplina",
        });

        $(".chip4").material_chip({
            placeholder: "Digite o docente",
        });


    })
}

function close_filtroCalendario() {
    filtroLivre = $("#buscaLivre").val()
    filtroCampus = $(".chip1").material_chip("data");
    filtroTurma = $(".chip2").material_chip("data");
    filtroDisciplina = $(".chip3").material_chip("data");
    filtroProfessor = $(".chip4").material_chip("data");
    filtroHorario = $(".timepicker").val()
    reloadCalendar()
}