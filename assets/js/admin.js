var fileSelected = false;
function updateAdminOptions() {
    var apiKey = jQuery("#twt_api_key").val();
    var apiSecret = jQuery("#twt_api_sec").val();
    var accessToken = jQuery("#acc_tok").val();
    var accessTokenSecret = jQuery("#acc_tok_sec").val();

    if (apiKey == '' || apiSecret == '' || accessToken == '' || accessTokenSecret == '') {
        alert('All fields is required!');
    } else {
        jQuery("#optionsform").submit();
    }
}
function saveVendorOptions() {
    var hashtag = jQuery("#hashtag").val();

    if (hashtag == '') {
        alert('All fields is required!');
    } else {
        jQuery("#optionsform").submit();
    }
}

function tcGetTimeline(productid) {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');

    window.followingCursor = 0;
    window.followersCursor = 0;
    window.timelineMinCursor = 0;

    jQuery(".active").removeClass('active');
    jQuery(".tchometimeline").addClass('active');

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_get_twitter_timeline"
        , type: 'post'
        , dataType: 'json'
        , data: {porduct: productid}
        , beforeSend: function() {
            jQuery('.tc_main_container').html("<div class=\"tcspinner\"><div class=\"rect1\"></div><div class=\"rect2\"></div><div class=\"rect3\"></div><div class=\"rect4\"></div><div class=\"rect5\"></div></div>");
        },
        success: function(res) {
            jQuery('.tc_main_container').html(res.html);
            jQuery('.tc_main_container').removeClass('following_container');
            window.timelineMinCursor = res.min;
        }
    });
}

function tcGetUserTimeline() {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');

    window.followingCursor = 0;
    window.followersCursor = 0;
    window.timelineMinCursor = 0;

    jQuery(".active").removeClass('active');
    jQuery(".tcgenerusrtmln").addClass('active');

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_get_profile_timeline"
        , type: 'post'
        , dataType: 'json'
        , data: ''
        , beforeSend: function() {
            jQuery('.tc_main_container').html("<div class=\"tcspinner\"><div class=\"rect1\"></div><div class=\"rect2\"></div><div class=\"rect3\"></div><div class=\"rect4\"></div><div class=\"rect5\"></div></div>");
        },
        success: function(res) {
            jQuery('.tc_main_container').html(res.html);
            window.timelineUserCursor = res.min;
            jQuery('.tc_main_container').removeClass('following_container');
        }
    });
}

function tcGetRetweets() {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');

    window.followingCursor = 0;
    window.followersCursor = 0;

    jQuery(".active").removeClass('active');
    jQuery(".tcgenerret").addClass('active');

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_get_twitter_retweets"
        , type: 'post'
        , dataType: 'json'
        , data: ''
        , beforeSend: function() {
            jQuery('.tc_main_container').html("<div class=\"tcspinner\"><div class=\"rect1\"></div><div class=\"rect2\"></div><div class=\"rect3\"></div><div class=\"rect4\"></div><div class=\"rect5\"></div></div>");
        },
        success: function(res) {
            jQuery('.tc_main_container').html(res);
            jQuery('.tc_main_container').removeClass('following_container');
        }
    });
}

function tcGetFollowers() {

    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');

    jQuery(".active").removeClass('active');
    jQuery(".tcgenerflwrs").addClass('active');

    window.followingCursor = 0;

    var data = '';

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_get_twitter_followers"
        , type: 'post'
        , dataType: 'json'
        , data: data
        , beforeSend: function() {
            jQuery('.tc_main_container').html("<div class=\"tcspinner\"><div class=\"rect1\"></div><div class=\"rect2\"></div><div class=\"rect3\"></div><div class=\"rect4\"></div><div class=\"rect5\"></div></div>");
        },
        success: function(res) {
            jQuery('.tc_main_container').addClass('following_container')
            jQuery('.tc_main_container').html(res.html);
            window.followersCursor = res.cursor;
        }
    });
}

function tcGetFollowing() {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');

    jQuery(".active").removeClass('active');
    jQuery(".tcgenerflwng").addClass('active');

    window.followersCursor = 0;

    var data = '';

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_get_twitter_following"
        , type: 'post'
        , dataType: 'json'
        , data: data
        , beforeSend: function() {
            jQuery('.tc_main_container').html("<div class=\"tcspinner\"><div class=\"rect1\"></div><div class=\"rect2\"></div><div class=\"rect3\"></div><div class=\"rect4\"></div><div class=\"rect5\"></div></div>");
        },
        success: function(res) {
            jQuery('.tc_main_container').addClass('following_container');
            jQuery('.tc_main_container').html(res.html);
            window.followingCursor = res.cursor;
        }
    });
}


function tcGetFavorite() {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');

    jQuery(".active").removeClass('active');
    jQuery(".tcgenerfvrt").addClass('active');

    window.followersCursor = 0;
    window.followingCursor = 0;

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_get_twitter_favorite"
        , type: 'post'
        , dataType: 'json'
        , data: ''
        , beforeSend: function() {
            jQuery('.tc_main_container').html("<div class=\"tcspinner\"><div class=\"rect1\"></div><div class=\"rect2\"></div><div class=\"rect3\"></div><div class=\"rect4\"></div><div class=\"rect5\"></div></div>");
        },
        success: function(res) {
            jQuery('.tc_main_container').html(res.html);
            jQuery('.tc_main_container').removeClass('following_container');
        }
    });
}


function tcAdminRetweet(id) {
    var link_to_site = jQuery("#collector").attr('site_url');
    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_admin_retweet"
        , type: 'post'
        , dataType: 'json'
        , data: {status_id: id}
        , beforeSend: function() {

        },
        success: function(res) {
            if (res) {
                jQuery("#tc_retweet_" + id).css("background", "url('../images/retweet_on.png') 0 1px no-repeat");
                jQuery("#tc_retweet_" + id).attr("retweet_id", res);
                jQuery("#tc_retweet_" + id).attr("onclick", "tcAdminRetweetDestroy('" + res + "', '" + id + "');");
            } else {
                alert("Some error! Try again later.");
            }
        }
    });
}

