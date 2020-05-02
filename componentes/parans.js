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