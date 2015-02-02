<?php
defined('ABSPATH') or die('Access denied!');

$html .= "
    <div id=\"wrapper\" class=\"opt instr\" style=\"width: 95%; margin: 0 auto;\">
			<header id=\"header\">
                <h1 id=\"headertitle\">TwitterCart Instructions</h1>
			</header>
			<div id=\"main\">
                <div class=\"opt_settings\">
                    <span>TwitterCart for Administrators</span>
                    <div class=\"instr_admin_contant\">
                        <strong>Activation</strong>
                        <p>After activation of the TwitterCart plugin, you will need to setup API credentials to connect your Woocommerce store account.</p>
                        <ul>
                            <li>1. Twitter API key.</li>
                            <li>2. Twitter API secret key.</li>
                            <li>3. Twitter API access token.</li>
                            <li>4. Twitter API secret token secret.</li>
                        </ul>
                        <p>With TwitterCart, you can create custom hashtags for promoting products on Twitter ( ie. Add to cart, Add to wishlist).</p>
                        <strong>Posting products to Twitter</strong>
                        <p>In order to post items to Twitter, you should to go to the product page and click on the \"Post product to Twitter\" for the selected product in the Actions column. Next, you can change default text of the tweet. After you have customized your tweet, with or without the product image checkmarked, click the \"Tweet\" button product and your product tweet with hashtag(s) will be posted on Twitter instantly.</p>
                    </div>
                </div>
                <div class=\"opt_details\">
                    <div class=\"instr_costumer_container\">
                        <span>TwitterCart for Customers</span>
                        <div class=\"instr_costumer_contant\">
                            <strong>Twitter Account</strong>
                            <p>Customers have the option to authorize TwitterCart in their \"My Account\" page. At the bottom of \"My Account\" page, customers should click \"Link my Twitter account\" button and agree with Twitter rules on next page.  This authorizes their account and they can use hashtags in replies on Twitter for adding products from the store to their shopping cart or wishlist for checkout the next time they login to their account on the vendor store.</p>
                        </div>
                    </div>
                </div>
			</div>
		</div>	
";

return $html;