function tcAdminRetweetDestroy(id, status_id) {
    var link_to_site = jQuery("#collector").attr('site_url');
    var link_to_img = jQuery("#collector").attr('image_url');
    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_admin_retweet_destroy"
        , type: 'post'
        , dataType: 'json'
        , data: {status_id: id}
        , beforeSend: function() {

        },
        success: function(res) {
            jQuery("#tc_retweet_" + status_id).css("background", "url('" + link_to_img + "opt02.png') 0 1px no-repeat");
            jQuery("#tc_retweet_" + status_id).attr("onclick", "tcAdminRetweet('" + status_id + "');");
        }
    });
}

function tcAdminReply(status_id, user_name, user_id) {
    jQuery("#stats-rightreply").css('display', 'block');
    jQuery("replyuser").html(user_name);
    jQuery("#reply_msg").val('@' + user_name + ' ');
    jQuery("#reply_status").css('display', 'none');
    tcStatusId = status_id;
}

function tcAdminFavorite(id) {
    var link_to_site = jQuery("#collector").attr('site_url');
    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_admin_favorite"
        , type: 'post'
        , dataType: 'json'
        , data: {status_id: id}
        , beforeSend: function() {

        },
        success: function(res) {
            jQuery("#tc_favorite_" + id).css("background", "url('../images/favorite_on.png') 0 1px no-repeat");
            jQuery("#tc_favorite_" + id).attr("onclick", "tcAdminFavoriteDestroy('" + id + "');");
        }
    });
}

function tcAdminFavoriteDestroy(id) {
    var link_to_site = jQuery("#collector").attr('site_url');
    var link_to_img = jQuery("#collector").attr('image_url');
    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_admin_favorite_destroy"
        , type: 'post'
        , dataType: 'json'
        , data: {status_id: id}
        , beforeSend: function() {

        },
        success: function(res) {
            jQuery("#tc_favorite_" + id).css("background", "url('" + link_to_img + "opt03.png') 0 1px no-repeat");
            jQuery("#tc_favorite_" + id).attr("onclick", "tcAdminFavorite('" + id + "');");
        }
    });
}


function tcReply() {
    var link_to_site = jQuery("#collector").attr('site_url');
    var reply_msg = jQuery("#reply_msg").val();
    jQuery("#reply_status").css("color", "blue");
    jQuery("#reply_status").html("Sending reply...");
    jQuery("#reply_status").css('display', 'block');
    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_admin_reply"
        , type: 'post'
        , dataType: 'json'
        , data: {status_id: tcStatusId, reply_msg: reply_msg}
        , beforeSend: function() {

        },
        success: function(res) {
            if (res) {
                jQuery("#reply_status").css("color", "green");
                jQuery("#reply_status").html("Success!");
                setTimeout(function() {
                    jQuery("#stats-rightreply").fadeOut(600);
                }, 800);
            } else {
                jQuery("#reply_status").css("color", "red");
                jQuery("#reply_status").html("Some error! Try again later.");
                setTimeout(function() {
                    jQuery("#stats-rightreply").fadeOut(600);
                }, 5000);
            }
        }
    });
}

function tcDirectMessage(user_id, user_name) {
    jQuery("#stats-rightmessage").css('display', 'block');
    jQuery("directuser").html(user_name);
    jQuery("#direct_status").css('display', 'none');
    jQuery("#direct_msg").val(' ');
    tcUserName = user_name;
}

function tcDirectChatMessage(user_id, user_name) {

    var link_to_site = jQuery("#collector").attr('site_url');

    jQuery("#stats-right-dm-chat").css('display', 'block');
    jQuery("#stats-rightreply").css('display', 'none');
    jQuery("directuser").html(user_name);
    tcUserName = user_name;
    setInterval(function() {
        jQuery.ajax({
            url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_check_inbox"
            , type: 'post'
            , dataType: 'json'
            , data: {user: tcUserName}
            , beforeSend: function() {

            },
            success: function(res) {
                jQuery("#directbox").append(res);
                var elem = document.getElementById('directbox');
                elem.scrollTop = elem.scrollHeight;
            }
        });
    }, 10000);
}


function sendToDirectChat() {
    var text = jQuery("#dmtextarea").val();
    var link_to_site = jQuery("#collector").attr('site_url');
    jQuery("#directbox").append("<div class=\"tcdmoutbox\">" + text + "</div>");
    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_admin_directmessage"
        , type: 'post'
        , dataType: 'json'
        , data: {user: tcUserName, direct_msg: text}
        , beforeSend: function() {

        },
        success: function(res) {

        }
    });
}
function tcDirect() {
    var link_to_site = jQuery("#collector").attr('site_url');
    var direct_msg = jQuery("#direct_msg").val();

    jQuery("#direct_status").css("color", "blue");
    jQuery("#direct_status").html("Sending message...");
    jQuery("#direct_status").css('display', 'block');
    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_admin_directmessage"
        , type: 'post'
        , dataType: 'json'
        , data: {user: tcUserName, direct_msg: direct_msg}
        , beforeSend: function() {

        },
        success: function(res) {
            if (res) {
                jQuery("#direct_status").css("color", "green");
                jQuery("#direct_status").html("Success!");
                setTimeout(function() {
                    jQuery("#stats-rightmessage").fadeOut(600);
                }, 800);
            } else {
                jQuery("#direct_status").css("color", "red");
                jQuery("#direct_status").html("Some error! Try again later.");
                setTimeout(function() {
                    jQuery("#stats-rightmessage").fadeOut(600);
                }, 5000);
            }
        }
    });
}

function tcGetReplies() {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');

    window.followingCursor = 0;
    window.followersCursor = 0;

    jQuery(".active").removeClass('active');
    jQuery(".tcgenerrep").addClass('active');

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_get_twitter_replies"
        , type: 'post'
        , dataType: 'json'
        , data: ''
        , beforeSend: function() {

            jQuery('.tc_main_container').html("<div class=\"tcspinner\"><div class=\"rect1\"></div><div class=\"rect2\"></div><div class=\"rect3\"></div><div class=\"rect4\"></div><div class=\"rect5\"></div></div>");
        },
        success: function(res) {
            jQuery('.tc_main_container').html(res.html);
            jQuery('.tc_main_container').removeClass('following_container');
        }
    });
}

