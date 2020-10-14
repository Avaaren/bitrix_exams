$(document).ready(function(){
    $("#oneclick-form").on("submit", function(e){
        e.preventDefault();
        // Битриксовый аякс запрос к компоненту
        // Обращается к файлу class.php к методу oneClickOrderAction
        BX.ajax.runComponentAction('custom:order.oneclick',
        'oneClickOrder', { 
            mode: 'class',
            data: {
                number: $("#phoneNumber").val()
            },
        })
        .then(function(response) {
            if (response.status === 'success') {
                if (response.data.errors.length > 0) {
                    response.data.errors.forEach(element => {
                        alert(element); 
                    });
                }
                else {
                    alert(`Заказ успешно добавлен с id ${response.data.id}`);
                }
            }
            else {
                alert("Запрос не дошел");
            }
        });
    });
});
