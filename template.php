<?php
/**
 * @file
 */

/*
 * template_preprocess_page($variables)
 */
function starterkit_preprocess_html(&$variables) {
  // Add bootstrap js to page_bottom
  drupal_add_js('//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js', 'external', 'footer');
  drupal_add_js(path_to_theme() .'/js/libs/bootstrap/bootstrap.js', 'file', 'footer'); 
  drupal_add_js(path_to_theme() .'/js/libs/bootstrap/transition.js', 'file', 'footer');
  drupal_add_js(path_to_theme() .'/js/libs/bootstrap/collapse.js', 'file', 'footer');
}
/**
 * template_preprocess_taxonomy_term(&$variables)
 */
function starterkit_preprocess_taxonomy_term(&$variables) {

}
/**
 * template_preprocess_page(&$variables)
 */
function starterkit_preprocess_page(&$variables) {

}
/**
 * template_preprocess_block($variables)
 */
function starterkit_preprocess_block(&$variables) {
  $block = $variables['block'];
}
/**
 * template_preprocess_region(&$variables)
 */
function starterkit_preprocess_region(&$variables) {
  $blocks = 0;

  foreach ($variables['elements'] as $delta => $block) {
    if (substr($delta, 0, 1) != '#') {
      $blocks++;
    }
  }

  $variables['classes_array'][] = 'blocks-'. $blocks;
}
/**
 * template_form_alter(&$form, &$form_state, $form_id)
 * FIXME: Should be done using FORM_ID instead
 */
function starterkit_form_alter(&$form, &$form_state, $form_id) {
	if ($form_id == 'user_login_block') {
		// user_login_block is a well
		$form['#attributes'] = array(
			'class' => array('well'),
		);
	}
	
	if ($form_id == 'search_block_form') {
		$form['#attributes'] = array(
			'class' => array('form-search'),
		);
		
		// search_block_form
		$form['search_block_form']['#attributes'] = array(
			'class' => array('search-query', 'input-medium'),
			'placeholder' => array_key_exists('#title', $form['search_block_form']) ? $form['search_block_form']['#title'] : '',
		);
		unset($form['search_block_form']['#title']);
	}
	// Form: user_register_form
	if ($form_id == 'user_register_form') {
		$form['#attributes'] = array(
			'class' => array('form-horizontal'),
		);
	}
	// Contact form
	if ($form_id == 'contact_site_form') {
		$form['actions']['submit']['#prefix'] = null;
		$form['actions']['submit']['#prefix'] = '<div>';
		$form['actions']['submit']['#suffix'] = null;
		$form['actions']['submit']['#suffix'] = '</div>';
	}
	// user_pass
	if ($form_id == 'user_pass') {
		$form['actions']['submit']['#prefix'] = null;
		$form['actions']['submit']['#prefix'] = '<div>';
		$form['actions']['submit']['#suffix'] = null;
		$form['actions']['submit']['#suffix'] = '</div>';
	}
}
/**
 * theme_image_formatter($variables)
 */
function starterkit_image_formatter($variables) {
	$item = $variables['item'];
  $image = array(
    'path' => $item['uri'], 
    'alt' => $item['alt'],
  );

  if (isset($item['attributes'])) {
    $image['attributes'] = $item['attributes'];
  }

  // Do not output an empty 'title' attribute.
  if (drupal_strlen($item['title']) > 0) {
    $image['title'] = $item['title'];
  }

	$output = theme('image', $image);

  if (!empty($variables['path']['path'])) {
    $path = $variables['path']['path'];
    $options = $variables['path']['options'];
    // When displaying an image inside a link, the html option must be TRUE.
    $options['html'] = TRUE;
    $output = l($output, $path, $options);
  }

  return $output;
}
/**
 * theme_status_messages($variables)
 */
function starterkit_status_messages($variables) {
	$display = $variables['display'];
  $output = '';

  $status_heading = array(
    'status' => t('Status message'), 
    'error' => t('Error message'), 
    'warning' => t('Warning message'),
  );

	// Bootstrap themes http://twitter.github.com/bootstrap/components.html#alerts
	$twitter_bootstrap_themes = array(
		'status' => 'alert-success',
		'error' => 'alert-error',
		'warning' => 'alert-block'
	);

  foreach (drupal_get_messages($display) as $type => $messages) {
		$alert = $twitter_bootstrap_themes[$type];
		
		if (empty($alert)) {
			$alert = $twitter_bootstrap_themes['status'];
		}
		
    $output .= "<div class=\"alert $type $alert\">\n";
    if (!empty($status_heading[$type])) {
      $output .= '<h4 class="alert-heading element-invisible">' . $status_heading[$type] . "</h4>\n";
    }
    if (count($messages) > 1) {
      $output .= " <ul>\n";
      foreach ($messages as $message) {
        $output .= '  <li>' . $message . "</li>\n";
      }
      $output .= " </ul>\n";
    }
    else {
      $output .= $messages[0];
    }
    $output .= "</div>\n";
  }
  return $output;
}
/**
 * TODO: Document this
 */