function tcGetDirectMessages() {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');

    jQuery('.tc_main_container').removeClass('following_container');

    window.followingCursor = 0;
    window.followersCursor = 0;

    jQuery(".active").removeClass('active');
    jQuery(".tcgenerdmsgs").addClass('active');

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_get_twitter_messages"
        , type: 'post'
        , dataType: 'json'
        , data: ''
        , beforeSend: function() {
            jQuery('.tc_main_container').html("<div class=\"tcspinner\"><div class=\"rect1\"></div><div class=\"rect2\"></div><div class=\"rect3\"></div><div class=\"rect4\"></div><div class=\"rect5\"></div></div>");
        },
        success: function(res) {
            jQuery('.tc_main_container').html(res.html);
        }
    });
}

function tcGetMostReplied() {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');

    jQuery('.active').removeClass('active');
    jQuery('.mrpld').addClass('active');

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_get_twitter_most_replied"
        , type: 'post'
        , dataType: 'json'
        , data: ''
        , beforeSend: function() {
            //jQuery('#content').html("<p class='loader' style=\"height: 400px; margin-top: 0px;\"><img style=\"margin-top: 200px;\" src='" + preloader_src + "'></p>");
            jQuery('#content').css('background', 'transparent');
            jQuery('#content').html("<div class=\"tcspinner\"><div class=\"rect1\"></div><div class=\"rect2\"></div><div class=\"rect3\"></div><div class=\"rect4\"></div><div class=\"rect5\"></div></div>");
        },
        success: function(res) {
            jQuery('#content').css('background', 'white');
            jQuery('#content').html(res.content);
            jQuery('#gistogramma').html('');
            jQuery('forscript').html(res.chart);
        }
    });
}

function tcGetMostRetweeted() {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');

    jQuery('.active').removeClass('active');
    jQuery('.mrtwtd').addClass('active');

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_get_twitter_most_retweeted"
        , type: 'post'
        , dataType: 'json'
        , data: ''
        , beforeSend: function() {
            //jQuery('#content').html("<p class='loader' style=\"height: 400px; margin-top: 0px;\"><img style=\"margin-top: 200px;\" src='" + preloader_src + "'></p>");
            //jQuery('#content').css('background', 'transparent');
            jQuery('#content').css('background', 'transparent');
            jQuery('#content').html("<div class=\"tcspinner\"><div class=\"rect1\"></div><div class=\"rect2\"></div><div class=\"rect3\"></div><div class=\"rect4\"></div><div class=\"rect5\"></div></div>");
        },
        success: function(res) {
            jQuery('#content').css('background', 'white');
            jQuery('#content').html(res.main);
            jQuery('#gistogramma').html('');
            jQuery('forscript').html(res.chart);
        }
    });
}


function tcGetAuthorized() {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_get_authorized"
        , type: 'post'
        , dataType: 'json'
        , data: ''
        , beforeSend: function() {
            jQuery('#stats-rightcontent').html("<p class='loader'><img src='" + preloader_src + "'></p>");
        },
        success: function(res) {
            jQuery('#stats-rightcontent').html(res.main);
            jQuery('#stats-right-summary-chart').html('');
            jQuery('forscript').html(res.chart);
        }
    });
}

function tcGetAuthorizedCustomers() {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');

    jQuery('.active').removeClass('active');
    jQuery('.autcst').addClass('active');

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_get_authorized_customers"
        , type: 'post'
        , dataType: 'json'
        , data: ''
        , beforeSend: function() {
            //jQuery('#content').html("<p class='loader' style=\"height: 400px; margin-top: 0px;\"><img style=\"margin-top: 200px;\" src='" + preloader_src + "'></p>");
            //jQuery('#content').css('background', 'transparent');
            jQuery('#content').css('background', 'transparent');
            jQuery('#content').html("<div class=\"tcspinner\"><div class=\"rect1\"></div><div class=\"rect2\"></div><div class=\"rect3\"></div><div class=\"rect4\"></div><div class=\"rect5\"></div></div>");
        },
        success: function(res) {
            jQuery('#content').css('background', 'white');
            jQuery('#content').html(res.main);
            jQuery('#gistogramma').html('');
            jQuery('forscript').html(res.chart);
        }
    });
}

function tcGetAuthorizedVendors() {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');

    jQuery('.active').removeClass('active');
    jQuery('.autvnd').addClass('active');

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_get_authorized_vendors"
        , type: 'post'
        , dataType: 'json'
        , data: ''
        , beforeSend: function() {
            //jQuery('#content').html("<p class='loader' style=\"height: 400px; margin-top: 0px;\"><img style=\"margin-top: 200px;\" src='" + preloader_src + "'></p>");
            //jQuery('#content').css('background', 'transparent');
            jQuery('#content').css('background', 'transparent');
            jQuery('#content').html("<div class=\"tcspinner\"><div class=\"rect1\"></div><div class=\"rect2\"></div><div class=\"rect3\"></div><div class=\"rect4\"></div><div class=\"rect5\"></div></div>");
        },
        success: function(res) {
            jQuery('#content').css('background', 'white');
            jQuery('#content').html(res.main);
            jQuery('#gistogramma').html('');
            jQuery('forscript').html(res.chart);
        }
    });
}

function tcDeactivateOauth() {
    var link_to_site = jQuery("#collector").attr('site_url');
    jQuery("#tclinkbutton").html("Link my twitter account");
    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_deactivate_account"
        , type: 'post'
        , dataType: 'json'
        , data: ''
        , beforeSend: function() {

        },
        success: function(res) {
            alert("Your Twitter account was successfully deactivated!");
        }
    });
}

function tcWhoRetweeted(status_id, product_title) {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    jQuery("#forret").css('display', 'block');
    jQuery("#forrep").css('display', 'none');

    var link_to_site = jQuery("#collector").attr('site_url');

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_get_who_retweeted"
        , type: 'post'
        , dataType: 'json'
        , data: {status_id: status_id}
        , beforeSend: function() {
            jQuery('#retweetedlist').html("<p class='loader' style=\"margin-top: 10px;\"><img src='" + preloader_src + "'></p>");
        },
        success: function(res) {
            jQuery('#retweetedlist').html(res.main);
        }
    });
}

function tcWhoReplied(status_id, product_title) {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    jQuery("#forret").css('display', 'none');
    jQuery("#forrep").css('display', 'block');

    var link_to_site = jQuery("#collector").attr('site_url');

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_get_who_replied"
        , type: 'post'
        , dataType: 'json'
        , data: {status_id: status_id}
        , beforeSend: function() {
            jQuery('#repliedlist').html("<p class='loader' style=\"margin-top: 10px;\"><img src='" + preloader_src + "'></p>");
        },
        success: function(res) {
            jQuery('#repliedlist').html(res.main);
        }
    });
}

