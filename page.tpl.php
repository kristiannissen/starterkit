<!-- page.tpl.php //-->
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container-fluid">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <?php if ($site_name): ?>
				<a class="brand" href="<?php print $front_page ?>"><?php print $site_name ?></a>
			<?php endif ?>
      <div class="nav-collapse">
        <?php print theme('links__system_main_menu', array('links' => $main_menu, 'attributes' => array('id' => 'main-menu', 'class' => array('links', 'inline', 'nav')))); ?>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>

<div class="container-fluid">
	<div class="wrapper">
		<?php if ($messages): ?>
			<?php print $messages ?>
		<?php endif ?>
		
		<div class="row-fluid">
			<div class="span12">
				<?php if ($title): ?>
					<div class="page-header">
						<h1><?php print $title ?></h1>
					</div>
				<?php endif ?>
				<?php if ($tabs): ?>
					<div class="row-fluid">
						<div class="span12">
							<?php print render($tabs) ?>
						</div>
					</div>
				<?php endif ?>
				<div class="row-fluid">
					<div class="span8">
					<?php if ($page['content']): ?>
						<?php print render($page['content']) ?>
					<?php endif ?>
					</div>
					<div class="span4">
						<aside>
							<?php if ($page['sidebar']): ?>
								<?php print render($page['sidebar']) ?>
							<?php endif ?>
						</aside>
					</div>
				</div>
			</div>
		</div>
	
		<hr>
	
		<footer>
	    <?php if ($page['footer']): ?>
	    	<?php print render($page['footer']) ?>
	    <?php endif ?>
	  </footer>
	</div>
</div> <!-- /container -->

