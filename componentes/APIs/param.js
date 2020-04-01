function get_parametro(param) {
    json = get_parametros()[0]
    for (coisa in json) {
        if (coisa == param) {
            var c = json[coisa]
            return c;
        }
    }
}

function removeParam() {
    window.history.replaceState(null, null, window.location.pathname);
}

function removeParamByKey(key) {
    url = window.location.href
    var final = url.split("?")[0], param, array = [], queryString = (url.indexOf("?") !== -1) ? url.split("?")[1] : "";
    if (queryString !== "") {
        array = queryString.split("&");
        for (var i = array.length - 1; i >= 0; i -= 1) {
            param = array[i].split("=")[0];
            if (param === key) {
                array.splice(i, 1);
            }
        }
        if(array.length == 0) {
            final = final + array.join("&");
        } else {
            final = final + "?" + array.join("&");
        }
    }
    window.history.replaceState(null, null, final);
}

function get_parametros() {
    var url = new URL(document.URL).href
    if (url == window.location.origin + window.location.pathname) {
        url = url + "?"
    }
    var parametros = (url.split('?')[1]).split('&')
    var json_parametros = {}
    for (coisa in parametros) {
        var coisa = parametros[coisa]
        var t = coisa.split('=')
        json_parametros[t[0]] = t[1]
    }
    var parametros_puro = (url.split('?')[1])
    return [json_parametros, parametros_puro]
}

var numeroPagina = 0;

function add_parametros(name, valor) {
    var url = "?" + name + "=" + valor;
    numeroPagina += 1;
    window.history.pushState({ pagina: numeroPagina, valor: valor, url: url }, valor, url);
    window.history.replaceState({ pagina: numeroPagina, valor: valor, url: url }, valor, url);
}