/**
 * Vendor functions
 */
function tcLinkOauthVendor() {
    var link_to_site = jQuery("#collector").attr('site_url');
    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_link_oauth_vendor"
        , type: 'post'
        , dataType: 'json'
        , data: ''
        , beforeSend: function() {

        },
        success: function(res) {

            newWin = window.open(res,
                    "Twitter OAuth",
                    "width=700,height=800,resizable=yes,scrollbars=yes,status=yes"
                    );
            newWin.focus();

            setTimeout(function() {
                if (newWin.closed) {
                    location.reload();
                } else {
                    setTimeout(arguments.callee, 10);
                }
            }, 10);

            //document.location = res;
        }
    });
}

function tcSendDevRequest() {
    var link_to_site = jQuery("tcollector").attr('site_url');
    var message = jQuery("#SupportForm").val();

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_dev_request"
        , type: 'post'
        , dataType: 'json'
        , data: {message: message}
        , beforeSend: function() {

        },
        success: function(res) {

        }
    });
    alert('Your request was successfully sent!');
}

function setSort(element, selector) {
    if (jQuery(element).hasClass('sorting_desc')) {
        jQuery('.sortable_img').css('display', 'none');
        jQuery('.sortable_img.both').css('display', 'inline');
        jQuery('img.' + selector + '.both').css('display', 'none');
        jQuery('img.' + selector + '.asc').css('display', 'inline');
        jQuery('img.' + selector + '.asc').css('position', 'absolute');
    } else if (jQuery(element).hasClass('sorting_asc')) {
        jQuery('.sortable_img').css('display', 'none');
        jQuery('.sortable_img.both').css('display', 'inline');
        jQuery('img.' + selector + '.both').css('display', 'none');
        jQuery('img.' + selector + '.desc').css('display', 'inline');
        jQuery('img.' + selector + '.desc').css('position', 'absolute');

    } else {
        jQuery('.sortable_img').css('display', 'none');
        jQuery('.sortable_img.both').css('display', 'inline');
        jQuery('img.' + selector + '.both').css('display', 'none');
        jQuery('img.' + selector + '.asc').css('display', 'inline');
        jQuery('img.' + selector + '.asc').css('position', 'absolute');
    }
    jQuery('.both').css('position', 'absolute');
}

function setArrow(element, selector) {
    jQuery('.sortable_img').css('display', 'none');
    jQuery('.' + selector + ' .sortable_img.bothwhite').css('display', 'inline');

}

function getActivityWidgetTotalUpdate() {
    var link_to_site = jQuery("tcollector").attr('site_url');
    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_activity_widget_total_update"
        , type: 'post'
        , dataType: 'json'
        , data: ''
        , beforeSend: function() {

        },
        success: function(res) {
            jQuery('tctotalposted').html(res.totalposted);
            //jQuery('tcmostrepliedname').html(res.mostreplied.product);
            //jQuery('tcmostrepliedcount').html(res.mostreplied.count);
        }
    });
}

function getActivityWidgetRepliesUpdate() {
    var link_to_site = jQuery("tcollector").attr('site_url');
    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_activity_widget_most_replied_update"
        , type: 'post'
        , dataType: 'json'
        , data: ''
        , beforeSend: function() {

        },
        success: function(res) {
            jQuery('tcmostrepliedname').html(res.mostreplied.product);
            jQuery('tcmostrepliedcount').html(res.mostreplied.count);
        }
    });
}

function getActivityWidgetRetweetsUpdate() {
    var link_to_site = jQuery("tcollector").attr('site_url');
    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_activity_widget_most_retweeted_update"
        , type: 'post'
        , dataType: 'json'
        , data: ''
        , beforeSend: function() {

        },
        success: function(res) {
            jQuery('tcmostretweetedname').html(res.mostretweeted.product);
            jQuery('tcmostretweetedcount').html(res.mostretweeted.count);
        }
    });
}

function tcGetTimelineUpdate() {
    var link_to_site = jQuery("tcollector").attr('site_url');
    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_timeline_update"
        , type: 'post'
        , dataType: 'json'
        , data: ''
        , beforeSend: function() {

        },
        success: function(res) {
            console.log(res);
        }
    });
}

function updateFollowing() {
    if (window.followingCursor > 0) {
        var link_to_site = jQuery("#collector").attr('site_url');
        data = {cursor: window.followingCursor};
        jQuery.ajax({
            url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_get_twitter_following"
            , type: 'post'
            , dataType: 'json'
            , data: data
            , beforeSend: function() {
            },
            success: function(res) {
                jQuery('.flwngcnt').append(res.html);
                window.followingCursor = res.cursor;
            }
        });
    }
}

function updateFollowers() {
    if (window.followersCursor > 0) {
        var link_to_site = jQuery("#collector").attr('site_url');
        data = {cursor: window.followersCursor};
        jQuery.ajax({
            url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_get_twitter_followers"
            , type: 'post'
            , dataType: 'json'
            , data: data
            , beforeSend: function() {
            },
            success: function(res) {
                jQuery('.flwrscnt').append(res.html);
                window.followersCursor = res.cursor;
            }
        });
    }
}

function showNewTweets() {
    jQuery('.twt_view').css('display', 'none');
    jQuery('.new_tweet_vw').css('display', 'inline-block');
}

function textareaChanged(textarea) {
    var contain = jQuery(textarea).val();
    var count = contain.length;
    if (fileSelected) {
        var left = 123 - count;
    } else {
        var left = 140 - count;
    }
    jQuery('.symbols').html(left);
    console.log(count);
}

function checkFileSelected() {
    if (fileSelected) {
        fileSelected = false;
        jQuery("#whatsNew").attr('maxlength', '140');
    } else {
        fileSelected = true;
        jQuery("#whatsNew").attr('maxlength', '123');
    }
}

