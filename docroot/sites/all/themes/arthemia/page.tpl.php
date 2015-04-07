<?php
// $Id: page.tpl.php,v 1.7 2009/07/03 15:09:30 nbz Exp $
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
  <head>
    <title><?php print $head_title ?></title>
    <?php print $head ?>
    <?php print $styles ?>
    <?php print $scripts ?>
  </head>
  <body>
    <div id="head" class="clearfloat">

      <div class="clearfloat">
        <div id="logo">
          <?php if ($logo || $site_name) {
            print '<a href="'. check_url($base_path) .'" title="'. $site_name .'">';
            if ($logo) {
              print '<img src="'. check_url($logo) .'" alt="'. $site_name .'" />';
          } else {
            print '<span id="sitename">'. $site_name .'</span>';
          }
            print '</a>';
          }
        ?>
        <?php if ($site_slogan): print '<div id="tagline">'. $site_slogan .'</div>'; endif; ?>
        </div>

        <?php if ($banner): ?>
          <div id="banner-region">
            <?php print $banner; ?>
          </div>
        <?php endif; ?>
      </div>

      <div id="navbar" class="clearfloat">
          <?php if (isset($primary_links)) {
            print arthemia_primary($primary_links);
          } ?>
        <?php if ($search_box): ?>
          <div id="searchform"><?php print $search_box; ?></div>
        <?php endif; ?>
      </div>
    </div>

    <div id="page" class="clearfloat">

      <?php if ($headline): ?>
      <div id="top" class="clearfloat">
        <div id="headline" class="<?php print empty($featured)? 'no' : 'with'?>-featured">
          <?php print $headline; ?>
        </div>
        <?php if ($featured): ?>
          <div id="featured">
            <?php print $featured; ?>
          </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>
      
      <?php if ($middle):?>
        <div id="middle" class="clearfloat">
          <?php print $middle; ?>
        </div>
      <?php endif; ?>

      <div id="content" class="main-content <?php print empty($sidebar)? 'no' : 'with'?>-sidebar">
        <?php if ($content_top): ?>
          <div id="content-top">
            <?php print $content_top; ?>
          </div>
        <?php endif; ?>

        <?php if ($breadcrumb) { print $breadcrumb; } ?>
        <?php if ($mission) { print "<div id='mission'>". $mission ."</div>"; } ?>
        <?php if ($tabs) { print "<div id='tabs-wrapper' class='clear-block'>"; } ?>
        <?php if ($title) { print "<h1". ($tabs ? " class='with-tabs'" : "") .">". $title ."</h1>"; } ?>
        <?php if ($tabs) { print $tabs ."</div>"; } ?>
        <?php if (isset($tabs2)) { print $tabs2; } ?>
        <?php if ($help) { print $help; } ?>
        <?php if ($show_messages && $messages) { print $messages; } ?>
        <?php print $content; ?>

        <?php if ($content_bottom): ?>
          <div id="content-bottom">
            <?php print $content_bottom; ?>
          </div>
        <?php endif; ?>

      </div>

      <?php if ($sidebar):?>
        <div id="sidebar">
          <?php print $sidebar; ?>
        </div>
      <?php endif; ?>

      <?php if ($bottom):?>
        <div id="bottom">
          <?php print $bottom; ?>
        </div>
      <?php endif; ?>
    </div>

    <div id="footer-region" class="clearfloat">
      <div id="footer-left" class="clearfloat">
        <?php print $footer_left; ?>
      </div> 		

      <div id="footer-middle" class="clearfloat">
        <?php print $footer_middle; ?>
      </div>

      <div id="footer-right" class="clearfloat">
        <?php print $footer_right; ?>
      </div>
    </div>

    <div id="footer-message">
      <?php print $footer_message; ?>
    </div>
    <?php print $closure; ?>
  </body>
</html>