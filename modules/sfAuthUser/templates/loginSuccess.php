<?php
    echo $form->renderFormTag(url_for('@sf_auth_login'));
    echo $form->renderHiddenFields();
?>
<table>
    <?php echo $form ?>
</table>
<input type="submit" />
</form>

<iframe src="https://gaming.rpxnow.com/openid/embed?token_url=http://localhost/frontend_dev.php/sfAuthUser/rpx"
  scrolling="no" frameBorder="no" style="width:400px;height:240px;">
</iframe>
