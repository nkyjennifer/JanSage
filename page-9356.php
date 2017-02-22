<?php get_header(); ?>
		
<?php if ( is_active_sidebar('sidebarleft') ) { ?>
	<div id="mainContentSidebarLeft" >
<?php } else { ?>
	<div id="mainContent">
<?php } ?>
 
<!-- item -->

		<div class="storycontent">

		<?php $pages = wp_list_pages(array(
				'child_of' => $post->ID,
				'depth' => 2, 'echo' => 0,
				'title_li'     => __('')
			));
			if(!empty($pages)) { ?>
		<div id="pagenav">
			<ul>
			<?php echo $pages; ?>
			</ul>
			<br class="clearfloat">
		</div>
		<?php
			}
		?>
		
		<?php if( count(get_post_ancestors($post->ID)) >= 2 ) { ?> 

		<div id="breadcrumbs">
			
		  <?php
			$parent_id  = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
			  $page = get_page($parent_id);
			  $breadcrumbs[] = '<a href="'.get_permalink($page->ID).'" title="">'.get_the_title($page->ID).'</a>';
			  $parent_id  = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			foreach ($breadcrumbs as $crumb) echo ' << '.$crumb;
		  ?>
		</div>
		<?php
			}
		?>
	
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
				Test Page for Sage Javascript
				<?php the_content(); ?>

<style>
    #paymentDiv {
        width: 60%;
        margin-left: auto;
        margin-right: auto;
        padding: 15px;
    }
</style>

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
                "http://bradyware.com/wp-content/themes/Brady_Ware_customWP/auth.php",
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


				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

		</div>
		
	<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
			<?php endwhile; ?>
	<?php endif; ?>

<!-- begin recent posts section -->
	<?php if ( is_front_page() ) { ;?>
	
	<?php if ( !dynamic_sidebar('industries') ) : ?><?php endif; ?>
	
		<div id="recentnews">
		<hr>
		<h3>Recent News</h3>

<!-- Latest 3 blog posts title and excerpt-->
				<?php $the_query = new WP_Query( 'showposts=3' ); ?>

				<?php while ($the_query -> have_posts()) : $the_query -> the_post(); ?>
				
				<span class="recentposts">
				
					<a href="<?php the_permalink() ?>">
					<h4><?php the_title(); ?></h4>
					<?php the_excerpt(__('(more…)')); ?>
					<p class="more">Read More&raquo; </p></a>
					
				</span>
				
				<?php endwhile;?>
   
<!-- end latest post -->
			</div>

	<? } else { ?>
	<?php } ?>	
	
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