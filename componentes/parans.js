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

function removeAllParans() {
    window.history.replaceState(null, null, window.location.pathname);
}

function removeParam(key) {
    todos = getAllParans();
    if (todos != undefined) {
        a = []
        for (i = 0; i < todos.length; i++) {
            if (todos[i][0] != key) {
                if(todos[i][1] != undefined) {
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

function setParam(param, value, content) {
    todos = getAllParans()
    a = []
    set = false
    url = ""
    if (todos != undefined) {
        for (i = 0; i < todos.length; i++) {
            if (todos[i][0] == param) {
                a.push(todos[i][0] + "=" + value)
                set = true
            } else {
                a.push(todos[i][0] + "=" + todos[i][1])
            }
        }
        url = a.join("&")
    }
    if (set == false) {
        if (todos == undefined) {
            url = url + param + "=" + value;
        } else {
            url = url + "&" + param + "=" + value;
        }
    }
    if(content != undefined) {
        window.history.pushState({ value: content, url: url }, null, "?" + param + "=" + value);
    } else {
        window.history.pushState(null, null, "?" + url);
    }
}