function starterkit_css_alter(&$css) {
  foreach ($css as $mod => $val) {
    // Remove modules
    if (stripos($mod, 'modules') !== false) {      
			unset($css[$mod]);
    }
  }
}
/**
 * TODO: Document this
 */
function starterkit_field__body($vars) {
  $output = '';

  foreach ($vars['items'] as $delta => $item) {
    $output .= render($item);
  }

  return $output;
}
/**
 * Tags can be themed depending on node type
 * starterkit_field__field_tags__article() will only affect tags on articles
 */
function starterkit_field__field_tags($vars) {
	$output = array();
	
	foreach ($vars['items'] as $delta => $item) {
		$output[] = render($item);
	}
	
	return implode(' ', $output);
}
/**
 * Drupal 7 only allows images to be uploaded to articles
 */
function starterkit_field__field_image__article($variables) {
	$output = '';
	
	foreach ($variables['items'] as $delta => $item) {
		$output .= render($item);
	}
	
	return $output;
}
/**
 * theme_button($variables)
 */
function starterkit_button($variables) {
	$element = $variables['element'];
  $element['#attributes']['type'] = 'submit';
  element_set_attributes($element, array('id', 'name', 'value'));

  $element['#attributes']['class'] = array('form-' . $element['#button_type'], 'btn');
  if (!empty($element['#attributes']['disabled'])) {
    $element['#attributes']['class'][] = 'form-button-disabled';
  }

  return '<input' . drupal_attributes($element['#attributes']) . ' />';
}
/**
 * theme_container($variables)
 */
function starterkit_container($variables) {
  $element = $variables['element'];

  // Special handling for form elements.
  if (isset($element['#array_parents'])) {
    // Assign an html ID.
    if (!isset($element['#attributes']['id'])) {
      $element['#attributes']['id'] = $element['#id'];
    }
    // Add the 'form-wrapper' class.
    $element['#attributes']['class'][] = 'form-wrapper';
  }

  return $element['#children'];
}
/**
 * theme_form_element($variables)
 */
function starterkit_form_element($variables) {
  $element = &$variables['element'];
  // This is also used in the installer, pre-database setup.
  $t = get_t();

  // This function is invoked as theme wrapper, but the rendered form element
  // may not necessarily have been processed by form_builder().
  $element += array(
    '#title_display' => 'before',
  );

  // Add element #id for #type 'item'.
  if (isset($element['#markup']) && !empty($element['#id'])) {
    $attributes['id'] = $element['#id'];
  }
  // Add element's #type and #name as class to aid with JS/CSS selectors.
  $attributes['class'] = array('form-item');
  if (!empty($element['#type'])) {
    $attributes['class'][] = 'form-type-' . strtr($element['#type'], '_', '-');
  }
  if (!empty($element['#name'])) {
    $attributes['class'][] = 'form-item-' . strtr($element['#name'], array(' ' => '-', '_' => '-', '[' => '-', ']' => ''));
  }
  // Add a class for disabled elements to facilitate cross-browser styling.
  if (!empty($element['#attributes']['disabled'])) {
    $attributes['class'][] = 'form-disabled';
  }
  $output = '';

  // If #title is not set, we don't display any label or required marker.
  if (!isset($element['#title'])) {
    $element['#title_display'] = 'none';
  }
  $prefix = isset($element['#field_prefix']) ? '<span class="field-prefix">' . $element['#field_prefix'] . '</span> ' : '';
  $suffix = isset($element['#field_suffix']) ? ' <span class="field-suffix">' . $element['#field_suffix'] . '</span>' : '';

  switch ($element['#title_display']) {
    case 'before':
    case 'invisible':
      $output .= ' ' . theme('form_element_label', $variables);
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;

    case 'after':
      $output .= ' ' . $prefix . $element['#children'] . $suffix;
      $output .= ' ' . theme('form_element_label', $variables) . "\n";
      break;

    case 'none':
    case 'attribute':
      // Output no label and no required marker, only the children.
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;
  }

  if (!empty($element['#description'])) {
    $output .= '<div class="description">' . $element['#description'] . "</div>\n";
  }

  $output .= '';

  return $output;
}
/**
 * TODO: Should be wrapper according to http://twitter.github.com/bootstrap/base-css.html#forms
 * theme_radios($variables)
 */
