function init_mudarDados() {
    $(document).ready(function () {
        $(".collapsible").collapsible();
    });
    carregarDados()
    eventos()
}

function carregarDados() {
    /* Preencher nomes */
    $("#email").val(getEmail())
    Materialize.updateTextFields();
    if (jsonDados["tipo"] == "ALU") {
        $("#l3").hide();
    }
    if (jsonDados["tipo"] == "DOC") {
        $("#l2").hide();
    }
    if (jsonDados["tipo"] == "ADM") {
        $("#l2").hide();
        $("#l3").hide();
    }

    /* Preencher turmas */
    arrayNomes = getArrayNomes(getNomeCompleto())
    for (i = 0; i < arrayNomes.length; i++) {
        var name = arrayNomes[i];
        if (name == getNome()) {
            $(".selectNome").append("<option selected>" + name + "</option>")
        } else {
            $(".selectNome").append("<option>" + name + "</option>")
        }
    }
    $(".selectNome").selectpicker();

    json = getTodasTurmas()
    var i = 1;
    for (var cursos in json) {
        $(".selectTurmas").append("<optgroup id=\"opcao" + i + "\" label=\"" + cursos + "\">")
        var jsonCurso = json[cursos]
        for (turmas in jsonCurso) {
            if (jsonCurso[turmas] == getTurma()) {
                $("#opcao" + i).append("<option selected data-tokens=\"" + cursos + "\">" + jsonCurso[turmas] + "</option>")
            } else {
                $("#opcao" + i).append("<option data-tokens=\"" + cursos + "\">" + jsonCurso[turmas] + "</option>")
            }
        }
        i++;
    }
    $(".selectTurmas").selectpicker({
        liveSearchPlaceholder: "Pesquisa rápida",
        noneResultsText: "Nada foi encontrado"
    });
    
    /* Preencher disciplinas */
    d = getTodasDisciplinas()
    r = getDisciplinas()
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
}

function renderizar() {
    mudarDados.show()
}

function eventos() {
    $(".selectNome").on("changed.bs.select", function () {
        if (this.value != undefined) {
            if (getNome() == this.value) {
                deselect("1")
            } else {
                select("1")
            }
        }
    });
    $(".selectTurmas").on("changed.bs.select", function () {
        if (this.value != undefined) {
            if (getTurma() == this.value) {
                deselect("2")
            } else {
                select("2")
            }
        }
    });
    $(".selectDisciplinas").on("change", function () {
        var r = [];
        $(this).find("option:selected").each(function (key, value) {
            r.push(value.innerHTML);
        });
        r = disciplinaNomeToID(r)
        if (JSON.stringify(r) === JSON.stringify(getDisciplinas())) {
            deselect("3")
        } else {
            select("3")
        }
    });
    emailTemp = $("#email").val()
    $("input").keyup(function () {
        if(validarEmail($("#email").val())) {
            $("#erro2").hide()
        } else {
            $("#erro2").show()
        }
        if (emailTemp == $("#email").val()) {
            deselect("4")
        } else {
            select("4")
        }
    });
}

function select(x) {
    $("#l" + x + ">.collapsible-header").css("cssText", "color:rgb(38, 166, 154);");
    $("#l" + x + ">.collapsible-header>.material-icons").css("cssText", "color:rgb(38, 166, 154)!important;");
    $("#ed" + x).show()
}

function deselect(x) {
    $("#l" + x + ">.collapsible-header").removeAttr("style")
    $("#l" + x + ">.collapsible-header>.material-icons").removeAttr("style")
    $("#ed" + x).hide()
}

//function alterarOsDados() {
    //return false;
//}