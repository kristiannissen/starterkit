<?php
/**
 * node--page.tpl.php
 */
?>
<!-- node.tpl.php //-->
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>"<?php print $attributes; ?>>
  <article>
    <header>
      <?php if (!$page): ?>
				<headergroup>
					<h4><?php print render($content['field_tags']) ?></h4>
        	<h1><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h1>
				</headergroup>
      <?php endif; ?>

     <?php if ($display_submitted): ?>
				<p>
         	<?php print $submitted ?> <time datetime="<?php print format_date($created, 'custom', 'Y-m-d') ?>"><?php print format_date($created, 'custom', 'Y-m-d') ?></time>
       	</p>
			<?php endif ?>
      
    
    </header>

    <?php print render($content) ?>
  
  </article>
</div>
