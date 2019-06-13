function checkFirst() {
    let fn = 'checkFirst';
    let elem = $('#ad1').val().split('\n');
    const obj = {
        method: fn,
        data: elem
    }; 

    $.ajax({
        type: 'POST',
        url: 'app.php',
        data: obj,
        success: function(resp) {
            console.log(resp);
        }
    });
}

function checkSecond() {
    let elem = $('#ad2').val().split('\n');    
    console.log(elem);
}

function checkThird() {
    let elem = $('#ad3').val().split('\n');    
    console.log(elem);
}