function tweetSubmit(form) {
    var link_to_site = jQuery("collector").attr('site_url');
    var msg = jQuery('#whatsNew').val();

    var inreply = jQuery('#inreply').val();
    var file_data = jQuery('#file-input').prop('files')[0];
    var form_data = new FormData();
    form_data.append('file', file_data);
    form_data.append('msg', msg);

    var hiddenid = jQuery("#hiddenid").val();

    if (inreply && inreply != '' && inreply != false && inreply != 'false') {
        form_data.append('inreply', inreply);
        jQuery.ajax({
            url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_new_tweet_reply"
            , type: 'post'
            , dataType: 'json'
            , data: form_data
            , contentType: false
            , processData: false
            , beforeSend: function() {

            },
            success: function(res) {
                if (res === true) {
                    alert('Your reply was successfully sent!');
                } else {
                    alert(res);
                }
            }
        });
    } else if (hiddenid > 0) {
        postNewSend();
    } else {
        jQuery.ajax({
            url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_new_tweet"
            , type: 'post'
            , dataType: 'json'
            , data: form_data
            , contentType: false
            , processData: false
            , beforeSend: function() {

            },
            success: function(res) {
                if (res === true) {
                    alert('Your tweet was successfully sent!');
                } else {
                    alert(res);
                }
            }
        });
    }
    jQuery('#whatsNew').val(' ');
}

function updateNotPosted() {
    var link_to_site = jQuery("collector").attr('site_url');
    var preloader_src = jQuery("#collector").attr('preloader_src');

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_update_not_posted"
        , type: 'post'
        , dataType: 'json'
        , data: ''
        , beforeSend: function() {
            jQuery("#tcnotposted").html("<p class='loader' style=\"margin-top: auto;\"><img src='" + preloader_src + "'></p>");
        },
        success: function(res) {
            jQuery("#tcnotposted").html(res);
        }
    });
}

function postNew(title, price, link, element, id) {
    jQuery("#sendedres").css('display', 'none');
    jQuery(".hideforres").css('display', 'block');
    var prepost = title + " - " + price + "  \r\n\ " + link;
    jQuery("#whatsNew").html(prepost);
    jQuery("#whatsNew").val(prepost);
    jQuery("#hiddenid").val(id);
    jQuery("#productAttachCheck").css('display', 'inline');
    jQuery("#productAttach").css('checked', 'true');
    jQuery("#whatsNew").focus();
}

function postNewSend() {
    var link_to_site = jQuery("collector").attr('site_url');
    var preloader_src = jQuery("#collector").attr('preloader_src');

    var status_text = jQuery("#whatsNew").val();
    var product_id = jQuery("#hiddenid").val();
    var attach_image = jQuery("#productAttach").val();

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_post_product_account"
        , type: 'post'
        , dataType: 'json'
        , data: {message: status_text, product: product_id, attach: attach_image}
        , beforeSend: function() {
        },
        success: function(res) {
            alertify.alert(res);
            jQuery("#productAttachCheck").css('display', 'none');
            jQuery("#whatsNew").html('');
            jQuery("#hiddenid").val('0');
        }
    });
}


function tcPostReply(id, user) {
    jQuery("#whatsNew").val(user);
    jQuery("#whatsNew").focus();
    jQuery("#inreply").val(id);
}

function reloc(location) {
    window.location = location;
}

function prepUnfollow(button) {
    jQuery(button).val('Follow');
}

function retFollow(button) {
    jQuery(button).val('Following');
}

function tcUnfollow(screen_name, element) {
    var link_to_site = jQuery("collector").attr('site_url');
    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_unfollow"
        , type: 'post'
        , dataType: 'json'
        , data: {name: screen_name}
        , beforeSend: function() {
        },
        success: function(res) {
        }
    });
    jQuery(element).attr('onclick', "tcFollow('" + screen_name + "', this);");
    var onmo = jQuery(element).attr('onmouseover');
    var onmout = jQuery(element).attr('onmouseout');
    jQuery(element).attr('onmouseover', onmout);
    jQuery(element).attr('onmouseout', onmo);
    jQuery(element).val('Follow');
}

function tcFollow(screen_name, element) {
    var link_to_site = jQuery("collector").attr('site_url');
    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_follow"
        , type: 'post'
        , dataType: 'json'
        , data: {name: screen_name}
        , beforeSend: function() {
        },
        success: function(res) {
        }
    });
    jQuery(element).attr('onclick', "tcUnfollow('" + screen_name + "', this);");
    var onmo = jQuery(element).attr('onmouseover');
    var onmout = jQuery(element).attr('onmouseout');
    jQuery(element).attr('onmouseover', onmout);
    jQuery(element).attr('onmouseout', onmo);
    jQuery(element).val('Following');
}

function tcOtherUserTimeline(username) {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');

    window.followingCursor = 0;
    window.followersCursor = 0;

    jQuery(".active").removeClass('active');

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_get_smbd_timeline"
        , type: 'post'
        , dataType: 'json'
        , data: {user: username}
        , beforeSend: function() {
            jQuery('.tc_main_container').html("<p class='loader'><img src='" + preloader_src + "'></p>");
        },
        success: function(res) {
            jQuery('.tc_main_container').html(res.html);
            window.cursorMinUT = res.min;
            jQuery('.tc_main_container').removeClass('following_container');
        }
    });
}

function earlierTweets() {
    if (jQuery(".tchometimeline").hasClass('active')) {
        var link_to_site = jQuery("collector").attr('site_url');
        jQuery.ajax({
            url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_timeline_earlier"
            , type: 'post'
            , dataType: 'json'
            , data: {cursor: window.timelineMinCursor}
            , beforeSend: function() {

            },
            success: function(res) {
                if (res.count != 0) {
                    window.timelineMinCursor = res.min;
                    jQuery('#tctwtcont').append(res.html);
                }
            }
        });
    }
}

