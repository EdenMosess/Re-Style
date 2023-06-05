<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
<script>
    getTotalCartItems();
    function getTotalCartItems(){
        $.ajax({
            url: 'getTotalCartItems.php',
            type: 'POST',
            data: {
                getTotalCartItems: 1,
            },
            success: function(data) {
                $("#totalCartItems").html(data);
            },
        });
    }
</script>