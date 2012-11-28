<?php namespace Nerd; use \Application\Flash; ?>

<h2>Administrative Login</h2>

<?= Flash::info($flash->get('info')) ?>
<?= Flash::success($flash->get('success')) ?>
<?= Flash::warning($flash->get('warning')) ?>
<?= Flash::error($flash->get('error')) ?>

<form action="login" method="post" class="form-horizontal">
  <div class="control-group">
    <label class="control-label" for="login_email">Email</label>
    <div class="controls">
      <input type="text" name="login[email]" value="<?= $email ?>" id="login_email" placeholder="Email">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="login_password">Password</label>
    <div class="controls">
      <input type="password" name="login[password]" id="login_password" placeholder="Password">
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <label class="checkbox">
        <input type="checkbox" name="login[remember]"> Remember me
      </label>
      <button type="submit" class="btn">Sign in</button>
    </div>
  </div>
</form>
