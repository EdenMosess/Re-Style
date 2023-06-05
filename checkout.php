<?php
include 'auth.php';
$userid = $_SESSION['user'];
$discount = 0;
$discount_price = 0;

if (isset($_POST['complete_purchase'])) {


    $stmt = $sql->prepare("select * from user_addresses where user_id=?");
    $stmt->bindParam(1, $_SESSION['user'], PDO::PARAM_STR);
    $stmt->execute();
    $user_address = $stmt->fetch();
    $street = $user_address['street'];
    $city = $user_address['city'];
    $postal = $user_address['postal_code'];


    $total = $_POST['total'];
    $discount_price = $_POST['discount'];
    $payment = $_POST['payment'];

    $date = date('Y-m-d H:i');
    $query = "INSERT into orders (date, total, user_id, street, city, postal_code, payment_type, discount) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $date, PDO::PARAM_STR);
    $stmt->bindParam(2, $total, PDO::PARAM_STR);
    $stmt->bindParam(3, $userid, PDO::PARAM_STR);
    $stmt->bindParam(4, $street, PDO::PARAM_STR);
    $stmt->bindParam(5, $city, PDO::PARAM_STR);
    $stmt->bindParam(6, $postal, PDO::PARAM_STR);
    $stmt->bindParam(7, $payment, PDO::PARAM_STR);
    $stmt->bindParam(8, $discount_price, PDO::PARAM_STR);
    $stmt->execute();
    $orderid = $sql->lastInsertId();

    $stmt = $sql->prepare("select a.quantity, a.product_id, b.price from cart as a left join products as b on a.product_id=b.id where a.user_id=? order by a.id desc");
    $stmt->bindParam(1, $userid, PDO::PARAM_STR);
    $stmt->execute();
    $cart_items = $stmt->fetchAll();
    $total = 0;
    foreach ($cart_items as $cart_item) {
        $query = "INSERT into order_items (product_id, order_id, quantity, price, user_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $sql->prepare($query);
        $stmt->bindParam(1, $cart_item['product_id'], PDO::PARAM_STR);
        $stmt->bindParam(2, $orderid, PDO::PARAM_STR);
        $stmt->bindParam(3, $cart_item['quantity'], PDO::PARAM_STR);
        $stmt->bindParam(4, $cart_item['price'], PDO::PARAM_STR);
        $stmt->bindParam(5, $userid, PDO::PARAM_STR);
        $stmt->execute();

        $query = "update products set status='sold' where id=?";
        $stmt = $sql->prepare($query);
        $stmt->bindParam(1, $cart_item['product_id'], PDO::PARAM_STR);
        $stmt->execute();
    }

    $stmt = $sql->prepare("delete from cart where user_id=?");
    $stmt->bindParam(1, $userid, PDO::PARAM_STR);
    $stmt->execute();
    header('location:thank-you.php?order_id=' . $orderid);
    echo 'done';
    die();
}

