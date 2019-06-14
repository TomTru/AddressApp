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
            switch(ulId) {
                case 'ad1ul':
                    insertToDom(resp, ulId);
                    break;
                case 'ad2ul':
                case 'ad3ul':
                    insertToDom2(resp, ulId);
                    break;
            }
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

function insertToDom2(response, ulId) {
    const parsedResp = JSON.parse(response);

    for (i in parsedResp) {
        let liItem = '<li>' + parsedResp[i] + '</li>';
        $('#' + ulId).append(liItem);
    }
    
}

function clearUl(id) {
    $('#' + id).empty();
}