<?php
    echo $form->renderFormTag(url_for('sfAuthUser/reset_password'));
    echo $form->renderHiddenFields();
?>
<table>
    <?php echo $form ?>
</table>
<input type="submit" />
</form>