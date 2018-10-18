<form name="OptionForm" method="post">
    <table width='100%' cellspacing='0' cellpadding='0'>
        <tr>
            <td align='center'>
                <table width='50%' cellspacing='0' cellpadding='0'>
                    <tr height="60">
                        <td class="form-title">
                            [[.import_data.]]
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <fieldset style="padding: 20px;">
                                <legend style="font-size: 13px;"><b>[[.option.]]</b></legend>
                                <input  class="function" type="button" id="wh_import" name="wh_import" value="[[.warehouse_import.]]"/>
                            </fieldset>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <input type="hidden" id="cmd" name="cmd" />
</form>

<script>
    jQuery(document).ready(function(){
        jQuery(".function").click(function(e)
        {
            var url = '<?php echo Url::build_current();?>'+'&cmd='+this.id+'&type=IMPORT';
            window.location.href = url;
        });
    });
</script>