<div id="wpmsgc-options" class="wpmsgc-option wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2><?php echo WPMSGC_NAME ?> Options</h2>

    <form method="post" action="options.php">
        <?php settings_fields('wpmsgc_options');
        $wpmsgcopts = get_option('wpmsgc'); ?>

        <table class="form-table">

            <tr valign="top">
                <td class="label" scope="row">Exclude/Include</td>
                <td>
                    <input name="wpmsgc[include]"
                           value="0" <?php echo ($wpmsgcopts['include']) ? '' : 'checked="checked"'; ?>
                           type="radio"/> Exclude Following&nbsp;&nbsp;&nbsp;
                    <input name="wpmsgc[include]"
                           value="1" <?php echo ($wpmsgcopts['include']) ? 'checked="checked"' : ''; ?>
                           type="radio"/> Include Following
                    <br/>
                    <input type="text" name="wpmsgc[exclude]" value="<?php echo $wpmsgcopts['exclude']?>"/>
                    <br/>
                    <small>Comma separated list of blog ids you want to exclude/include.
                        Select Exclude Following and leave textbox empty if you want to show it on all blogs.
                    </small>
                </td>
            </tr>

            <tr valign="top">
                <td scope="row">Header Content</td>
                <td>
                    <p align="right">
                        <a class="button toggleHVisual">Visual</a>
                        <a class="button toggleHHTML">HTML</a>
                    </p>
                    <textarea id="wpmsgctextheader" class="wpmsgctextarea"
                              name="wpmsgc[header]"><?php echo $wpmsgcopts['header']?></textarea>
                    <br/>
                    <small>Enter content that will be displayed in header.</small>
                </td>
            </tr>

            <tr valign="top">
                <td scope="row">Footer Content</td>
                <td>
                    <p align="right">
                        <a class="button toggleFVisual">Visual</a>
                        <a class="button toggleFHTML">HTML</a>
                    </p>
                    <textarea id="wpmsgctextfooter" class="wpmsgctextarea"
                              name="wpmsgc[footer]"><?php echo $wpmsgcopts['footer']?></textarea>
                    <br/>
                    <small>Enter content that will be displayed in footer.</small>
                </td>
            </tr>

        </table>

        <p class="submit">
            <input type="submit" class="button-primary" name="wpmsgc[submit]" value="<?php _e('Save Changes') ?>"/>
        </p>
    </form>
    <p>If you find this plugin useful, please donate to continue development of this and several other plugins</p>

    <form style="background:none;" method="get" action="https://www.paypal.com/cgi-bin/webscr">
        <div class="paypal-donations"><input type="hidden" value="_donations" name="cmd">
            <input type="hidden" value="dobaria.neerav@gmail.com" name="business">
            <input type="hidden" value="Support WPMS Global Content Development" name="item_name">
            <input type="hidden" value="5" name="amount">
            <input type="hidden" value="USD" name="currency_code">
            <input type="image" alt="PayPal - The safer, easier way to pay online." name="submit"
                   src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif">
        </div>
    </form>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        var idH = 'wpmsgctextheader';

        $('a.toggleHVisual').click(
                function() {
                    tinyMCE.execCommand('mceAddControl', false, idH);
                }
                );

        $('a.toggleHHTML').click(
                function() {
                    tinyMCE.execCommand('mceRemoveControl', false, idH);
                }
                );

        var idF = 'wpmsgctextfooter';

        $('a.toggleFVisual').click(
                function() {
                    tinyMCE.execCommand('mceAddControl', false, idF);
                }
                );

        $('a.toggleFHTML').click(
                function() {
                    tinyMCE.execCommand('mceRemoveControl', false, idF);
                }
                );

    });
</script>