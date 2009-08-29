<?php
    echo $form->renderFormTag(url_for('sfAuthUser/activate'));
    echo $form->renderHiddenFields();
?>
<table>
    <?php echo $form ?>
</table>
<input type="submit" />
</form>
