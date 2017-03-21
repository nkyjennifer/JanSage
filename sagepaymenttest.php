<html>
<title>Sage Payments Static Page Test</title>
</html>

<!-- PayJS 1.0.0 -->
<!--<script type="text/javascript" src="https://www.sagepayments.net/pay/1.0.0/js/pay.min.js"></script>-->

<!-- PayJS 1.0.1 -->
<script type="text/javascript" src="https://www.sagepayments.net/pay/1.0.1/js/pay.min.js"></script>

<div class="wrapper text-center">
    <h1>Dynamic</h1>
    <p>It's not always possible to know all the initialization data up front &mdash; for instance, a non-profit might want to offer users several different donation amounts. Since the authKey encryption needs to be done server-side, and include the amount, these cases require an extra step:</p>
    <br />
    <div>
        <form class="form">
            <div class="form-group">
                <label class="control-label">Amount</label>
                <input type="text" class="form-control currency" id="exampleInputDollar" value="$1.00">
            </div>
            <button class="btn btn-primary" id="paymentButton">Pay Now</button>
        </form>
        <div id="paymentDiv" hidden></div>
        <br /><br />
        <h5>Results:</h5>
        <p style="width:100%"><pre><code id="paymentResponse">The response will appear here as JSON, and in your browser console as a JavaScript object.</code></pre></p>
    </div>
</div>

<script type="text/javascript">

    // this time, when the user submits, we'll send the amount to a server-side
    // script that returns the data we'll need for initialization.
    
    PayJS(['PayJS/UI', 'jquery'],
    function($UI, $) {
        $("#paymentButton").click(function() {
            $(this).prop('disabled', true);
            $("#paymentResponse").text("The response will appear here as JSON, and in your browser console as a JavaScript object.");
            
            var amt = parseFloat($("#exampleInputDollar").val().replace('$', ''));
            amt = amt.toFixed(2);

            $.get(
                "auth.php",
                {
                    amount: amt,
                },
                function(authResp) {
                    $UI.Initialize({
                        clientId: authResp.clientId,
                        merchantId: authResp.merch,
                        authKey: authResp.authKey,
                        requestType: "payment",
                        orderNumber: authResp.invoice,
                        amount: amt,
                        elementId: "paymentDiv",
                        postbackUrl: authResp.postback,
                        salt: authResp.salt,
                        addFakeData: true,
                        environment: "cert"
                    });
                    $UI.setCallback(function(resp) {
                        console.log(resp.getResponse());
                        $("#paymentResponse").text(
                            //TH - Update to PaymentsJS 1.0.1 use ".getRawResponse()" instead of ".getResponse({ "json": true })".
                            // resp.getResponse({ "json": true })
                            resp.getRawResponse()
                        );
                        $("#paymentButton").prop('disabled', false);
                    });
                    $("#paymentDiv").show('slow');
                },
                "json"
            );
        });
    });
</script>

</html>
