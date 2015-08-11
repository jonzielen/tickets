<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body style="background-color:#eee;">
        <table cellpadding="0" cellspacing="0" width="100%" style="background-color:#eee;">
            <tr>
                <td>
                    <img src="{headerImage}" alt="" style="display:block;width:100%;" />
                </td>
            </tr>

            <tr>
                <td>
                    <table style="width:75%;margin:0 auto;">
                        <tr>
                            <td>
                                <span style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000;">Hi!</span><br />
                                <span style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000;">{message}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td style="text-align:center;">
                    &copy; <?php echo date('Y'); ?>, Jon Z.
                </td>
            </tr>
        </table>
    </body>
</html>
