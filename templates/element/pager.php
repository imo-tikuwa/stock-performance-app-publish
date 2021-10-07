<?php
/**
 * @var \App\View\AppView $this
 */
?>
<nav aria-label="pager">
  <p class="text-center"><?= $this->Paginator->counter('全{{count}}件中 {{start}}件目～{{end}}件目を表示') ?></p>
  <?php // ページャーは2ページ目があるときのみ表示 ?>
  <?php if ($this->Paginator->hasPage(2, null)) {?>
    <ul class="pagination pg-blue justify-content-center">
      <?= $this->Paginator->first('<< ')?>
      <?php if (!empty($this->Paginator->hasPrev())) {?><?= $this->Paginator->prev('< ') ?><?php } ?>
      <?= $this->Paginator->numbers()?>
      <?php if (!empty($this->Paginator->hasNext())) { ?><?= $this->Paginator->next(' >') ?><?php } ?>
      <?= $this->Paginator->last(' >>')?>
    </ul>
  <?php } ?>
</nav>