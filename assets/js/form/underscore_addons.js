(function() {
  String.prototype.mask = function(m) {
    var m, l = (m = m.split("")).length, s = this.split(""), j = 0, h = "";
    for(var i = -1; ++i < l;){
        if(m[i] != "9"){
            if(m[i] == "\\" && (h += m[++i])) continue;
            h += m[i];
            i + 1 == l && (s[j - 1] += h, h = "");
        }
        else{
            if(!s[j] && !(h = "")) break;
            (s[j] = h + s[j++]) && (h = "");
        }
      }
    return s.join("") + h;
};;  _.mixin({
    explode: function(delimiter, string, limit) {
      var emptyArray, partA, partB, splitted;
      emptyArray = {
        0: ''
      };
      if (arguments.length < 2 || typeof arguments[0] === 'undefined' || typeof arguments[1] === 'undefined') {
        return null;
      }
      if (delimiter === '' || delimiter === false || delimiter === null) {
        return false;
      }
      if (typeof delimiter === 'function' || typeof delimiter === 'object' || typeof string === 'function' || typeof string === 'object') {
        return emptyArray;
      }
      if (delimiter === true) {
        delimiter = '1';
      }
      if (!limit) {
        return string.toString().split(delimiter.toString());
      } else {
        splitted = string.toString().split(delimiter.toString());
        partA = splitted.splice(0, limit - 1);
        partB = splitted.join(delimiter.toString());
        partA.push(partB);
        return partA;
      }
    },
    implode: function(glue, pieces) {
      var i, tGlue, _i, _len;
      if (arguments.length === 1) {
        pieces = glue;
        glue = '';
      }
      if (typeof pieces === 'object') {
        if (pieces instanceof Array) {
          return pieces.join(glue);
        } else {
          for (_i = 0, _len = pieces.length; _i < _len; _i++) {
            i = pieces[_i];
            retVal += tGlue + pieces[i];
            tGlue = glue;
          }
          return retVal;
        }
      } else {
        return pieces;
      }
    },
    count: function(Obj) {
      var count, prop;
      count = 0;
      for (prop in Obj) {
        if (Obj.hasOwnProperty(prop)) {
          count++;
        }
      }
      return count;
    },
    array_shift: function(inputArr) {
      if (inputArr.length === 0) {
        null;
      }
      if (inputArr.length > 0) {
        return inputArr.shift();
      }
    }
   ,
    strpos: function (haystack, needle, offset) {
        var i = (haystack + '').indexOf(needle, (offset || 0));
        return i === -1 ? false : i;
    }
    
  });
}).call(this);

