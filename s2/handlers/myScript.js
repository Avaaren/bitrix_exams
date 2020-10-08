$(document).ready(function() {
    // Отлавливание нажатия на кнопку жалобы
    $('#ajax-request').click(function(e) {
        $("#ajax-message").remove();
        // Из дата атрибутов получаем id новости и состояние ajax мода
        let elementId = $(this).data("id");
        let isAjax = $(this).data("ajax");
        e.preventDefault();
        // Если ajax мод включен, пост запрос к обработчику
        if (isAjax == "Y") {
            $.ajax({
                type: "POST",
                context: document.body,
                url: '/s2/handlers/ajax_handler.php',
                data: {
                    "elementId": elementId
                },
                // Если запрос прошел успешно, то выводим сообщение
                success: function(response)
                {
                    var jsonData = JSON.parse(response);
                    console.log(jsonData);
                    $("#ajax-request").after(`<span id='ajax-message'>Успешно добавлено ${jsonData.addedElementId}. Ваше мнение очень важно</span>`);
                },
                error: function(response)
                {
                    $("#ajax-request").after(`<span id='ajax-message'>Что то пошло не так =)</span>`);
                }
           });
        }
        else {
            // Если не ajax режим, то отсылаем синхронный гет запрос к обработчику
            let xhr = new XMLHttpRequest();
            // Создаем полный url обработчика и добавлем параметры
            let url = new URL('/s2/handlers/ajax_handler.php', window.location.origin);
            url.searchParams.set('elementId', elementId);
            // Открыаваем соединение в синхронном режиме и отправляем его
            xhr.open('GET', url, false);
            try {
                xhr.send();
                // Если код ответа не 200, то выводим сообщение об ошибке
                if (xhr.status != 200) { 
                    alert(`Ошибка ${xhr.status}: ${xhr.statusText}`);
                }  
                else { 
                    $("#ajax-request").after(`<span id='ajax-message'>Успешно добавлено ${xhr.response}. Ваше мнение очень важно</span>`);
                
                }
              } 
              catch(err) { 
                alert("Запрос не удался");
              }
        }
        
     });
});
