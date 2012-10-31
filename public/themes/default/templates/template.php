<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <title><?= $page->title ?> &mdash; Powered by NerdCMS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Application-wide stylesheets -->
  <?= $application->css ?>

  <!-- Theme stylesheets -->
  <?= $theme->css ?> 
</head>
<body>
  <div id="main" class="container">

    <?= $main ?: 'No content has been defined' ?>

    <footer id="footer">
      <p>Page rendered using Nerd v<?= Nerd\Version::FULL ?>, an open-source software package released under the MIT License.</p>
    </footer>
  </div>
</body>

<!-- Application-wide javascript -->
<?= $application->js ?>

<!-- Theme javascript -->
<?= $theme->js ?> 

</html>
