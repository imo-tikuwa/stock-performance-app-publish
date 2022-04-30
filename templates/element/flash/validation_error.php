<?php
/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
$alert_class = 'alert alert-primary alert-dismissible fade show';
if (isset($params['alert-class'])) {
  $alert_class .= " {$params['alert-class']}";
}
?>
<div class='<?= $alert_class ?>' role="alert">
  <?= $message ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
