
String.prototype.trunc = String.prototype.trunc || function(n){
    return this.length>n ? this.substr(0, n-1) + '...' : this;
};

function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}
function clone(obj) {
    var target = {};
    for (var i in obj) {
        if (obj.hasOwnProperty(i)) target[i] = obj[i];
    }
    return target;
}

var app = angular.module('app', ['ngSanitize', 'ui.bootstrap.alert', 'ui.bootstrap.modal']);
