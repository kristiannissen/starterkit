<?php
/**
 * nodes are stored at $variables['elements']['nodes']
 */

$elements = $variables['elements']['nodes'];
$nodes = array();

foreach ($elements as $element => $value) {
	if (is_int($element)) {
		$nodes[] = $value;
	}
}

?>
<!-- block__system__main_front //-->

<?php foreach (array_slice($nodes, 0, 2) as $node): ?>
	<div class="span6">
		<?php print render($node) ?>
	</div>
<?php endforeach ?>

<div class="row-fluid">
	<?php foreach (array_slice($nodes, 2) as $node): ?>
		<div class="span12">
			<?php print render($node) ?>
		</div>
	<?php endforeach ?>
</div>