function get_parametro(param) {
    json = get_parametros()[0]
    for (coisa in json) {
        if(coisa == param) {
            var c = json[coisa]
            return c;
        }
    }
}

function removeParam() {
    window.history.replaceState(null, null, window.location.pathname);
}

function get_parametros() {
    var url = new URL(document.URL).href
    if (url == window.location.origin + window.location.pathname) {
        url = url + "?"
    }
    var parametros = (url.split('/?')[1]).split('&')
    var json_parametros = {}
    for (coisa in parametros) {
        var coisa = parametros[coisa]
        var t = coisa.split('=')
        json_parametros[t[0]] = t[1]
    }
    var parametros_puro = (url.split('/?')[1])
    return [json_parametros, parametros_puro]
}

function add_parametros(name, valor, remove) {
    if(remove == true) {removeParam()}
    var parametros_puro = window.location.search
    if (parametros_puro == "") {
        url = "?" + name + "=" + valor
    } else {
        url = parametros_puro + "&" + name + "=" + valor
    }
    window.history.replaceState({}, "", url);
}