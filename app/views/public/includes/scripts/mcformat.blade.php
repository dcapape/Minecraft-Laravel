var obfuscators = [];
$('.mc-k').each(function(index, elem) {
  	var magicSpan, currNode;
    var string = elem.innerText;

    if(string.indexOf('<br>') > -1) {
        elem.innerHTML = string;
        for(var j = 0, len = elem.childNodes.length; j < len; j++) {
            currNode = elem.childNodes[j];
            if(currNode.nodeType === 3) {
                magicSpan = document.createElement('span');
                magicSpan.innerHTML = currNode.nodeValue;
                elem.replaceChild(magicSpan, currNode);
                init(magicSpan);
            }
        }
    } else {
        init(elem, string);
    }
    function init(el, str) {
        var i = 0,
            obsStr = str || el.innerHTML,
            len = obsStr.length;
        obfuscators.push( window.setInterval(function () {
            if(i >= len) i = 0;
            obsStr = replaceRand(obsStr, i);
            el.innerHTML = obsStr;
            i++;
            wrap_letters(el);
        }, 0) );
    }
    function randInt(min, max) {
        return Math.floor( Math.random() * (max - min + 1) ) + min;
    }
    function replaceRand(string, i) {
        var randChar = String.fromCharCode( randInt(63, 96) );
        return string.substr(0, i) + randChar + string.substr(i + 1, string.length);
    }
});

function wrap_letters($element) {
    for (var i = 0; i < $element.childNodes.length; i++) {
        var $child = $element.childNodes[i];

        if ($child.nodeType === Node.TEXT_NODE) {
            var $wrapper = document.createDocumentFragment();

            for (var i = 0; i < $child.nodeValue.length; i++) {
                var $char = document.createElement('span');
                $char.className = 'char';
                $char.textContent = $child.nodeValue.charAt(i);

                $wrapper.appendChild($char);
            }

            $element.replaceChild($wrapper, $child);
        } else if ($child.nodeType === Node.ELEMENT_NODE) {
            wrap_letters($child);
        }
    }
}
