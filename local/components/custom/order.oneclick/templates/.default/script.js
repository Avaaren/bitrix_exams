$(document).ready(function(){
    $("#oneclick-form").on("submit", function(e){
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "/local/components/custom/order.oneclick/oneclick.php",
            data: $(this).serialize(),
            success:function(response){
                var jsonData = JSON.parse(response);
                if (jsonData.errors.length > 0) {
                    jsonData.errors.forEach(element => {
                       alert(element); 
                    });
                }
                else {
                    alert(`Заказ успешно добавлен с id ${jsonData.id}`);
                }
            },
            errors:function(){
                alert("Запрос не дошел");
            }
        });
    });
});
