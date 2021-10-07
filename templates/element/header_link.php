<?php
/**
 * @var \App\View\AppView $this
 */
$header_links = _code('HeaderConfig');
if (!empty($header_links) && count($header_links) > 0) {
	$html = "";
	$dropdown_html = "<a class=\"nav-link dropdown-toggle d-md-none\" href=\"#\" role=\"button\" id=\"header-dropdown-link\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fas fa-link\"></i></a>";
	$dropdown_html .= "<div class=\"dropdown-menu\" aria-labelledby=\"header-dropdown-link\">";
	foreach ($header_links as $header_link) {

		if (empty($header_link['title']) || empty($header_link['link'])) {
			continue;
		}

		$html .= "<li class=\"nav-item d-none d-md-inline-block\">";
		$html .= $this->Html->link($header_link['title'], $header_link['link'], ['class' => 'nav-link', 'target' => '_blank']);
		$html .= "</li>";
		$dropdown_html .= $this->Html->link($header_link['title'], $header_link['link'], ['class' => 'dropdown-item']);
	}
	$dropdown_html .= "</div>";
	echo $html . $dropdown_html;
}