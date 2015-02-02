<?php
defined('ABSPATH') or die('Access denied!');

$page = "
    <collector id=\"collector\" style=\"display: none;\" preloader_src=\"" . TC_IMG_URL . "712.GIF\" site_url=\"" . BASE_SITE_URL . "\"></collector>
    <div id=\"wrapper\" class=\"opt\" style=\"width: 95%; margin: 0 auto;\">
			<header id=\"header\">
                <h1>TwitterCart Options</h1>
			</header>
			<div id=\"main\">
                <div class=\"opt_settings\">
                    <span>Twitter Settings</span>
                    <form name=\"optionsform\" id=\"optionsform\" method=\"POST\" action=\"\">
";
if(!$personalAccount){
    $page .= "<label for=\"connecttwacc\" style=\"float: left;\">Connect your Twitter</label><input type=\"button\" id=\"connecttwacc\" name=\"connecttwacc\" onclick=\"tcLinkOauthVendor();\" style=\"width: 200px; margin: 0; cursor: pointer; margin-bottom: 27px !important; */\" value=\"Connect my twitter account\">";
}
                        
$page .= "              <label for=\"add_to_cart\">#AddToCart hashtag</label><input style=\"margin: 0;\" id=\"hashtag\" name=\"hashtag\" type=\"text\" placeholder=\"#AddToCart hashtag\" value=\"$hashtag\"/>
                        <label for=\"add_to_wish\">#AddToWishlist hashtag</label><input style=\"margin: 0;\" id=\"wishlist_hashtag\" name=\"wishlist_hashtag\" type=\"text\" placeholder=\"#AddToWishlist hashtag\" value=\"$wishlist_hashtag\"/>
                        <input type=\"submit\" style=\"cursor: pointer;\" value=\"Save\"/>
                    </form>
                </div>
                <div class=\"opt_details\">
                    <span>Help</span>
                    <div class=\"howdo_contain\">
                        <p class=\"faw_quest\">What I can put in hashtags fields?</p>
                        <p>Hashtag can cintain any characters and digitals, but max length must be not more than 140 characters.</p>
                        <p class=\"faw_quest\">Why is nothing happening at the click of a button?</p>
                        <p>Your browser can block modal pop-up windows. Please, check it and unblock modal windows if they were blocked.</p>
                    </div>
                </div>
	</div>
</div>	
";

return $page;


$page .= "
    <h2>Plugin options</h2>
    <form name=\"optionsform\" id=\"optionsform\" method=\"POST\" action=\"\">
    <div class=\"sbstuffbox\" style=\"background: white; width: 450px; min-height: 150px; height: auto; margin-bottom: 20px; padding-left: 40px; padding-top: 20px; padding-bottom: 20px; line-height: 50px; font-weight: 600;\">
    <collector id=\"collector\" style=\"display: none;\" preloader_src=\"" . TC_IMG_URL . "712.GIF\" site_url=\"" . BASE_SITE_URL . "\"></collector>
";
if ($personalAccount) {
    $page .= "
    <!--p>Link your Twitter account first! <button type=\"button\" style=\"width: 165px; float: right; margin-right: 50px;\" onclick=\"tcLinkOauthVendor();\">Link my twitter account</button></p-->
";  
} else {
    $page .= "
    <p>Link your Twitter account first! <button type=\"button\" style=\"width: 165px; float: right; margin-right: 50px;\" onclick=\"tcLinkOauthVendor();\">Link my twitter account</button></p>
";
}
$page .= "  
    <p style=\" margin-top: 30px;\">#AddToCart hashtag: <input type=\"text\" name=\"hashtag\" placeholder=\"mytwittercart\" value=\"$hashtag\" style=\"width: 165px; float: right; margin-right: 50px;\"></p>
    <p style=\" margin-top: 30px;\">#AddToWishlist hashtag: <input type=\"text\" name=\"wishlist_hashtag\" placeholder=\"mytwitterwl\" value=\"$wishlist_hashtag\" style=\"width: 165px; float: right; margin-right: 50px;\"></p>
    <p style=\"float: right; margin-right: 50px;\"><button type=\"submit\">Save</button></p>
    </div>
    </form>
";
return $page;
