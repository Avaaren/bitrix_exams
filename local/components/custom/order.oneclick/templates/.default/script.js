$(document).ready(function(){
    $("#oneclick-form").on("submit", function(e){
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "/local/components/custom/order.oneclick/oneclick.php",
            data: $(this).serialize(),
            success:function(response){
                console.log(response);
            },
        });
    });
});