function getDMChat(username) {
    jQuery('withbox').html(username);
    jQuery('.side_bar').css('display', 'block');
    var ident = "#collector_" + username;
    var avatar = jQuery(ident).attr('avatar');
    var ftext = jQuery(ident).attr('ftext');
    var fid = jQuery(ident).attr('fid');
    var type = jQuery(ident).attr('typeof');
    window.dmcursor = jQuery(ident).attr('maxid');
    window.dmuser = username;
    if (type == 'inbox') {
        var firstmessage = "<div class=\"opp right\" id=\"mid" + fid + "\" onmouseover=\"showTrashAct('" + fid + "');\" onmouseout=\"cleanDisplayShow();\"><img src=\"" + jQuery("collector").attr('image_url') + "trash.png\" class=\"trashpan\" alt=\"Delete this message\" onclick=\"delThisMessage('" + fid + "');\"><img class=\"ava\" width=\"46\" height=\"46\" src=\"" + avatar + "\" alt=\"ava\"><ul><li><span>" + ftext + "</span></li></ul></div>";
    } else {
        var avatar = jQuery(".user .user_info .ava").attr('src');
        var firstmessage = "<div class=\"opp left\" id=\"mid" + fid + "\" onmouseover=\"showTrashAct('" + fid + "');\" onmouseout=\"cleanDisplayShow();\"><img src=\"" + jQuery("collector").attr('image_url') + "trash.png\" class=\"trashpan\" alt=\"Delete this message\" onclick=\"delThisMessage('" + fid + "');\"><img class=\"ava\" width=\"46\" height=\"46\" src=\"" + avatar + "\" alt=\"ava\"><ul><li><span>" + ftext + "</span></li></ul></div>";
    }
    jQuery('.chat_main').html(firstmessage);
    setInterval(function() {
        var link_to_site = jQuery("collector").attr('site_url');
        jQuery.ajax({
            url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_dm_update"
            , type: 'post'
            , dataType: 'json'
            , data: {cursor: window.dmcursor, user: window.dmuser}
            , beforeSend: function() {

            },
            success: function(res) {
                window.dmcursor = res.cursor;
                jQuery(ident).attr('maxid', res.cursor);
                jQuery('.chat_main').append(res.html);
            }
        });
    }, 15000);
}

function showTrashAct(fid) {
    var ident = '#mid' + fid + ' .trashpan';
    jQuery(ident).css('display', 'block');
}

function cleanDisplayShow() {
    jQuery('.trashpan').css('display', 'none');
}

function delThisMessage(fid) {
    var ident = '#mid' + fid;
    jQuery(ident).remove();
    var link_to_site = jQuery("collector").attr('site_url');
    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_dm_delete"
        , type: 'post'
        , dataType: 'json'
        , data: {id: fid}
        , beforeSend: function() {

        },
        success: function(res) {

        }
    });

}

function sendDm() {
    var text = jQuery("#new_message").val();
    var avatar = jQuery(".user .user_info .ava").attr('src');
    var message = "<div class=\"opp left\" id=\"forchange\" onmouseout=\"cleanDisplayShow();\"><img src=\"" + jQuery("collector").attr('image_url') + "trash.png\" class=\"trashpan\" alt=\"Delete this message\"><img class=\"ava\" width=\"46\" height=\"46\" src=\"" + avatar + "\" alt=\"ava\"><ul><li><span>" + text + "</span></li></ul></div>";
    jQuery('.chat_main').append(message);
    var block = document.getElementsByClassName("chat_wrap");
    block.scrollTop = 9999;

    var link_to_site = jQuery("#collector").attr('site_url');
    //window.dmcursor = jQuery(ident).attr('maxid');
    jQuery("#new_message").val('');

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_admin_directmessage"
        , type: 'post'
        , dataType: 'json'
        , data: {user: window.dmuser, direct_msg: text}
        , beforeSend: function() {

        },
        success: function(res) {
            jQuery('#forchange').attr('id', 'mid' + res.id);
            var ident = "#mid" + res.id;
            jQuery(ident).attr('onmouseover', "showTrashAct('" + res.id + "');");
            ident = ident + '>img.trashpan';
            jQuery(ident).attr('onclick', "delThisMessage('" + res.id + "');");
        }
    });
}


function getWidgetTimeline() {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_get_widget_timeline"
        , type: 'post'
        , dataType: 'json'
        , data: {}
        , beforeSend: function() {
            //jQuery('div.tab_timeline').html("<p class='loader' style=\"padding-top: 200px;\"><img src='" + preloader_src + "'></p>");
            jQuery('div.tab_timeline').html("<div class=\"tcspinner\" style=\"top: 0;\"><div class=\"rect1\"></div><div class=\"rect2\"></div><div class=\"rect3\"></div><div class=\"rect4\"></div><div class=\"rect5\"></div></div>");
        },
        success: function(res) {
            jQuery('div.tab_timeline').html(res.html);
        }
    });
}

function getWidgetReplies() {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_get_widget_replies"
        , type: 'post'
        , dataType: 'json'
        , data: {}
        , beforeSend: function() {
            //jQuery('div.tab_replies').html("<p class='loader' style=\"padding-top: 200px;\"><img src='" + preloader_src + "'></p>");
            jQuery('div.tab_replies').html("<div class=\"tcspinner\" style=\"top: 0;\"><div class=\"rect1\"></div><div class=\"rect2\"></div><div class=\"rect3\"></div><div class=\"rect4\"></div><div class=\"rect5\"></div></div>");
        },
        success: function(res) {
            jQuery('div.tab_replies').html(res.html);
        }
    });
}

function getWidgetFavorites() {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_get_widget_favorites"
        , type: 'post'
        , dataType: 'json'
        , data: {}
        , beforeSend: function() {
            //jQuery('div.tab_favorites').html("<p class='loader' style=\"padding-top: 200px;\"><img src='" + preloader_src + "'></p>");
            jQuery('div.tab_favorites').html("<div class=\"tcspinner\" style=\"top: 0;\"><div class=\"rect1\"></div><div class=\"rect2\"></div><div class=\"rect3\"></div><div class=\"rect4\"></div><div class=\"rect5\"></div></div>");
        },
        success: function(res) {
            jQuery('div.tab_favorites').html(res.html);
        }
    });
}

function getWidgetRetweeted() {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_get_widget_retweeted"
        , type: 'post'
        , dataType: 'json'
        , data: {}
        , beforeSend: function() {
            jQuery('div.tab_retweeted').html("<p class='loader' style=\"padding-top: 200px;\"><img src='" + preloader_src + "'></p>");
            jQuery('div.tab_retweeted').html("<div class=\"tcspinner\" style=\"top: 0;\"><div class=\"rect1\"></div><div class=\"rect2\"></div><div class=\"rect3\"></div><div class=\"rect4\"></div><div class=\"rect5\"></div></div>");
        },
        success: function(res) {
            jQuery('div.tab_retweeted').html(res.html);
        }
    });
}

function setBothSort(element, classname) {
    if (!(jQuery(element).hasClass('sorting_desc') || jQuery(element).hasClass('sorting_asc'))) {
        var selector = 'img.' + classname;
        jQuery(selector).css('display', 'none');
        var currentselector = 'th.' + classname + ' .allhover';
        jQuery(currentselector).css('display', 'inline');
        console.log(currentselector);
    }
}

