<!-- page.tpl.php //-->
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <button data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar" type="button">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <?php if ($site_name): ?>
				<a class="brand" href="<?php print $front_page ?>"><?php print $site_name ?></a>
			<?php endif ?>
      <div class="nav-collapse collapse">
        <?php print theme('links__system_main_menu', array('links' => $main_menu, 'attributes' => array('id' => 'main-menu', 'class' => array('nav')))); ?>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>

<div class="container-fluid">
  <?php if ($messages): ?>
    <section role="alert">
      <?php print $messages ?>
    </section>
  <?php endif ?>
  
  <section role="main">
    <?php if ($page['sidebar']): ?>
      <div class="row-fluid">
        <div class="span8">
          <?php if ($title): ?>
            <div class="page-header">
              <h1><?php print $title ?></h1>
            </div>
          <?php endif ?>
          
          <?php if ($tabs): ?>
            <div class="row-fluid">
              <div class="span8">
                <?php print render($tabs) ?>
              </div>
            </div>
          <?php endif ?>

          <?php print render($page['content']) ?>
        </div>

        <div class="span4">
          <aside>
          <?php print render($page['sidebar']) ?>
          </aside>
        </div>
      </div>
    <?php else: ?>
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

          <?php print render($page['content']) ?>
        </div>
      </div>
    <?php endif ?>
  </section>

</div> <!-- /container -->

<div class="container-fluid">
  <footer>
    <?php if ($page['footer']): ?>
      <?php print render($page['footer']) ?>
    <?php endif ?>
  </footer>
</div>
