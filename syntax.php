<?php
/**
 * w3pw plugin:  display passwords from w3pw
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Elan Ruusamäe <glen@delfi.ee>
 */
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

/**
$conf['plugin']['w3pw']['url'] = '/w3pw/';
 */

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_w3pw extends DokuWiki_Syntax_Plugin {

    /**
     * return some info
     */
    function getInfo() {
      return array(
        'author' => 'Elan Ruusamäe',
        'email'  => 'glen@delfi.ee',
        'date'   => '2010-09-10',
        'name'   => 'W3PW Plugin',
        'desc'   => 'Plugin to display passwords from w3pw',
        'url'    => 'https://cvs.delfi.ee/dokuwiki/plugin/w3pw/',
      );
    }

    /**
     * What kind of syntax are we?
     */
    function getType() {
        return 'substition';
    }

    /**
     * Where to sort in?
     */
    function getSort() {
        return 306;
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('\{\{w3pw>.+?\}\}', $mode, 'plugin_w3pw');
    }


    /**
     * Handle the match
     */
    function handle($match, $state, $pos, &$handler) {
        // extract out of container
        // php -r 'echo strlen("{{w3pw>");' = 7
		$match = substr($match, 7, -2);

        // extract title
        list($match, $title) = explode('|', $match, 2);

        $data = array('object' => trim($match), 'title' => trim($title));
        return $data;
    }

    /**
     * Create output
     */
    function render($format, &$renderer, $data) {
        global $ID;
        if ($format != 'xhtml') {
            return false;
        }

        global $conf;
        // get plugin config
        $c = empty($conf['plugin']['w3pw']) ? array() : $conf['plugin']['w3pw'];

        $link = $c['url'] ? $c['url'] : '/w3pw/view.php';
        $link .= '?id='.$data['object'];

        $title = !empty($data['title']) ? $data['title'] : 'click to see';
        $onclick = "var w=window.open(this.href, 'w3pw', 'width=560,height=400,left=0,top=0,scrollbars=yes,status=yes');w.focus();return false;";

        $renderer->doc .= '<a href="'.$link.'" onClick="'.$onclick.'">'.hsc($title).'</a>';

        return true;
    }
}

//Setup VIM: ex: et ts=4 enc=utf-8 :
