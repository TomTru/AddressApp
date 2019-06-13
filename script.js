function check(index, which) {
    const ulId = 'ad' + index + 'ul';
    clearUl(ulId);
    let fn = 'check' + which;
    let elem = $('#ad' + index).val().split('\n');
    const obj = {
        method: fn,
        data: elem
    };

    $.ajax({
        type: 'POST',
        url: 'app.php',
        data: obj,
        success: function(resp) {
            insertToDom(resp, ulId);
        }
    });
}

function insertToDom(response, ulId) {
    const parsedResp = JSON.parse(response);
    const keys = Object.keys(parsedResp);
    keys.forEach(element => {
        let liItem = '<li>' + element + ' : ' + parsedResp[element].code;
        if (parsedResp[element].movedTo) {
            liItem += ' => ' + parsedResp[element].movedTo;
        }

        liItem += '</li>';
        $('#' + ulId).append(liItem);
    });
}

function clearUl(id) {
    $('#' + id).empty();
}