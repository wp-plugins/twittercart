<?php
defined('ABSPATH') or die('Access denied!');

$html = "
    <div id=\"wrapper\" class=\"faq\" style=\"width: 95%; margin: 0 auto;\">
			<header id=\"header\">
                <h1 id=\"headertitle\">TwitterCart FAQ's</h1>
			</header>
			<div id=\"main\">
                <div class=\"faq_container\">
                    <span>Frequently Asked Questions</span>
                    <div class=\"questions\">
                        <ul>
                            <li class=\"quest1\">
                                <span>Why do I need to connect my Twitter account?</span>
                                By connecting your Twitter with your ecommerce store account, you are notifying the store that #TwitterCart requests coming from your Twitter account should be added to your shopping cart. Without that link, the store owner would not know to which customer’s cart to add the item. To edit your Twitter authorization preferences, visit your account page or opt out of having the store respond to your #TwitterCart requests here (your accounts must be connected in order to opt out).
                            </li>
                            <li class=\"quest2\">
                                <span>Will #TwitterCart work if my Twitter account is protected?</span>
                                No, #TwitterCart only works for public Twitter accounts and tweets. If your Twitter account is protected, only your followers can see your tweets. This means that #TwitterCart won't be able to see your replies and add the item to your shopping cart.
                            </li>
                            <li class=\"quest3\">
                                <span>Am I buying the product when I reply with the store's \"#TwitterCart\" hashtag?</span>
                                No, replying with \"#TwitterCart\" hashtag will only save the item to your Cart. You can always review or edit your Cart at a later time. You will also receive a reply tweet from store's Twitter account describing the status of your request (e.g., whether the item was successfully added to your Cart, if it was out of stock, or how you can finish checking out later).
                            </li>
                            <li class=\"quest4\">
                                <span>Who can see what I’ve added to my Cart?</span>
                                Most content is public on Twitter, so your #TwitterCart replies will be visible to whomever you replied, to those viewing the conversation, and on your own Timeline (unless your Twitter account is set to private).
                            </li>
                        </ul>
                        <div class=\"tc_notes\">
                            <p>To work correctly with the Twitter API, you must install the necessary permissions (Read, write, and direct messages) on <a href=\"https://apps.twitter.com/\">Apps page</a></p>                            
                        </div>
                    </div>
                </div>
			</div>
		</div>	
";

return $html;
