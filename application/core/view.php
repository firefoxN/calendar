<?php

namespace app\core;

class View
{
	/**
	 * @param string $content_view name of template file
	 * @param string $template_view name of layout file
	 * @param null $data
	 */
	public function generate($content_view, $template_view, $data = null)
	{

		if (is_array($data)) {
			extract($data);
		}

		include 'application/views/' . $template_view;
	}
}