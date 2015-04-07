<?php
// include FSHL syntaxhighlighter library
require_once 'fshl/fshl.php';
require_once 'fshl/fshl-config.php';

if (!class_exists('fshlParser')) {
  die('DOWNLOAD <a href="http://hvge.sk/scripts/fshl/">FSHL</a> AND UNPACK TO fshl FOLDER FIRST!');
}

// user callback object for processing Texy events (PHP4/PHP5)
// class must be named as syntaxhighlightername + 'BlockHandler'
// ('fshl' + 'BlockHandler' = 'fshlBlockHandler' in this case)

/**
 * User handler for code block
 *
 * @param TexyHandlerInvocation  handler invocation
 * @param string  block type
 * @param string  text to highlight
 * @param string  language
 * @param TexyModifier modifier
 * @return TexyHtml
 */
function fshlBlockHandler($invocation, $blocktype, $content, $lang, $modifier) {
  if ($blocktype !== 'block/code') {
    return $invocation->proceed();
  }

  $lang = strtoupper($lang);
  if ($lang == 'JAVASCRIPT') $lang = 'JS';

  $parser = new fshlParser('HTML_UTF8', P_TAB_INDENT);
  if (!$parser->isLanguage($lang)) {
    return $invocation->proceed();
  }

  $texy = $invocation->getTexy();
  $content = Texy::outdent($content);
  $content = $parser->highlightString($lang, $content);
  $content = $texy->protect($content, TEXY_CONTENT_BLOCK); // or Texy::CONTENT_BLOCK in PHP 5

  $elPre = TexyHtml::el('pre');
  if ($modifier) $modifier->decorate($texy, $elPre);
  $elPre->attrs['class'] = strtolower($lang);

  $elCode = $elPre->create('code', $content);

  return $elPre;
}
