<?php get_header(); ?>
		
<?php if ( is_active_sidebar('sidebarleft') ) { ?>
	<div id="mainContentSidebarLeft" >
<?php } else { ?>
	<div id="mainContent">
<?php } ?>
 
<!-- item -->
<?php the_content(); ?>

<script type="text/javascript" src="https://www.sagepayments.net/pay/1.0.0/js/pay.min.js"></script>

<div class="wrapper text-center">
	<form class="paymentform">
		<div class="form-group">
			<label class="control-label">Invoice Number</label>
			<input type="text" class="form-control invoice" id="invoicenumber" value="">
		</div>
		<div class="form-group">
			<label class="control-label">Amount of Payment</label>
			<input type="text" class="form-control currency" id="InputDollar" value="">
		</div>
		<button class="gform_button" id="paymentButton">Pay Now</button>
	</form>
	<div id="paymentDiv" hidden></div>
	<br /><br />
	<p style="width:100%"><pre><code id="paymentResponse"></code></pre></p>
</div>

<?php
    require('../shared/shared.php');
    
    $nonces = getNonces();
    $amount = $_GET["amount"];
    
    $req = [
        "merchantId" => $merchant['ID'],
        "merchantKey" => $merchant['KEY'], // don't include the Merchant Key in the JavaScript initialization!
        "requestType" => "payment",
        "orderNumber" => "Invoice" . rand(0, 1000),
        "amount" => $amount,
        "salt" => $nonces['salt'],
        "postbackUrl" => $request['postbackUrl'],
        "preAuth" => $request['preAuth']
    ]; 
    
    $authKey = getAuthKey(json_encode($req), $developer['KEY'], $nonces['salt'], $nonces['iv']);
?>
{
    "authKey": "<?php echo $authKey; ?>",
    "invoice": "<?php echo $req['orderNumber']; ?>",
    "salt": "<?php echo $req['salt']; ?>",
    "merch": "<?php echo $req['merchantId']; ?>",
    "clientId": "<?php echo $developer['ID'] ?>",
    "postback": "<?php echo $req['postbackUrl']; ?>"
}

<script type="text/javascript">
    // this time, when the user submits, we'll send the amount to a server-side
    // script that returns the data we'll need for initialization.
    
    PayJS(['PayJS/UI', 'jquery'],
    function($UI, $) {
        $("#paymentButton").click(function() {
            $(this).prop('disabled', true);
            $("#paymentResponse").text("The response will appear here as JSON, and in your browser console as a JavaScript object.");
            
            var amt = parseFloat($("#InputDollar").val().replace('$', ''));
            amt = amt.toFixed(2);
            $.get(
                "dynamic/auth.php",
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
                        addFakeData: true
                    });
                    $UI.setCallback(function(resp) {
                        console.log(resp.getResponse());
                        $("#paymentResponse").text(
                            resp.getResponse({ "json": true })
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

<!-- end item -->
<!-- end content -->
	</div><!-- end #mainContent -->

	<?php if ( is_active_sidebar(sidebarleft)) { ?>
	
		<div id="leftsidebar">
		<?php if ( !dynamic_sidebar('sidebar-left') ) : ?><?php endif; ?>
		</div>
		
	<? } else { ?>
	
	<?php } ?>	

	<br class="clearfloat" />
</div><!-- end #container -->

<?php get_footer(); ?>
