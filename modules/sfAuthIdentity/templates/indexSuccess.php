<?php use_helper('I18n') ?>
<h1>Identities</h1>
<h2>Complete this wizard and add a identity</h2>
<iframe src="https://gaming.rpxnow.com/openid/embed?token_url=http://localhost/frontend_dev.php/sfAuthIdentity/new" scrolling="no" frameBorder="no" style="width:400px;height:240px;">
</iframe>

<h2>Your assigned identities</h2>
<table>
    <tr>
        <th>Type</th>
        <th>#</th>
    </tr>
    <?php foreach ($identities as $identity): ?>
    <tr>
        <td><?php echo $identity->provider ?></td>
        <td><?php echo link_to(__('Delete'), 'sfAuthIdentity/delete?id=' . $identity->getId()) ?></td>
    </tr>
    <?php endforeach; ?>
</table>