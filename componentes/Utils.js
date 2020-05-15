versao = "Dev 1.14.2"

/*##################################################
# FUNÇÕES DE URL'S
##################################################*/

function getAllParans() {
    url = window.location.href
    br = url.split("?")
    if (br[1] != undefined) {
        array = []
        multiples = br[1].split("&")
        for (i = 0; i < multiples.length; i++) {
            par = multiples[i].split("=")
            a = par[0]
            b = par[1]
            array.push([a, b])
        }
        return array;
    }
}

function getParam(param) {
    todos = getAllParans()
    for (i = 0; i < todos.length; i++) {
        if (todos[i][0] == param) {
            return todos[i][1]
        }
    }
}

function paramExist(param) {
    all = getAllParans()
    if (all == undefined) { return false } else {
        for (i = 0; i < all.length; i++) {
            if (all[i][0] == param) {
                return true;
            }
        }
        return false;
    }
}

function removeAllParans(except) {
    window.history.replaceState(null, null, window.location.pathname);

}

function removeParam(key) {
    todos = getAllParans();
    if (todos != undefined) {
        a = []
        for (i = 0; i < todos.length; i++) {
            if (todos[i][0] != key) {
                if (todos[i][1] != undefined) {
                    a.push(todos[i][0] + "=" + todos[i][1])
                } else {
                    a.push(todos[i][0])
                }
            }
        }
        if (a.length == 0) {
            removeAllParans()
        } else {
            url = a.join("&")
            window.history.replaceState(null, null, "?" + url);
        }
    }
}

function setParam(param, value, saveHistory, exclusive) {
    todos = getAllParans()
    a = []
    set = false
    url = ""
    if (todos != undefined && exclusive != true) {
        for (i = 0; i < todos.length; i++) {
            if (todos[i][0] == param) {
                if (value != undefined && value != '') {
                    a.push(todos[i][0] + "=" + value)
                } else {
                    a.push(todos[i][0])
                }
                set = true
            } else {
                if (todos[i][1] != undefined && todos[i][1] != '') {
                    a.push(todos[i][0] + "=" + todos[i][1])
                } else {
                    a.push(todos[i][0])
                }

            }
        }
        url = a.join("&")
    }
    if (set == false) {
        separador = ''
        if (todos != undefined && exclusive != true) {
            separador = '&'
        }
        if (value != undefined && value != '') {
            url = url + separador + param + "=" + value;
        } else {
            url = url + separador + param
        }
    }
    if (saveHistory) {
        window.history.pushState(url, null, "?" + url);
    } else {
        window.history.replaceState(null, null, "?" + url);
    }
}

/*##################################################
# FUNÇÕES DE CALCULO DE DATAS E HORÁRIOS
##################################################*/

function dataHoje(string) {
    if (string) {
        return moment().format("DD/MM/YYYY");
    } else {
        return Date.parse(moment());
    }
}

function stringDateToDate(str) {
    return Date.parse(moment(str, "DD/MM/YYYY"));
}

function somarDia(hj, dias, string) {
    if (hj == null) {
        hj = moment().format("DD/MM/YYYY");
    }
    if (string) {
        return moment(hj, "DD-MM-YYYY").add(dias, 'days').format('DD/MM/YYYY');
    } else {
        return moment(hj, "DD-MM-YYYY").add(dias, 'days').toDate()
    }
}

function somarAno(hj, anos, string) {
    if (hj == null) {
        hj = moment().format("DD/MM/YYYY");
    }
    if (string) {
        return moment(hj, "DD-MM-YYYY").add(anos, 'years').format('DD/MM/YYYY');
    } else {
        return moment(hj, "DD-MM-YYYY").add(anos, 'years').toDate()
    }
}

function diferencaHorario(h1, h2) {
    m1 = moment(h1, "hh:mm")
    m2 = moment(h2, "hh:mm")
    var totalHoras = (m2.diff(m1, 'hours'));
    var totalMinutos = m2.diff(m1, 'minutes');
    var minutos = totalMinutos % 60;
    if(minutos == 0) {
        return totalHoras + " horas"
    } else {
        return totalHoras + " horas e  " + minutos + " minutos"
    }
}

function compararHorario(h1, h2) {
    return moment(h1, "hh:mm").isBefore(moment(h2, "hh:mm"));
}

/*##################################################
# FUNÇÕES DE JSON
##################################################*/

function fixJson(json) {
    return json.replace(/'/g, "\"");
}

function isJson(json) {
    var json = fixJson(json)
    try {
        JSON.parse(json);
    } catch (e) {
        return false;
    }
    return true;
}

function jsonVazio(json) {
    return JSON.stringify(json) == "{}"
}

/*##################################################
# FUNÇÕES DE STRING'S
##################################################*/

function randomString(chars, tamanho) {
    text = ""
    for(i = 0; i < tamanho; i++) {
        random = Math.floor(Math.random() * chars.length + 1);
        var s = chars.charAt(random);
        text = text + s;
    }
    return text;
}

/*##################################################
# FUNÇÕES DIVERSAS
##################################################*/

function runFunction(nome, reclamar) {
    try {
        window[nome]()
    } catch (ex) { 
        if(reclamar) acionarErro("A função '" + nome + "' não existe");
    }
}

function definirVersao() {
    $(".versao").text(versao)
}