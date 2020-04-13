var m1 = false, m2 = false, m3 = false, m4 = false, m5 = false;
var imgSetada = false, cropper;

function init_mudarDados() {
    $(document).ready(function () {
        $(".collapsible").collapsible();
    });
    carregarDados()
    eventos()
}

function salvarDados() {
    $("#salvarDados").attr("disabled", true)
    $("#undoDados").attr("disabled", true)

    array = [];
    if (m1 == true) {
        var nome = $("select.selectNome").val()
        array.push(["nomePreferencia", nome])
    }
    if (m2 == true) {
        var turma = $("select.selectTurmas").val()
        array.push(["turma", turma])
    }
    if (m3 == true) {
        var disci = $("select.selectDisciplinas").val()
        if (disci.length == 0) {
            acionarErro("Selecione pelo menos uma disciplina")
            $("#salvarDados").removeAttr("disabled")
            $("#undoDados").removeAttr("disabled")
            return;
        } else {
            disci = disciplinaNomeToID(disci)
            disci = disci.join("-");
            array.push(["disci", disci])
        }
    }
    if (m4 == true) {
        if (validarEmail($("#email").val())) {
            var email = $("#email").val()
            array.push(["email", email])
        } else {
            acionarErro("Email inválido")
            $("#salvarDados").removeAttr("disabled")
            $("#undoDados").removeAttr("disabled")
            return;
        }
    }
    if (m5 == true) {
        if (imgSetada == true) {
            var base = $("#previewImage").attr("src")
            var base64 = base.substring(base.indexOf(',') + 1);
            array.push(["img", base64])
        } else {
            array.push(["img", "REMOVE"])
        }
    }

    request = new Request()
    request.add("alterarDados", "")
    request.add("id", getID())
    for (i = 0; i < array.length; i++) {
        request.add(array[i][0], array[i][1])
    }
    request.setURL("../back-end/request.php")
    request.send("POST", ["OK", , "NONE", "IMG", "EML", "ERROR"], (resultado) => {
        resposta = resultado.resposta;
        erro = resultado.erro;
        if (resposta != null) {
            if (resposta == "OK") {
                window.location.reload(false);
            }
            if (resposta == "NONE") {
                acionarErro("None")
                $("#salvarDados").removeAttr("disabled")
                $("#undoDados").removeAttr("disabled")
            }
            if (resposta == "IMG") {
                acionarErro("Erro grave ao processar a imagem")
                $("#salvarDados").removeAttr("disabled")
                $("#undoDados").removeAttr("disabled")
            }
            if (resposta == "EML") {
                acionarErro("Esse e-mail já está sendo usado")
                $("#salvarDados").removeAttr("disabled")
                $("#undoDados").removeAttr("disabled")
            }
            if (resposta == "ERROR") {
                acionarErro("Erro grave")
                $("#salvarDados").removeAttr("disabled")
                $("#undoDados").removeAttr("disabled")
            }
        } else {
            alert(erro)
            acionarErro("Erro grave")
            $("#salvarDados").removeAttr("disabled")
            $("#undoDados").removeAttr("disabled")
        }
    });
}

/* Carregamento de dados */

function carregarDados() {
    /* Preencher nomes */
    $("#email").val(getEmail())
    Materialize.updateTextFields();
    if (jsonDados["tipo"] == "ALU") {
        $("#l3").hide();
    }
    if (jsonDados["tipo"] == "DOC" || jsonDados["tipo"] == "MON") {
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
        if (validarEmail($("#email").val())) {
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

    $('#inputImage').on("change", function () { carregarFile(); });
}

function select(x) {
    $("#l" + x + ">.collapsible-header").css("cssText", "color:rgb(38, 166, 154);");
    $("#l" + x + ">.collapsible-header>.material-icons").css("cssText", "color:rgb(38, 166, 154)!important;");
    $("#ed" + x).show()
    eval("m" + x + "=true");
    if (m1 == true || m2 == true || m3 == true || m4 == true || m5 == true) {
        $("#salvarDados").removeAttr("disabled", true)
        $("#undoDados").removeAttr("disabled", true)
    }
}

function deselect(x) {
    $("#l" + x + ">.collapsible-header").removeAttr("style")
    $("#l" + x + ">.collapsible-header>.material-icons").removeAttr("style")
    $("#ed" + x).hide()
    eval("m" + x + "=false");
    if (m1 == false & m2 == false & m3 == false & m4 == false & m5 == false) {
        $("#salvarDados").attr("disabled", true)
        $("#undoDados").attr("disabled", true)
    }
}

/* Imagem */

function deletarFoto() {
    select("5")
    $("#previewImage").attr("src", "../icones/semFoto.png")
    $("#deletarFoto").attr("disabled", true)
    $("#resetFoto").removeAttr("disabled")
    imgSeada = false
}

function resetFoto() {
    deselect("5")
    $("#previewImage").attr("src", "../back-end/fotos/fotos.php?request")
    $("#resetFoto").attr("disabled", true)
    $("#deletarFoto").removeAttr("disabled")
    imgSetada = false
}


function irParte1() {
    $("#parte1").show()
    $("#parte2").hide()
    $("#inputImage").val("");
}

function irParte2(data, largura, altura) {
    $("#parte1").hide()
    $("#parte2").show()
    $("#imagemCrop").attr("src", data)
    var image = document.getElementById("imagemCrop");
    cropper = new Cropper(image, {
        guides: false,
        cropBoxResizable: false,
        rotatable: false,
        rotatable: true,
        dragMode: 'move',
        data: {
            width: largura,
            height: altura,
        }
    });
}

function retornarParte1() {
    cropper.destroy()
    cropper = null;
    $("#imagemCrop").removeAttr("src")
    irParte1();
}

function rotacionarFoto() {
    cropper.rotate(90)
}


function cropImagem(base64) {
    crop.show()
    renderImagem(base64);
}

/* carregamento da imagem */

function carregarFile() {
    var uploadImage = document.getElementById("inputImage");
    if (uploadImage.files.length === 0) {
        return;
    }
    var file = document.getElementById("inputImage").files[0];
    lerArquivo(file)
}

function lerArquivo(file) {
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function () {
        var data = reader.result, img = new Image();
        img.onload = function () {
            var largura = img.naturalWidth, altura = img.naturalHeight;
            irParte2(data, largura, altura)
        };
        img.onerror = function () {
            acionarErro("Imagem inválida")
        };
        img.src = data;
    };
    reader.onerror = function () {
        acionarErro("Erro grave ao ler o arquivo")
    };
}

function redimensionarImagem() {
    var datas = cropper.getCroppedCanvas().toDataURL();
    var img = document.createElement('img');
    img.onload = function () {
        var canvas = document.createElement('canvas');
        var ctx = canvas.getContext('2d');
        canvas.width = 400;
        canvas.height = 400;
        ctx.drawImage(this, 0, 0, 400, 400);
        concluirFoto(canvas.toDataURL())
    };
    img.onerror = function () {
        acionarErro("Erro grave ao gravar o arquivo")
    };
    img.src = datas;
}

function concluirFoto(data) {
    select("5")
    cropper.destroy()
    cropper = null;
    $("#imagemCrop").removeAttr("src")

    irParte1()
    $("#previewImage").attr("src", data)
    $("#resetFoto").removeAttr("disabled")
    $("#deletarFoto").removeAttr("disabled")
    imgSetada = true;
}

