function bindTwitter() {
    var newAccount = jQuery("#tc_user_twitter").val();
    var link_to_site = jQuery("#link_to_site").val();

    if (newAccount == "") {
        alert("Twitter account field must be not empty!");
    } else {
        jQuery.ajax({
            type: "post",
            dataType: "json",
            url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_bind_user_twitter",
            data: {account: newAccount},
            beforeSend: function() {
            },
            success: function(response) {
                alertify.alert(response);
            }
        });
    }
}

function tcLinkOauth() {
    var link_to_site = jQuery("#collector").attr('site_url');
    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_link_oauth"
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
        }
    });
}

function tcStatsUser() {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_get_twitter_timeline_frontend"
        , type: 'post'
        , dataType: 'json'
        , data: ''
        , beforeSend: function() {
            jQuery('#userStatBox').html("<p class='loader' style=\"margin-top: 50px;\"><img src='" + preloader_src + "'></p>");
        },
        success: function(res) {
            jQuery('#userStatBox').html(res);
        }
    });
}

function tcOptionsUser() {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');

    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_get_frontend_options"
        , type: 'post'
        , dataType: 'json'
        , data: ''
        , beforeSend: function() {
            jQuery('#userOptionsBox').html("<p class='loader' style=\"margin-top: 50px;\"><img src='" + preloader_src + "'></p>");
        },
        success: function(res) {
            jQuery('#userOptionsBox').html(res);
        }
    });
}

function updateFrontendOptions() {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');
    var msg = jQuery("#optionsform").serialize();
    var hashtag = jQuery("#hashtag").val();
    if (hashtag == '') {
        alert('#HashTag field can`t be empty!');
    } else {
        jQuery.ajax({
            url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_set_frontend_options"
            , type: 'post'
            , dataType: 'json'
            , data: msg
            , beforeSend: function() {
                jQuery('#userOptionsBox').html("<p class='loader' style=\"margin-top: 50px;\"><img src='" + preloader_src + "'></p>");
            },
            success: function(res) {
                tcOptionsUser();
            }
        });
    }
}

function tcUserReply(status_id, user_name, user_id) {
    jQuery("replyuser").html(user_name);
    jQuery("#reply_msg").val('@' + user_name + ' ');
    jQuery("#reply_status").css('display', 'none');
    jQuery(".tcofb").css('display', 'block');
    tcStatusId = status_id;
}

function tcDeactivateOauth() {
    var link_to_site = jQuery("#collector").attr('site_url');
    jQuery("#tclinkbutton").html("Link my twitter account");
    jQuery("#tcdeactivatebutton").css('display', 'none');
    jQuery("#tcuseraccount").css('display', 'none');
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

function tcTimelineUser() {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');
    jQuery("#inline_content").remove();
    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_user_timeline"
        , type: 'post'
        , dataType: 'json'
        , data: ''
        , beforeSend: function() {
            jQuery('#userTimelineBox').html("<p class='loader' style=\"margin-top: 50px;\"><img src='" + preloader_src + "'></p>");
        },
        success: function(res) {
            jQuery('#userTimelineBox').html(res.html);
        }
    });
}

function tcUserRetweet(id) {
    var link_to_site = jQuery("#collector").attr('site_url');
    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_user_retweet"
        , type: 'post'
        , dataType: 'json'
        , data: {status_id: id}
        , beforeSend: function() {

        },
        success: function(res) {
            if (res) {
                jQuery("#tc_retweet_" + id).css("background", "url('../images/retweet_on.png') no-repeat center");
                jQuery("#tc_retweet_" + id).attr("retweet_id", res);
                jQuery("#tc_retweet_" + id).attr("onclick", "tcUserRetweetDestroy('" + res + "', '" + id + "');");
            } else {
                alert("Some error! Try again later.");
            }
        }
    });
}


function tcFavoriteUser() {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');
    jQuery("#inline_content").remove();
    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_user_twitter_favorite"
        , type: 'post'
        , dataType: 'json'
        , data: ''
        , beforeSend: function() {
            jQuery('#userFavoriteBox').html("<p class='loader'><img src='" + preloader_src + "'></p>");
        },
        success: function(res) {
            jQuery('#userFavoriteBox').html(res);
        }
    });
}

function tcInboxUser() {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');
    jQuery("#inline_content").remove();
    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_user_twitter_inbox"
        , type: 'post'
        , dataType: 'json'
        , data: ''
        , beforeSend: function() {
            jQuery('#userInboxBox').html("<p class='loader'><img src='" + preloader_src + "'></p>");
        },
        success: function(res) {
            jQuery('#userInboxBox').html(res);
        }
    });
}

function tcOutboxUser() {
    var preloader_src = jQuery("#collector").attr('preloader_src');
    var link_to_site = jQuery("#collector").attr('site_url');
    jQuery("#inline_content").remove();
    jQuery.ajax({
        url: link_to_site + "/wp-admin/admin-ajax.php?action=tc_user_twitter_outbox"
        , type: 'post'
        , dataType: 'json'
        , data: ''
        , beforeSend: function() {
            jQuery('#userOutboxBox').html("<p class='loader'><img src='" + preloader_src + "'></p>");
        },
        success: function(res) {
            jQuery('#userOutboxBox').html(res);
        }
    });
}