function setReturnedSort(element, classname) {
    if (!(jQuery(element).hasClass('sorting_desc') || jQuery(element).hasClass('sorting_asc'))) {
        var selector = 'th.' + classname + ' img';
        jQuery(selector).css('display', 'none');
        var currentselector = 'th.' + classname + ' .both';
        jQuery(currentselector).css('display', 'inline');
        console.log(currentselector);
    }
}


jQuery(document).ready(function() {
    jQuery("#tc-home-timeline").click(function() {
        tcGetTimeline();
    });
    jQuery("#tc-replies").click(function() {
        tcGetReplies();
    });
    jQuery("#tc-retweets").click(function() {
        tcGetRetweets();
    });
    jQuery("#tc-followers").click(function() {
        tcGetFollowers();
    });
    jQuery("#tc-following").click(function() {
        tcGetFollowing();
    });
    jQuery("#tc-inbox").click(function() {
        tcGetInbox();
    });
    jQuery("#tc-outbox").click(function() {
        tcGetOutbox();
    });
    jQuery("#tc-favorite").click(function() {
        tcGetFavorite();
    });
    jQuery("#tc-most-replied").click(function() {
        tcGetMostReplied();
    });
    jQuery("#tc-most-retweeted").click(function() {
        tcGetMostRetweeted();
    });
    jQuery("#tc-authorized").click(function() {
        tcGetAuthorized();
    });
    jQuery("#tc-authorized-customers").click(function() {
        tcGetAuthorizedCustomers();
    });
    jQuery("#tc-authorized-vendors").click(function() {
        tcGetAuthorizedVendors();
    });

    var tcStatusId = '0';
    var tcUserName = '0';
    var outboxInterval = 0;
    var followingCursor = 0;
    var followersCursor = 0;
    var timelineMinCursor = -1;
    var timelineUserCursor = -1;

    var menu_nav = jQuery('.nav_bar>nav>ul>li');
    var menu_head = jQuery('#header>ul>li');
    function colors(menu1, menu2, r_bord1, r_bord2) {
        menu1.children('a').hover(function() {
            var num = jQuery(this).parent().index();
            menu2.eq(num).children('a').css({'background-color': '#26b8ea', 'border-bottom-color': '#1da4d2', 'border-right-color': r_bord1})
        }, function() {
            var num = jQuery(this).parent().index();
            menu2.eq(num).children('a').css({'background-color': '#727b88', 'border-bottom-color': '#606874', 'border-right-color': r_bord2})
        })
    }
    colors(menu_nav, menu_head, '#527c92', '#606874');
    colors(menu_head, menu_nav);

    jQuery(".user_about").mCustomScrollbar({
        autoHideScrollbar: true,
        theme: "rounded"
    });
    jQuery("#products_filter>label").css('width', '80px');
    jQuery("#products_filter>label input").css('width', '120px');
    jQuery("#products_filter>label input").css('height', '35px');
    jQuery("#products_filter>label input").css('margin', '0');
    jQuery("#products_filter>label input").css('margin-left', '10px');
    jQuery("#products_filter>label input").after("<div class=\"searchboxtc\"></div>");

    jQuery('.nav_bar').scrollToFixed({marginTop: 40});

});

