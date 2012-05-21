<?php
/**
 * template_preprocess_block($variables)
 */
function starterkit_preprocess_block(&$variables) {
	// Add .span to blocks
  $variables['classes_array'][] = 'span';
  
  $block = $variables['block'];
	
	if (in_array($block->region, array('sidebar', 'herounit', 'footer'))) {
		$variables['content'] = '<section>'. $variables['content'] .'</section>';
	}
	
	if ($block->module == 'system' && $block->delta == 'main') {
		
	}
}
/**
 * template_preprocess_region(&$variables)
 */
function starterkit_preprocess_region(&$variables) {
  $variables['classes_array'][] = 'row-fluid';
}
/**
 * template_form_alter()
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
			'placeholder' => $form['search_block_form']['#title'],
		);
		unset($form['search_block_form']['#title']);
	}
	
	if ($form_id == 'user_register_form') {
		$form['#attributes'] = array(
			'class' => array('form-horizontal'),
		);
	}
}

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

function starterkit_css_alter(&$css) {
  foreach ($css as $mod => $val) {
    // Remove modules
    if (stripos($mod, 'modules') !== false) {
      unset($css[$mod]);
    }
  }
}

function starterkit_field__body($vars) {
  $output = '';

  foreach ($vars['items'] as $delta => $item) {
    $output .= render($item);
  }

  return $output;
}

function starterkit_field__field_tags__article($vars) {
	$output = '';
	
	foreach ($vars['items'] as $delta => $item) {
		$output .= render($item);
	}
	
	return $output;
}

function starterkit_field__field_image__article($variables) {
	$output = '';
	
	foreach ($variables['items'] as $delta => $item) {
		$output .= render($item);
	}
	
	return '<figure>'. $output .'</figure>';
}

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
// TODO: Should be wrapper according to http://twitter.github.com/bootstrap/base-css.html#forms
function starterkit_radios($variables) {
  $element = $variables['element'];

  return '<div class="control-group"><div class="controls">'. $element['#children'] .'</div></div>';
}

function starterkit_radio($variables) {
  $element = $variables['element'];
  $element['#attributes']['type'] = 'radio';
  element_set_attributes($element, array('id', 'name','#return_value' => 'value'));

  if (isset($element['#return_value']) && $element['#value'] !== FALSE && $element['#value'] == $element['#return_value']) {
    $element['#attributes']['checked'] = 'checked';
  }
  _form_set_class($element, array('form-radio'));

  return '<input' . drupal_attributes($element['#attributes']) . ' />';
}
# FIXME: Notice: Undefined variable: output in starterkit_textarea()
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
