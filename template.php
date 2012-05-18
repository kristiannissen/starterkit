<?php

function dhbsk_preprocess_block(&$variables) {
	$block = $variables['block'];
	
	if (in_array($block->region, array('sidebar', 'herounit', 'footer'))) {
		$variables['content'] = '<section>'. $variables['content'] .'</section>';
	}
	
	if ($block->module == 'system' && $block->delta == 'main') {

	}
}

function dhbsk_form_alter(&$form, &$form_state, $form_id) {
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
}

function dhbsk_status_messages($variables) {
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

function dhbsk_css_alter(&$css) {
  foreach ($css as $mod => $val) {
    // Remove modules
    if (stripos($mod, 'modules') !== false) {
      unset($css[$mod]);
    }
  }
}

function dhbsk_field__body($vars) {
  $output = '';

  foreach ($vars['items'] as $delta => $item) {
    $output .= render($item);
  }

  return $output;
}
# FIXME: This is not working
function dhbsk_field__tags($vars) {
	$output = '';
	
	foreach ($vars['items'] as $delta => $item) {
		$output .= render($item);
	}
	
	return '<strong>'. $output .'</strong>';
}

function dhbsk_button($variables) {
	$element = $variables['element'];
  $element['#attributes']['type'] = 'submit';
  element_set_attributes($element, array('id', 'name', 'value'));

  $element['#attributes']['class'] = array('form-' . $element['#button_type'], 'btn');
  if (!empty($element['#attributes']['disabled'])) {
    $element['#attributes']['class'][] = 'form-button-disabled';
  }

  return '<input' . drupal_attributes($element['#attributes']) . ' />';
}

function dhbsk_container($variables) {
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

function dhbsk_form_element($variables) {
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

function dhbsk_radios($variables) {
  $element = $variables['element'];
  
  return $element['#children'];
}