(function(a) {
    a.isScrollToFixed = function(b) {
        return !!a(b).data("ScrollToFixed")
    };
    a.ScrollToFixed = function(d, i) {
        var l = this;
        l.$el = a(d);
        l.el = d;
        l.$el.data("ScrollToFixed", l);
        var c = false;
        var G = l.$el;
        var H;
        var E;
        var e;
        var y;
        var D = 0;
        var q = 0;
        var j = -1;
        var f = -1;
        var t = null;
        var z;
        var g;
        function u() {
            G.trigger("preUnfixed.ScrollToFixed");
            k();
            G.trigger("unfixed.ScrollToFixed");
            f = -1;
            D = G.offset().top;
            q = G.offset().left;
            if (l.options.offsets) {
                q += (G.offset().left - G.position().left)
            }
            if (j == -1) {
                j = q
            }
            H = G.css("position");
            c = true;
            if (l.options.bottom != -1) {
                G.trigger("preFixed.ScrollToFixed");
                w();
                G.trigger("fixed.ScrollToFixed")
            }
        }
        function n() {
            var I = l.options.limit;
            if (!I) {
                return 0
            }
            if (typeof (I) === "function") {
                return I.apply(G)
            }
            return I
        }
        function p() {
            return H === "fixed"
        }
        function x() {
            return H === "absolute"
        }
        function h() {
            return !(p() || x())
        }
        function w() {
            if (!p()) {
                t.css({display: G.css("display"), width: G.outerWidth(true), height: G.outerHeight(true), "float": G.css("float")});
                cssOptions = {"z-index": l.options.zIndex, position: "fixed", top: l.options.bottom == -1 ? s() : "", bottom: l.options.bottom == -1 ? "" : l.options.bottom, "margin-left": "0px"};
                if (!l.options.dontSetWidth) {
                    cssOptions.width = G.width()
                }
                G.css(cssOptions);
                G.addClass(l.options.baseClassName);
                if (l.options.className) {
                    G.addClass(l.options.className)
                }
                H = "fixed"
            }
        }
        function b() {
            var J = n();
            var I = q;
            if (l.options.removeOffsets) {
                I = "";
                J = J - D
            }
            cssOptions = {position: "absolute", top: J, left: I, "margin-left": "0px", bottom: ""};
            if (!l.options.dontSetWidth) {
                cssOptions.width = G.width()
            }
            G.css(cssOptions);
            H = "absolute"
        }
        function k() {
            if (!h()) {
                f = -1;
                t.css("display", "none");
                G.css({"z-index": y, width: "", position: E, left: "", top: e, "margin-left": ""});
                G.removeClass("scroll-to-fixed-fixed");
                if (l.options.className) {
                    G.removeClass(l.options.className)
                }
                H = null
            }
        }
        function v(I) {
            if (I != f) {
                G.css("left", q - I);
                f = I
            }
        }
        function s() {
            var I = l.options.marginTop;
            if (!I) {
                return 0
            }
            if (typeof (I) === "function") {
                return I.apply(G)
            }
            return I
        }
        function A() {
            if (!a.isScrollToFixed(G)) {
                return
            }
            var K = c;
            if (!c) {
                u()
            } else {
                if (h()) {
                    D = G.offset().top;
                    q = G.offset().left
                }
            }
            var I = a(window).scrollLeft();
            var L = a(window).scrollTop();
            var J = n();
            if (l.options.minWidth && a(window).width() < l.options.minWidth) {
                if (!h() || !K) {
                    o();
                    G.trigger("preUnfixed.ScrollToFixed");
                    k();
                    G.trigger("unfixed.ScrollToFixed")
                }
            } else {
                if (l.options.maxWidth && a(window).width() > l.options.maxWidth) {
                    if (!h() || !K) {
                        o();
                        G.trigger("preUnfixed.ScrollToFixed");
                        k();
                        G.trigger("unfixed.ScrollToFixed")
                    }
                } else {
                    if (l.options.bottom == -1) {
                        if (J > 0 && L >= J - s()) {
                            if (!x() || !K) {
                                o();
                                G.trigger("preAbsolute.ScrollToFixed");
                                b();
                                G.trigger("unfixed.ScrollToFixed")
                            }
                        } else {
                            if (L >= D - s()) {
                                if (!p() || !K) {
                                    o();
                                    G.trigger("preFixed.ScrollToFixed");
                                    w();
                                    f = -1;
                                    G.trigger("fixed.ScrollToFixed")
                                }
                                v(I)
                            } else {
                                if (!h() || !K) {
                                    o();
                                    G.trigger("preUnfixed.ScrollToFixed");
                                    k();
                                    G.trigger("unfixed.ScrollToFixed")
                                }
                            }
                        }
                    } else {
                        if (J > 0) {
                            if (L + a(window).height() - G.outerHeight(true) >= J - (s() || -m())) {
                                if (p()) {
                                    o();
                                    G.trigger("preUnfixed.ScrollToFixed");
                                    if (E === "absolute") {
                                        b()
                                    } else {
                                        k()
                                    }
                                    G.trigger("unfixed.ScrollToFixed")
                                }
                            } else {
                                if (!p()) {
                                    o();
                                    G.trigger("preFixed.ScrollToFixed");
                                    w()
                                }
                                v(I);
                                G.trigger("fixed.ScrollToFixed")
                            }
                        } else {
                            v(I)
                        }
                    }
                }
            }
        }
        function m() {
            if (!l.options.bottom) {
                return 0
            }
            return l.options.bottom
        }
        function o() {
            var I = G.css("position");
            if (I == "absolute") {
                G.trigger("postAbsolute.ScrollToFixed")
            } else {
                if (I == "fixed") {
                    G.trigger("postFixed.ScrollToFixed")
                } else {
                    G.trigger("postUnfixed.ScrollToFixed")
                }
            }
        }
        var C = function(I) {
            if (G.is(":visible")) {
                c = false;
                A()
            }
        };
        var F = function(I) {
            (!!window.requestAnimationFrame) ? requestAnimationFrame(A) : A()
        };
        var B = function() {
            var J = document.body;
            if (document.createElement && J && J.appendChild && J.removeChild) {
                var L = document.createElement("div");
                if (!L.getBoundingClientRect) {
                    return null
                }
                L.innerHTML = "x";
                L.style.cssText = "position:fixed;top:100px;";
                J.appendChild(L);
                var M = J.style.height, N = J.scrollTop;
                J.style.height = "3000px";
                J.scrollTop = 500;
                var I = L.getBoundingClientRect().top;
                J.style.height = M;
                var K = (I === 100);
                J.removeChild(L);
                J.scrollTop = N;
                return K
            }
            return null
        };
        var r = function(I) {
            I = I || window.event;
            if (I.preventDefault) {
                I.preventDefault()
            }
            I.returnValue = false
        };
        l.init = function() {
            l.options = a.extend({}, a.ScrollToFixed.defaultOptions, i);
            y = G.css("z-index");
            l.$el.css("z-index", l.options.zIndex);
            t = a("<div />");
            H = G.css("position");
            E = G.css("position");
            e = G.css("top");
            if (h()) {
                l.$el.after(t)
            }
            a(window).bind("resize.ScrollToFixed", C);
            a(window).bind("scroll.ScrollToFixed", F);
            if ("ontouchmove" in window) {
                a(window).bind("touchmove.ScrollToFixed", A)
            }
            if (l.options.preFixed) {
                G.bind("preFixed.ScrollToFixed", l.options.preFixed)
            }
            if (l.options.postFixed) {
                G.bind("postFixed.ScrollToFixed", l.options.postFixed)
            }
            if (l.options.preUnfixed) {
                G.bind("preUnfixed.ScrollToFixed", l.options.preUnfixed)
            }
            if (l.options.postUnfixed) {
                G.bind("postUnfixed.ScrollToFixed", l.options.postUnfixed)
            }
            if (l.options.preAbsolute) {
                G.bind("preAbsolute.ScrollToFixed", l.options.preAbsolute)
            }
            if (l.options.postAbsolute) {
                G.bind("postAbsolute.ScrollToFixed", l.options.postAbsolute)
            }
            if (l.options.fixed) {
                G.bind("fixed.ScrollToFixed", l.options.fixed)
            }
            if (l.options.unfixed) {
                G.bind("unfixed.ScrollToFixed", l.options.unfixed)
            }
            if (l.options.spacerClass) {
                t.addClass(l.options.spacerClass)
            }
            G.bind("resize.ScrollToFixed", function() {
                t.height(G.height())
            });
            G.bind("scroll.ScrollToFixed", function() {
                G.trigger("preUnfixed.ScrollToFixed");
                k();
                G.trigger("unfixed.ScrollToFixed");
                A()
            });
            G.bind("detach.ScrollToFixed", function(I) {
                r(I);
                G.trigger("preUnfixed.ScrollToFixed");
                k();
                G.trigger("unfixed.ScrollToFixed");
                a(window).unbind("resize.ScrollToFixed", C);
                a(window).unbind("scroll.ScrollToFixed", F);
                G.unbind(".ScrollToFixed");
                t.remove();
                l.$el.removeData("ScrollToFixed")
            });
            C()
        };
        l.init()
    };
    a.ScrollToFixed.defaultOptions = {marginTop: 0, limit: 0, bottom: -1, zIndex: 1000, baseClassName: "scroll-to-fixed-fixed"};
    a.fn.scrollToFixed = function(b) {
        return this.each(function() {
            (new a.ScrollToFixed(this, b))
        })
    }
})(jQuery);