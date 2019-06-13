function checkFirst() {
    let fn = 'checkFirst';
    let elem = $('#ad1').val().split('\n');
    const obj = {
        method: fn,
        data: elem
    };

    clearUl('ad1ul');
    $.ajax({
        type: 'POST',
        url: 'app.php',
        data: obj,
        success: function(resp) {
            insertToDom(resp, 'ad1ul');
        }
    });
}

function checkSecond() {
    let elem = $('#ad2').val().split('\n');    
}

function checkThird() {
    let elem = $('#ad3').val().split('\n');
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