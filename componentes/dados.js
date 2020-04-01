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


function getDisciplinas() {
    a = jsonDados["disciplinas"];
    r = [];
    for (i in a) {
        if(i != undefined) {
            r.push(i);
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