function starterkit_radios($variables) {
  $element = $variables['element'];
	$title = $element['#title'];
	
	unset($variables['element']['#title']);
	
  return '<div class="control-group"><label class="control-label">'. $title .'</label><div class="controls">'. $element['#children'] .'</div></div>';
}
/** 
 * theme_radio($variables)
 */
function starterkit_radio($variables) {
  $element = $variables['element'];
  $element['#attributes']['type'] = 'radio';
  element_set_attributes($element, array('id', 'name','#return_value' => 'value'));

  if (isset($element['#return_value']) && $element['#value'] !== FALSE && $element['#value'] == $element['#return_value']) {
    $element['#attributes']['checked'] = 'checked';
  }
  _form_set_class($element, array('form-radio'));

  return '<label class="radio"><input' . drupal_attributes($element['#attributes']) . ' />'. $element['#title'] .'</label>';
}
/**
 * theme_form_element_label($variables)
 */
function starterkit_form_element_label($variables) {
	$element = $variables['element'];
  // This is also used in the installer, pre-database setup.
  $t = get_t();

  // If title and required marker are both empty, output no label.
  if ((!isset($element['#title']) || $element['#title'] === '') && empty($element['#required'])) {
    return '';
  }

  // If the element is required, a required marker is appended to the label.
  $required = !empty($element['#required']) ? theme('form_required_marker', array('element' => $element)) : '';

  $title = filter_xss_admin($element['#title']);

  $attributes = array();
  // Style the label as class option to display inline with the element.
  if ($element['#title_display'] == 'after') {
    $attributes['class'] = 'option';
  }
  // Show label only to screen readers to avoid disruption in visual flows.
  elseif ($element['#title_display'] == 'invisible') {
    $attributes['class'] = 'element-invisible';
  }

  if (!empty($element['#id'])) {
    $attributes['for'] = $element['#id'];
  }

	// If type is radio
	if ($element['#type'] == 'radio') {
		return '';
	}

  // The leading whitespace helps visually separate fields from inline labels.
  return ' <label' . drupal_attributes($attributes) . '>' . $t('!title !required', array('!title' => $title, '!required' => $required)) . "</label>\n";
}
/**
 * theme_textarea($variables)
 * FIXME: Notice: Undefined variable: output in starterkit_textarea()
 */
function starterkit_textarea($variables) {
	$element = $variables['element'];
  element_set_attributes($element, array('id', 'name', 'cols', 'rows'));
  _form_set_class($element, array('form-textarea'));

  $wrapper_attributes = array(
    'class' => array('form-textarea-wrapper'),
  );

  // Add resizable behavior.
  if (!empty($element['#resizable'])) {
    drupal_add_library('system', 'drupal.textarea');
    $wrapper_attributes['class'][] = 'resizable';
  }

	// Add bootstrap CSS class
	$element['#attributes']['class'][] = 'input-xlarge';

  $output = '<textarea' . drupal_attributes($element['#attributes']) . '>' . check_plain($element['#value']) . '</textarea>';

  return $output;
}
/**
 * theme_menu_local_tasks(&$variables)
 */
function starterkit_menu_local_tasks(&$variables) {
	$output = '';

		if (!empty($variables['primary'])) {
			if (!array_key_exists('#prefix', $variables['primary'])) {
				$variables['primary']['#prefix'] = null;
				$variables['primary']['#prefix'] = '<ul class="nav nav-tabs">';
			}

			$variables['primary']['#suffix'] = '</ul>';

			$output .= drupal_render($variables['primary']);
		}
		if (!empty($variables['secondary'])) {
			$variables['secondary']['#prefix'] .= '<ul class="nav nav-tabs">';
			$variables['secondary']['#suffix'] = '</ul>';

			$output .= drupal_render($variables['secondary']);
		}

		return $output;
}
/**
 * theme_menu_local_task($variables)
 */
function starterkit_menu_local_task($variables) {
  $link = $variables['element']['#link'];
  $link_text = $link['title'];

	if (!empty($variables['element']['#active'])) {
		// Add text to indicate active tab for non-visual users.

		// If the link does not contain HTML already, check_plain() it now.
		// After we set 'html'=TRUE the link will not be sanitized by l().
		if (empty($link['localized_options']['html'])) {
			$link['title'] = check_plain($link['title']);
		}
		$link['localized_options']['html'] = TRUE;
		$link_text = t('!local-task-title', array('!local-task-title' => $link['title']));
	}

	return '<li' . (!empty($variables['element']['#active']) ? ' class="active"' : '') . '>' . l($link_text, $link['href'], $link['localized_options']) . "</li>\n";
}
