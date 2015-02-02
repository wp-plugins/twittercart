<?php
//Post product page

return
        "
    <h2>Post product to Twitter</h2>
    <div style=\"background: transparent; margin: 0; padding: 0; margin-bottom: 33px;\">
    <form name=\"postform\" id=\"postform\" method=\"POST\" action=\"\" style=\"float: left; width: 48.25%;\">
        <div class=\"post_container\">
          <div class=\"post_market\">
            <a href=\"\" class=\"m_browserweb\">
              <p>Browserweb.com</p>
              <span>@browserweb</span>
            </a>
            <a href=\"\" class=\"m_twt\">
              <img src=\"".TC_IMG_URL."twt_cart.jpg\" alt=\"TwitterCart\">
            </a>
          </div>
          <label class=\"post_edit\">
            <a href=\"\"><img alt=\"BrowserWeb\" src=\"".TC_IMG_URL."brw-retro-logo-black-111opt.png\"></a>
            <textarea id=\"status_text\" name=\"status_text\" maxlength=\"120\">".$default_status_text."</textarea>
          </label>
          <div class=\"post_act\">
            <label>
              <span>Attach image</span>
              <input type=\"checkbox\" checked=\"true\" id=\"img_attach\" name=\"img_attach\">
            </label>
            
            <input value=\"Tweet\" name=\"submited\" type=\"submit\">
          </div>
        </div>

    </form>
    <div class=\"support-box\" style=\"background: white; margin-left: 50%;\">
                    <span>Tweet Tips</span>
                    <p style=\"padding: 20px;\">
                        Edit your tweet... you have 140 charcters including the image so make it good and you should reference
                        the #hashtag you use in your tweet that you wish customers or visitor to reply with to gain new bussines.
                        So for example if your hashtag is #mytwittercart then edit the product tweet before posting to include your hashtag
                        ie. reply with #mytwittercart to add this item to your cart!
                    </p>
                </div>
    </div>
    <!--form class=\"post_product\" name=\"postform\" id=\"postform\" method=\"POST\" action=\"\">
        <div class=\"post_container\">
            <label class=\"post_edit\" for=\"status_text\"><span>Edit your tweet</span>
              <textarea maxlength=\"120\" name=\"status_text\" id=\"status_text\">".$default_status_text."</textarea>
            </label>
            <label class=\"post_attach\" for=\"img_attach\"><span>Attach image</span>
                <input type=\"checkbox\" name=\"img_attach\" id=\"img_attach\" checked=\"true\">
            </label>
        <p><button value=\"true\" name=\"submited\" type=\"submit\">Post</button></p>
    </div>  
   </form-->
"
;
