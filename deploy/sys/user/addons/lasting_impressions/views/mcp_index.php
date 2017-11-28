<?php if ($settings):
    print form_open($partial_url);
    ?>
<p><?php echo $instructions ?></p>
    <table class="mainTable">
        <thead>
            <tr>
                <th>
    <?php print lang('table_header_li_enabled') ?>
                </th>
                <th>
    <?php print lang('table_header_limit') ?>
                </th>
                <th>
    <?php print lang('table_header_cookie_expiration') ?>
                </th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>
                    <div>
                        <input type="radio" name="li-enabled" id="li-disabled" value="0" <?php if ($settings['enabled'] == '0') echo 'checked' ?>>
                        <label for="li-disabled"><?php print lang('disable') ?></label>
                        <input type="radio" name="li-enabled" id="li-enabled" value="1" <?php if ($settings['enabled'] == '1') echo 'checked' ?>>
                        <label for="li-enabled"><?php print lang('enable') ?></label>
                    </div>
                </td>
                <td>
                    <input type="text" maxlength="6" name="li-limit" id="li-limit" value="<?php echo $settings['limit'] ?>" />
                </td>
                <td>
                    <input type="text" maxlength="6" name="li-expires" id="li-expires" value="<?php echo $settings['expires'] ?>" />
                </td>
            </tr>
        </tbody>

    </table>

    <p class="submit-container"><input class="submit" type="submit" value="Submit" name="submit" id="li_submit"></p>

    <?php print form_close();

else:
    ?>

    <p><?php print lang('no_settings') ?></p>

<?php endif; ?>

<?php include PATH_THIRD . 'lasting_impressions' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'mcp_footer.php'; ?>
