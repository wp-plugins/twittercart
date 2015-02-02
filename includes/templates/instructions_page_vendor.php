<?php
defined('ABSPATH') or die('Access denied!');

$html .= "
    <div id=\"wrapper\" class=\"opt instr\" style=\"width: 95%; margin: 0 auto;\">
			<header id=\"header\">
                <h1 id=\"headertitle\">TwitterCart Instructions</h1>
			</header>
			<div id=\"main\">
                <div class=\"opt_settings\">
                    <span>TwitterCart for admin</span>
                    <div class=\"instr_admin_contant\">
                        <strong>Activation</strong>
                        <p>After activation you will set API credentials of your shop account.</p>
                        <ul>
                            <li>1. Twitter API key</li>
                            <li>2. Twitter API secret key</li>
                            <li>3. Twitter API access token</li>
                            <li>4. Twitter API secret token secret</li>
                        </ul>
                        <p>Also you can change relevant hashtags for main functions of the plugin (Adding to cart, Adding to wishlist)</p>
                        <strong>Posting products to Twitter</strong>
                        <p>In order to post items need to go to the product page and click the \"Post product to Twitter\". On next step you can change default text of the tweet. After you clcik \"Post\" button product will be available for purchase on Twitter with relevant hahstag.</p>
                        <strong>Twitter account</strong>
                        <p>You can manage your twitter account within WP admin panel. Go to the Twitter account page for this. On this page available functionality of Twitter (Retweets, replies, deirect messages e t. c.)</p>
                        <strong>Plugin stats</strong>
                        <p>Plugin statis is available on Stats page. You can see most retweeted, most replied products e t.c.. All stats within all shop and all hashtags.</p>
                    </div>
                </div>
                <div class=\"opt_details\">
                    <div class=\"instr_vendor_container\">
                        <span>TwitterCart for vendors</span>
                        <div class=\"instr_vendor_contant\">
                            <strong>Vendors options</strong>
                            <p>Vendors can change default hashtags and set personal hashtags for \"Add to cart\" and \"Add to wishlist\" functions. Also vendors can link their personal accounts for posting their products.</p>
                            <strong>Vendors functions</strong>
                            <p>In other cendors have same functionality with admin. But only their products are available for all functions.</p>
                        </div>
                    </div>
                    <div class=\"instr_costumer_container\">
                        <span>TwitterCart for customers</span>
                        <div class=\"instr_costumer_contant\">
                            <strong>Twitter account</strong>
                            <p>For using all functionality of the plugin user must enavle his Twitter account. In the bottom of My account page click \"Link my Twitter account\" button and agree with Twitter rules on next page. After this he can use hashtags for buying products from this shop.</p>
                        </div>
                    </div>
                </div>
			</div>
		</div>	
";

return $html;
