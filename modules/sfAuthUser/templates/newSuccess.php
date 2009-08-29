<?php
    echo $form->renderFormTag(url_for('@sf_auth_new'));
    echo $form->renderHiddenFields();
?>
<table>
    <?php echo $form ?>
</table>
<input type="submit" />
</form>
