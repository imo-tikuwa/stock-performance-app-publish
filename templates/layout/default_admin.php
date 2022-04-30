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
<body class="h-100">
  <div id="app" class="h-100">
    <div class="container-fluid">
      <div class="row">
        <div id="brand" class="d-flex align-items-center col-auto ps-3 pe-3">
          <a class="navbar-brand" href="<?= $this->Url->build(['controller' => 'Top', 'action' => 'index', 'prefix' => 'Admin'])?>"><?= SITE_NAME ?></a>
        </div>
        <nav class="col navbar navbar-expand-lg navbar-light bg-light ps-0 pe-0">
          <div id="header-link" class="d-none d-lg-inline-block ms-3">
            <ul class="navbar-nav">
              <?= $this->element('header_link'); ?>
            </ul>
          </div>
          <div id="navbar-togglers" class="col-auto pe-md-4">
            <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#header-toggle-link" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navbar">
              <span class="fas fa-link"></span>
            </button>
            <button class="navbar-toggler" type="button" data-bs-toggle="modal" data-bs-target="#modal-sidebar">
              <span class="fas fa-bars"></span>
            </button>
          </div>
        </nav>
      </div>
      <div class="row d-block d-lg-none">
        <div id="header-toggle-link" class="collapse navbar-collapse">
          <ul class="navbar-nav">
            <?= $this->element('header_link', ['params' => ['nav-item-class' => 'd-block d-lg-none']]); ?>
          </ul>
        </div>
      </div>
    </div>

    <div class="container-fluid h-100">
      <div class="row h-100">
        <nav id="sidebar" class="col-auto collapse bg-dark">
          <div class="sidebar-inner">
            <div class="py-md-3">
              <ul class="nav flex-column" role="menu">
                <?= $this->element('left_side_menu') ?>
              </ul>
            </div>
          </div>
        </nav>
        <main class="col px-md-4 py-4">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark"><?= $this->fetch('title') ?></h1>
            </div>
          </div>

          <?= $this->Flash->render() ?>

          <div class="row">
            <?= $this->fetch('content') ?>
          </div>

          <footer class="pt-4 text-center">
            <?= $this->element('footer_link'); ?>
          </footer>
        </main>
      </div>
    </div>
    <div class="modal fade modal-sidebar" id="modal-sidebar" tabindex="-1" role="dialog" aria-labelledby="modal-sidebar-label" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body bg-dark">
            <div class="pt-5 pb-3">
              <ul class="nav flex-column" role="menu">
                <?= $this->element('left_side_menu') ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?= $this->Html->script('vendor/bundle') ?>
  <?= $this->fetch('script') ?>
</body>
</html>
