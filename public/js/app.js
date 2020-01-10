const url = (x, y, action) => '/index.php?x=' + x + '&y=' + y + '&action=' + action;

const putFlag    = (x, y) => url(x, y, 'flag');
const removeFlag = (x, y) => url(x, y, 'remove-flag');

window.onload = function () {
    const classname = document.getElementsByClassName('tile');

    for (let i = 0; i < classname.length; i++) {
        classname[i].addEventListener('contextmenu', function (e) {
            e.preventDefault();

            let x       = this.getAttribute('data-x'),
                y       = this.getAttribute('data-y'),
                hasFlag = this.classList.contains('flag'),
                isOpen  = !this.classList.contains('close');

            if (isOpen && !hasFlag) {
                return;
            }

            window.location.href = hasFlag ? removeFlag(x, y) : putFlag(x, y);
        }, false);
    }
};