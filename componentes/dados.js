function getID() {
    return jsonDados["id"];
}

function getNomeCompleto() {
    return jsonDados["nome"];
}

function getNome() {
    return jsonDados["nomePreferencia"];
}

function getMatricula() {
    return jsonDados["matricula"];
}

function getEmail() {
    return jsonDados["email"];
}

function getTipo() {
    return jsonDados["tipo"];
}

function getCursoID() {
    return jsonDados["cursoID"];
}

function getCurso() {
    return jsonDados["curso"];
}

function getTurma() {
    return jsonDados["turma"];
}

function getCampusID() {
    return jsonDados["campusID"];
}

function getCampus() {
    return jsonDados["campus"];
}

function getTodasTurmas() {
    return jsonDados["todasTurmas"];
}

function getTodasDisciplinas() {
    return jsonDados["todasDisciplinas"];
}

function getTodasSalas() {
    return jsonDados["todasSalas"];
}


function getDisciplinas(json) {
    if(json == null) {
        json = jsonDados["disciplinas"];
    }
    r = [];
    for (i in json) {
        if(i != undefined) {
            r.push(i);
        }
    }
    return r;
}

function getDisciplinasNome(json) {
    if(json == "null") {
        return [];
    }
    if(json == null) {
        json = jsonDados["disciplinas"];
    }
    r = [];
    for (i in json) {
        if(i != undefined) {
            r.push(json[i]);
        }
    }
    return r;
}

function disciplinaNomeToID(arrayNomes) {
    dados = getTodasDisciplinas()
    arrayID = [];
    for(i = 0; i < arrayNomes.length; i++) {
        nome = arrayNomes[i];
        Object.keys(dados).forEach(function (key) {
            value = dados[key]
            if(value == nome) {
                arrayID.push(key)
            }
        });
    }
    return arrayID;
}

function disciplinaIDtoNome(arrayID) {
    dados = getTodasDisciplinas()
    a = []
    for(i = 0; i < arrayID.length; i++) {
        id = arrayID[i];
        Object.keys(dados).forEach(function (key) {
            value = dados[key]
            if(id == key) {
                a.push(dados[key])
            }
        });
    }
    return a;
}

function getTipoNome(tipoID) {
    if(tipoID == "ALU") {
        return "Aluno";
    }
    if(tipoID == "DOC") {
        return "Docente";
    }
    if(tipoID == "MON") {
        return "Monitor";
    }
    if(tipoID == "ADM") {
        return "Administrador";
    }
}

function validarEmail(email) {
    var patternEmail = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
    return patternEmail.test(email)
}

function getArrayNomes(completo) {
    var nomesArray = completo.split(" ")
    nome = nomesArray[0]
    combinacoes = []
    for (var i = 1; i < nomesArray.length; i++) {
        combinacoes.push(nome + " " + nomesArray[i])
    }
    return combinacoes
}

function notaDaSenha(password) {
    if (password.length < 6) {
        return 0
    }
    
    var matchedCase = new Array();
    matchedCase.push("[$@$!%*#?&]");
    matchedCase.push("[A-Z]");
    matchedCase.push("[0-9]");
    matchedCase.push("[a-z]");
    var nota = 0;
    for (var i = 0; i < matchedCase.length; i++) {
        if (new RegExp(matchedCase[i]).test(password)) {
            nota++;
        }
    }
    if(nota == 1) {
        return 20;
    }
    if(nota == 2) {
        return 50;
    }
    if(nota == 3) {
        return 75;
    }
    if(nota == 4) {
        return 100;
    }
    return 0;
}