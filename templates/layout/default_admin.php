<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>
<!DOCTYPE html>
<html>
<head>
  <?= $this->Html->charset() ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $this->fetch('title') ?></title>
  <?= $this->Html->meta('icon') ?>
  <?= $this->Html->meta('robots', ['content' => 'noindex']) ?>
  <?= $this->Html->css('vendor/bundle') ?>
  <?= $this->fetch('meta') ?>
  <?= $this->fetch('css') ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item">
        <a href="<?= $this->Url->build(['controller' => 'Top', 'action' => 'index', 'prefix' => 'Admin'])?>" class="nav-link"><i class="fas fa-home"></i></a>
      </li>
      <?= $this->element('header_link'); ?>
    </ul>

  </nav>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= $this->Url->build(['controller' => 'Top', 'action' => 'index', 'prefix' => 'Admin']) ?>" class="brand-link">
      <span class="brand-text-disp-collapse"><?= SITE_NAME_SHORT ?></span>
      <span class="brand-text font-weight-light brand-text-disp-open"><?= SITE_NAME ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <?= $this->element('left_side_menu') ?>
        </ul>
      </nav>
    </div>
  </aside>

  <!--Main layout-->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">

        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><?= $this->fetch('title') ?></h1>
          </div>
        </div>

        <?= $this->Flash->render() ?>

        <div class="row">
          <?= $this->fetch('content') ?>
        </div>
      </div>
    </div>
  </div>

  <!--Footer-->
  <footer class="main-footer">
    <div class="text-center">
      <?= $this->element('footer_link'); ?>
    </div>
  </footer>

</div>
<?= $this->Html->script('vendor/bundle') ?>
<?= $this->fetch('script') ?>
</body>
</html>
