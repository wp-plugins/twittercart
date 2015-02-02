<?php
defined('ABSPATH') or die('Access denied!');

return "
    <tcollector site_url=\"".BASE_SITE_URL."\"></tcollector>
    <div id=\"wrapper\" class=\"upd\" style=\"width: 95%; margin: 0 auto;\">
			<header id=\"header\">
                <h1 id=\"headertitle\">TwitterCart Support</h1>
			</header>
			<div id=\"main\">
				<div class=\"update_container\">
                    <div class=\"update_menu\">
                        <span class=\"active\">Latest Update</span>
                        <span>Update history</span>
                    </div>
                    <div class=\"update_history\">
                        <div class=\"date_update\" style=\"display: none;\">
                            <div class=\"update_days\">
                                <span>Choose<br/>the day:<br/><a href=\"#\"><img src=\"".TC_IMG_URL."upd_ico03.png\" width=\"25\" height=\"25\" alt=\"day\"/></a></span>
                                <div class=\"update_calendar\">
                                    <table id=\"calendar\">
                                        <thead>
                                        <tr>
                                            <td colspan=\"2\"><img src=\"".TC_IMG_URL."calendar_ico01.png\" alt=\"left\"/></td>
                                            <td colspan=\"3\"></td>
                                            <td colspan=\"2\"><img src=\"".TC_IMG_URL."calendar_ico02.png\" alt=\"right\"/></td>
                                        </tr>
                                        <tr>
                                            <td>Su</td>
                                            <td>Mo</td>
                                            <td>Tu</td>
                                            <td>We</td>
                                            <td>Th</td>
                                            <td>Fr</td>
                                            <td>Sa</td>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <!--p class=\"select_date\">January, 6, 2015:</p>
                            <div class=\"update_change\">
                                <span class=\"added\">Added:</span>
                                <span>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</span>
                            </div>
                            <div class=\"update_change\">
                                <span class=\"fixed\">Fixed:</span>
                                <span>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</span>
                            </div-->
                        </div>
                        <div class=\"latest_update\" style=\"display: block;\">
                            <p>v. 0.3.26</p>
                            <div class=\"update_change\">
                                <span class=\"added\">Added:</span>
                                <span>
                                    <p>Default API options was cleared</p>
                                </span>
                            </div>
                            <div class=\"update_change\">
                                <span class=\"fixed\">Fixed:</span>
                                <span>
                                <p>Widget headers fixed</p>
                                <p>Text align changed in messages</p>
                                <p>Frontend Twitter account functionality repaired</p>
                                </span>
                            </div>
                        </div>
                    </div>
				</div>
                <div class=\"support-box\">
                    <span>Support</span>
                    <p style=\"padding: 20px;\">
                        For support, please go to Wordpress and submit a support request (link to follow once we finalize the plugin registration).<br>
                        If you are a subscriber to TwitterCart Pro or TwitterCart Vendor, please submit a ticket and/or login to your account at <a href=\"http://browserweb.org/\">Browserweb.org</a>.<br>
                        If you wish to upgrade or Purchase our Premium Plugin versions, visit <a href=\"http://browserwebinc.com/\">Browserwebinc.com</a> to learn  more.
                    </p>
                </div>
			</div>
		</div>
                <script>
                function calendar(id, year, month) {
        var Dlast = new Date(year, month + 1, 0).getDate(),
                D = new Date(year, month, Dlast),
                DNlast = new Date(D.getFullYear(), D.getMonth(), Dlast).getDay(),
                DNfirst = new Date(D.getFullYear(), D.getMonth(), 1).getDay(),
                calendar = '<tr>',
                month = ['January,', 'February,', 'March,', 'April,', 'May,', 'June,', 'July,', 'August,', 'September,', 'October,', 'November,', 'December,'];
        if (DNfirst != 6) {
            for (var i = 0; i < DNfirst; i++)
                calendar += '<td>';
        }
        else {
            for (var i = 0; i < 6; i++)
                calendar += '<td>';
        }
        for (var i = 1; i <= Dlast; i++) {
            if (i == new Date().getDate() && D.getFullYear() == new Date().getFullYear() && D.getMonth() == new Date().getMonth()) {
                calendar += '<td class=\"today\">' + i;
            }
            else {
                calendar += '<td>' + i;
            }
            if (new Date(D.getFullYear(), D.getMonth(), i).getDay() == 6 && i != Dlast) {
                calendar += '<tr>';
            }
        }
        for (var i = DNlast; i < 6; i++)
            calendar += '<td>&nbsp;';

        document.querySelector('#' + id + ' tbody').innerHTML = calendar;
        document.querySelector('#' + id + ' thead td:nth-child(2)').innerHTML = month[D.getMonth()] + ' ' + D.getFullYear();
        document.querySelector('#' + id + ' thead td:nth-child(2)').dataset.month = D.getMonth();
        document.querySelector('#' + id + ' thead td:nth-child(2)').dataset.year = D.getFullYear();

        if (document.querySelectorAll('#' + id + ' tbody tr').length < 6) {
            document.querySelector('#' + id + ' tbody').innerHTML += '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
        }
        if (document.querySelectorAll('#' + id + ' tbody tr').length < 6) {
            document.querySelector('#' + id + ' tbody').innerHTML += '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
        }
    }

    calendar('calendar', new Date().getFullYear(), new Date().getMonth());

    //click l/r month
    document.querySelector('#calendar thead tr:nth-child(1) td:nth-child(1)').onclick = function() {
        calendar('calendar', document.querySelector('#calendar thead td:nth-child(2)').dataset.year, parseFloat(document.querySelector('#calendar thead td:nth-child(2)').dataset.month) - 1);
    }
    document.querySelector('#calendar thead tr:nth-child(1) td:nth-child(3)').onclick = function() {
        calendar('calendar', document.querySelector('#calendar thead td:nth-child(2)').dataset.year, parseFloat(document.querySelector('#calendar thead td:nth-child(2)').dataset.month) + 1);
    }
    jQuery('.update_menu>span').click(function() {
        jQuery('.update_menu>span').removeClass('active');
        jQuery(this).addClass('active');
        if (jQuery('.update_menu>span:first').attr('class') == 'active') {
            jQuery('.update_history>div').css('display', 'none');
            jQuery('.update_history>div:last').css('display', 'block')
        }
        if (jQuery('.update_menu>span:last').attr('class') == 'active') {
            jQuery('.update_history>div').css('display', 'none');
            jQuery('.update_history>div:first').css('display', 'block')
        }
    });
                </script>
";
