/* eslint-disable func-names,no-var,prefer-reflect,prefer-arrow-callback */
(function () {
    var win = window; // eslint-disable-line no-undef
    var FR = win.FileReader;
    var doc = win.document;
    var kjua = win.kjua;

    var gui_val_pairs = [
    ];

    function el_by_id(id) {
        return doc.getElementById(id);
    }

    function val_by_id(id) {
        var el = el_by_id(id);
        return el && el.value;
    }

    function on_event(el, type, fn) {
        el.addEventListener(type, fn);
    }

    function on_ready(fn) {
        on_event(doc, 'DOMContentLoaded', fn);
    }

    function for_each(list, fn) {
        Array.prototype.forEach.call(list, fn);
    }

    function all(query, fn) {
        var els = doc.querySelectorAll(query);
        if (fn) {
            for_each(els, fn);
        }
        return els;
    }

    function update_qrcode() {
        var options = {
            render: 'canvas',
            ecLevel: 'H',
            minVersion: 1,

            fill: '#333333',
            back: '#ffffff',

            text: val_by_id('text'),
            size: 400,
            rounded: 100,
            quiet: 1,

            mode: 'label',

            mSize: 20,
            mPosX: 50,
            mPosY: 50,

            label: '',
            fontname: 'Ubuntu Mono',
            fontcolor: '#ff9818',
        };
        var cr_address = val_by_id('address');
        var currency = val_by_id('currency');
        var amount = val_by_id('amount');
        el_by_id('text').value = currency+'://'+cr_address+'?amount='+amount;

        var container = el_by_id('container');
        var qrcode = kjua(options);
        for_each(container.childNodes, function (child) {
            container.removeChild(child);
        });
        if (qrcode) {
            container.appendChild(qrcode);
        }
    }

    function update() {
        update_qrcode();
    }

    function on_img_input() {
        var input = el_by_id('image');
        if (input.files && input.files[0]) {
            var reader = new FR();
            reader.onload = function (ev) {
                el_by_id('img-buffer').setAttribute('src', ev.target.result);
                el_by_id('mode').value = 4;
                setTimeout(update, 250);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    on_ready(function () {
        on_event(el_by_id('image'), 'change', on_img_input);
        all('input, textarea, select', function (el) {
            on_event(el, 'input', update);
            on_event(el, 'change', update);
            on_event(el, 'click', update);
        });
        on_event(win, 'load', update);
        update();
    });
}());
/* eslint-enable */
