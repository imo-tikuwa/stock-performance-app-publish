<?php
/**
 * @var \App\View\AppView $this
 */
$header_links = _code('HeaderConfig');
if (!empty($header_links) && count($header_links) > 0) {
	$html = "";
	foreach ($header_links as $header_link) {

		if (empty($header_link['title']) || empty($header_link['link'])) {
			continue;
		}

		$html .= "<li class=\"nav-item\">";
		$html .= $this->Html->link($header_link['title'], $header_link['link'], ['class' => 'nav-link', 'target' => '_blank']);
		$html .= "</li>";
	}
	echo $html;
}