if (isset($_POST['coupon'])) {
    $coupon = $_POST['coupon'];
    $stmt = $sql->prepare("select * from coupon_codes where code=? AND status='available'");
    $stmt->bindParam(1, $coupon, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $coupon = $stmt->fetch();
        $discount = $coupon['discount'];
        $msg = "<div class='alert-success alert'>Success, Discount applied successfully</div>";
    } else {
        $msg = "<div class='alert alert-danger'>Sorry this code is either used or invalid.</div>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cart</title>
    <?php include 'head.php'; ?>
    <style>
        td {
            vertical-align: middle !important;
            padding: 10px !important;
        }

        .pricing {
            text-align: center;
        }

        body {
            background: #e9fbeb;
        }
    </style>
</head>

<body>
<?php include 'nav.php'; ?>
<div class="container">

    <?php if (isset($msg)) {
        echo $msg;
    } ?>
    <div class="row mb-5">

        <div class="col-md-5">
            <h3 class="mt-5 mb-5">Order Summary</h3>


            <?php
            $stmt = $sql->prepare("select a.quantity, a.id as cart_id, b.* from cart as a left join products as b on a.product_id=b.id where a.user_id=? order by a.id desc");
            $stmt->bindParam(1, $_SESSION['user'], PDO::PARAM_STR);
            $stmt->execute();
            $products = $stmt->fetchAll();
            $subtotal = 0;
            $totalitems = 0;
            $totalcart = $stmt->rowCount();
            if ($totalcart > 0) {
                ?>

                <?php foreach ($products as $product) { ?>
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <img style="width:100%;height:200px;object-fit:cover;"
                                 src="images/<?php echo $product['image']; ?>" alt="">
                        </div>

                        <div class="col-md-6 text-center">
                            <b><?php echo $product['name']; ?></b>
                            <br><b>$<?php echo $product['price']; ?></b>
                            <br>Quantity: <?php echo $product['quantity']; ?>
                            <br>Size: <?php echo $product['size']; ?>
                        </div>
                    </div>
                    <hr>
                    <?php $subtotal += $product['price'] * $product['quantity'];
                    $totalitems += $product['quantity'];
                } ?>
                <?php $discount_price = ($discount / 100) * $subtotal; ?>
                <?php $total = $subtotal - $discount_price; ?>
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-8 ">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="float-right">
                                    <h4><span class="mr-4">Sub total</span></h4>
                                    <h4><span class="mr-4">Discount</span></h4>
                                    <h4><span class="mr-4">Total</span></h4>
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="float-right">
                                    <h4>$<?php echo round($subtotal, 2); ?></h4>
                                    <h4>$<?php echo round($discount_price, 2); ?></h4>
                                    <h4>$<?php echo $total; ?></h4>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <form action="" method="post">
                            <label for="">Coupon Code</label>
                            <input required type="text" class="form-control mb-3" name="coupon">
                            <button class="btn btn-primary">Add</button>
                        </form>
                    </div>
                </div>


            <?php } else { ?>
                <div class="text-center">
                    <h3 class="fancy mb-4 text-white">Oops. No items in your cart.</h3>
                    <a href="shop.php" class="btn btn-secondary">Shop Items</a>
                </div>
            <?php } ?>
        </div>
        <div class="col-md-7">
            <form action="" method="post" id="order_form">
                <div class="card mt-5" id="address_card" style="display:none">
                    <div class="card-body">
                        <h5 class="text-center">Shipping Address</h5>
                        <?php
                        $stmt = $sql->prepare("select * from user_addresses where user_id=?");
                        $stmt->bindParam(1, $_SESSION['user'], PDO::PARAM_STR);
                        $stmt->execute();
                        $addresses = $stmt->fetchAll();
                        foreach ($addresses as $address) {
                            ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="address"
                                       id="address_<?php echo $address['id']; ?>" value="<?php echo $address['id']; ?>"
                                       data-name="<?php echo $address['name']; ?>"
                                       data-street="<?php echo $address['street']; ?>"
                                       data-postal="<?php echo $address['postal_code']; ?>"
                                       data-city="<?php echo $address['city']; ?>">
                                <label class="form-check-label" for="address_<?php echo $address['id']; ?>">
                                    <?php echo $address['name']; ?>
                                </label>
                            </div>
                        <?php } ?>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="address" id="address_other"
                                   value="other">
                            <label class="form-check-label" for="address_other">
                                Other Shipping Address
                            </label>
                        </div>
                        <div style="display:none" id="address_form">
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <label for="">Address Name</label>
                                    <input type="text" class="form-control" name="name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Street and House</label>
                                    <input type="text" class="form-control" name="street">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">City</label>
                                    <input type="text" class="form-control" name="city">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Postal Code</label>
                                    <input type="text" class="form-control" name="postal_code"
                                           onkeypress="return isNumber(event)">
                                </div>
                            </div>
                        </div>
                        <div class="float-right">
                            <button type="button" class="btn btn-primary btn-lg px-5" id="address_next">Next</button>
                        </div>
                    </div>
                </div>
                <div class="card mt-5" id="payment_card">
                    <div class="card-body">
                        <h5 class="text-center">Payment Details</h5>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment" id="payment_cash" value="cash">
                            <label class="form-check-label" for="payment_cash">
                                Pay with Cash
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment" id="payment_paypal"
                                   value="paypal">
                            <label class="form-check-label" for="payment_paypal">
                                Pay with Paypal
                            </label>
                        </div>
                        <?php
                        $stmt = $sql->prepare("select * from user_cards where user_id=?");
                        $stmt->bindParam(1, $_SESSION['user'], PDO::PARAM_STR);
                        $stmt->execute();
                        $cards = $stmt->fetchAll();
                        foreach ($cards as $card) {
                            ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment"
                                       id="card_<?php echo $card['id']; ?>" value="<?php echo $card['id']; ?>"
                                       data-ending="<?php echo substr($card['card_number'], -4); ?>"
                                       data-validity="<?php echo $card['validity']; ?>">
                                <label class="form-check-label" for="card_<?php echo $card['id']; ?>">
                                    Card ending in <?php echo substr($card['card_number'], -4); ?>
                                    (valid: <?php echo $card['validity']; ?>)
                                </label>
                            </div>
                        <?php } ?>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment" id="payment_other"
                                   value="other">
                            <label class="form-check-label" for="payment_other">
                                Payment with another card
                            </label>
                        </div>
                        <div style="display:none" id="payment_form">
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <label for="">Cardholder's Name</label>
                                    <input type="text" class="form-control" name="cardholder_name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Card Number*</label>
                                    <input type="number" class="form-control" name="card_number">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Security Code*</label>
                                    <input type="number" class="form-control" name="security_code">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Validity*</label>
                                    <input type="number" class="form-control" name="validity">
                                </div>
                            </div>
                        </div>
                        <div class="float-right">
                            <button type="button" class="btn btn-primary btn-lg px-5" id="payment_next">Next</button>
                        </div>
                    </div>
                </div>

                <div class="card mt-5" id="summary_card" style="display:none">
                    <div class="card-body">
                        <h5 class="text-center">Order Summary</h5>

                        <h5><b>Your Cart:</b></h5>
                        <p class="mb-0">Total Items: <?php echo $totalitems; ?></p>
                        <p class="mb-0">Sub Total: $<?php echo $subtotal; ?></p>
                        <p class="mb-0">Discount: $<?php echo $discount_price; ?></p>
                        <p class="mb-0">Total Payment: $<?php echo $total; ?></p>
                        <p class="mb-0">Estimated Arrival Time: </p>
                        <br>
                        <h5><b>Pickup Addresses:</b></h5>
                        <?php
                        $stmt = $sql->prepare("SELECT *
                                                     FROM user_addresses
                                                     WHERE user_id IN (SELECT uploader
                                                                      FROM products
                                                                      WHERE products.id IN (SELECT b.id
                                                                                            FROM cart AS a
                                                                                                     LEFT JOIN products AS b ON a.product_id = b.id
                                                                                            WHERE a.user_id = ?
                                                                                            ORDER BY a.id DESC));");
                        $stmt->bindParam(1, $_SESSION['user'], PDO::PARAM_STR);
                        $stmt->execute();
                        $addresses = $stmt->fetchAll();
                        foreach ($addresses as $address) {
                            ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="address"
                                       id="address_<?php echo $address['id']; ?>" value="<?php echo $address['id']; ?>"
                                       data-name="<?php echo $address['name']; ?>"
                                       data-street="<?php echo $address['street']; ?>"
                                       data-postal="<?php echo $address['postal_code']; ?>"
                                       data-city="<?php echo $address['city']; ?>">
                                <label class="form-check-label" for="address_<?php echo $address['id']; ?>">
                                    <?php echo $address['name']; ?>
                                </label>
                            </div>
                        <?php } ?>
                        <p class="mb-0">Full Name: <span id="address_name"></span></p>
                        <p class="mb-0">Street and House: <span id="address_street"></span></p>
                        <p class="mb-0">City: <span id="address_city"></span></p>
                        <p class="mb-0">Postal Code: <span id="address_postal"></span></p>
                        <br>
                        <h5><b>Payment Details:</b></h5>
                        <p class="mb-0">Type: <span id="payment_type"></span></p>
                        <div id="card_details">
                            <p class="mb-0">Card ending in: <span id="card_ending"></span></p>
                            <p class="mb-0">Validity: <span id="card_validity"></span></p>
                        </div>
                        <br>
                        <button type="button" class="btn btn-primary btn-lg" id="prev_summary">Previous</button>
                        <div class="float-right">
                            <input type="hidden" name="total" value="<?php echo $total; ?>">
                            <input type="hidden" name="discount" value="<?php echo $discount_price; ?>">
                            <input type="hidden" name="complete_purchase" value="1">
                            <button type="submit" class="btn btn-primary btn-lg px-5" id="complete_purchase">Complete
                                Purchase
                            </button>
                            <div style="display:none" id="paypal-button"></div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
<script src="https://www.paypal.com/sdk/js?client-id=AfPnZ7Hnonuf-NMM6Q6f7U8nhk84i2Ei4fSI56eqAh0Gj74fYQ36qfCBtj9B2oJPULQLWjz5_EVgA5yy&currency=USD&disable-funding=credit"></script>
<script>
    $("input[name='address']").click(function () {
        if ($(this).val() == 'other') {
            $("#address_form").show();
        } else {
            $("#address_name").html($(this).data('name'));
            $("#address_city").html($(this).data('city'));
            $("#address_postal").html($(this).data('postal'));
            $("#address_street").html($(this).data('street'));
            $("#address_form").hide();
        }
    });

    $("input[name='payment']").click(function () {
        $("#payment_type").html("Card");
        $("#card_details").show();
        $("#complete_purchase").attr('type', 'submit');
        $("#complete_purchase").show();
        $("#paypal-button").hide();

        if ($(this).val() == 'other') {
            $("#payment_form").show();
        } else {
            if ($(this).val() == 'paypal') {
                $("#payment_type").html("Paypal");
                $("#card_details").hide();
                $("#complete_purchase").attr('type', 'button');
                $("#complete_purchase").hide();
                $("#paypal-button").show();
            }

            if ($(this).val() == 'cash') {
                $("#payment_type").html("Cash");
                $("#card_details").hide();

            }

            if ($(this).val() != 'paypal' && $(this).val() != 'other' && $(this).val() != 'cash') {
                $("#payment_type").html("Card");
                $("#card_details").show();
                $("#card_ending").html($(this).data('ending'));
                $("#card_validity").html($(this).data('validity'));
            }

            $("#payment_form").hide();
        }
    });

    $("#address_next").click(function () {
        if (!$("input[name='address']:checked").val()) {
            alert('Please select address!');
            return false;
        } else {

            if ($("input[name='address']:checked").val() == 'other') {
                $("#address_name").html($('input[name="name"]').val());
                $("#address_city").html($('input[name="city"]').val());
                $("#address_postal").html($('input[name="postal_code"]').val());
                $("#address_street").html($('input[name="street"]').val());
            }

            $("#address_card").hide();
            $("#payment_card").show();
        }
    });

    $("#payment_next").click(function () {
        if (!$("input[name='payment']:checked").val()) {
            alert('Please select payment!');
            return false;
        } else {

            if ($("input[name='payment']:checked").val() == 'other') {
                if ($('input[name="card_number"]').val().length != 16 ||
                    $('input[name="security_code"]').val().length != 3 ||
                    $('input[name="validity"]').val().length != 4) {
                    alert('Payment information invalid!');
                    return false;
                }
                var card_num = $('input[name="card_number"]').val();
                var lastFour = card_num.substr(card_num.length - 4);
                $("#card_ending").html(lastFour);
                $("#card_validity").html($('input[name="validity"]').val());
            }

            $("#payment_card").hide();
            $("#summary_card").show();
        }
    });

    $("#prev_summary").click(function () {
        $("#summary_card").hide();
        $("#payment_card").show();
    });

    $("#prev_payment").click(function () {
        $("#payment_card").hide();
        $("#address_card").show();
    });


    paypal.Buttons({
        locale: 'en_US',
        style: {
            layout: 'horizontal',
            size: 'medium',
            color: 'blue',
            shape: 'rect',
            label: 'pay',
            height: 40,
            tagline: 'false'
        },

        // Set up the transaction
        createOrder: function (data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '<?php echo $total; ?>',
                    }
                }]
            });
        },

        // Finalize the transaction
        onApprove: function (data, actions) {
            return actions.order.capture().then(function (orderData) {
                var transaction = orderData.purchase_units[0].payments.captures[0];
                $("#order_form").submit();
            });
        }
    }).render('#paypal-button');

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

</script>
</body>

</html>