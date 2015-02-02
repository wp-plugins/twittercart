<?php
//Plugin options page
return "
    <div id=\"wrapper\" class=\"opt\"  style=\"width: 95%; margin: 0 auto;\">
			<header id=\"header\">
                <h1 id=\"headertitle\">TwitterCart Options</h1>
			</header>
			<div id=\"main\">
                <div class=\"opt_settings\">
                    <span>Twitter Settings</span>
                    <form name=\"optionsform\" id=\"optionsform\" method=\"POST\" action=\"\">
                        <label for=\"twt_api_key\">Twitter API key</label><input style=\"margin: 0;\" id=\"twt_api_key\" name=\"twt_api_key\" type=\"text\" placeholder=\"Twitter API key\" value=\"$api_key\"/>
                        <label for=\"twt_api_sec\">Twitter API secret</label><input style=\"margin: 0;\" id=\"twt_api_sec\" name=\"twt_api_sec\" type=\"text\" placeholder=\"Twitter API secret\"  value=\"$api_secret\"/>
                        <label for=\"acc_tok\">Access token</label><input style=\"margin: 0;\" id=\"acc_tok\" id=\"acc_tok\" name=\"acc_tok\" type=\"text\" placeholder=\"Access token\" value=\"$access_token\"/>
                        <label for=\"acc_tok_sec\">Access token secret</label><input style=\"margin: 0;\" id=\"acc_tok_sec\" name=\"acc_tok_sec\" type=\"text\" placeholder=\"Access token secret\" value=\"$access_token_secret\"/>
                        <label for=\"add_to_cart\">#AddToCart hashtag</label><input style=\"margin: 0;\" id=\"add_to_cart\" name=\"add_to_cart\" type=\"text\" placeholder=\"#AddToCart hashtag\" value=\"$hashtag\"/>
                        <label for=\"add_to_wish\">#AddToWishlist hashtag</label><input style=\"margin: 0;\" id=\"add_to_wish\" name=\"add_to_wish\" type=\"text\" placeholder=\"#AddToWishlist hashtag\" value=\"$wishlist_hashtag\"/>
                        <input type=\"button\" onclick=\"updateAdminOptions();\" style=\"cursor: pointer;\" value=\"Save\"/>
                    </form>
                </div>
                <div class=\"opt_details\">
                    <span>How do I obtain Twitter API details?</span>
                    <div class=\"howdo_contain\">
                        <ul>
                            <li><span>1</span>Go to the <a href=\"https://apps.twitter.com/\">Apps page</a> on Twitter and click &quot;Create New App&quot;.</li>
                            <li><span>2</span>Set required info about your application and save.</li>
                            <li><span>3</span>Go to &quot;Keys and Access Tokens&quot; tab and copy API key and API secret.</li>
                            <li><span>4</span>Click &quot;Create my access token&quot; and copy genearted token and token secret.</li>                            
                        </ul>
                        <p class=\"faw_quest\">What can I include in hashtags fields?</p>
                        <p>The Hashtag can contain any characters and/ or numerical digits, but max length must not exceed 140 characters.</p>
                    </div>
                </div>
	</div>
</div>	
";