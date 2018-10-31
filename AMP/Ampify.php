/**
 * Ampify class
 *
 * @license MIT
 * @author Firejune
 * @version 0.4.19
 */
class Ampify {
	// An var of list for source code syntax highlighting
	private static $highlights = array();
	// An var of list for extnend component loading
	private static $components = array();
	// Source code syntax highlight using GeSHi
	private static function codeHelper($matches) {
		require_once('lib/geshi/geshi.php');
		$lang = str_replace('language-', '', $matches[1]);
		$code = rtrim($matches[2]);
		if ($lang == 'json' || $lang == 'jsx') $lang = 'javascript';
		if ($lang == 'html' || $lang == 'markup') $lang = 'html5';
		if ($lang == 'command') $lang = 'bash';
		if (!in_array($lang, self::$highlights)) {
			self::$highlights[] = $lang;
		}
		$geshi = new GeSHi(str_tag_on(strip_tags($code)), $lang);
		$geshi->enable_classes();
		$code = $geshi->parse_code();
		$code = preg_replace('/<[ ]*pre( [^>]*)?>/i', '<pre$1><code>', $code);
		$code = preg_replace('/<\/pre>/i', '</code></pre>', $code);
		return self::fixClassName($code);
	}
	// Return defined styles of syntax highlight
	private static function getCodeStyle() {
		$geshi = new GeSHi;
		$languages = self::$highlights;
		foreach ($languages as $language) {
			$file = $geshi->language_path.$language.'.php';
			if (!file_exists($file)) {
				continue;
			}
			$geshi->set_language($language);
			$css .= preg_replace('/^\/\*\*.*?\*\//s', '', $geshi->get_stylesheet(false));
		}
		return $css;
	}
	// Fix class names of syntax type
	private static function fixClassName($code) {
		$entities =     array('class="command"', 'class="html"',  'class="json"',       'class="jsx"');
		$replacements = array('class="bash"',    'class="html5"', 'class="javascript"', 'class="javascript"');
		return str_replace($entities, $replacements, $code);
	}
	// Retrun Accepted HTML5 tags and AMP components
	private static function getAllowTags() {
		$html = '<h1><h2><h3><h4><h5><h6><a><p><ul><ol><li><blockquote><q><cite><ins><del><strong>'
			.'<em><code><pre><svg><table><thead><tbody><tfoot><th><tr><td><dl><dt><dd><article>'
			.'<section><header><footer><aside><figure><figcaption><time><abbr><div><span><hr><br>'
			.'<kbd><u><b><i><s><small><caption><address><button><source>';
		$amp = '<amp-embed><amp-img><amp-pixel><amp-video><amp-audio><amp-anim><amp-iframe><amp-fit-text>'
			.'<amp-access><amp-font><amp-slides><amp-ad><amp-list><amp-live-list><amp-social-share>'
			.'<amp-lightbox><amp-carousel><amp-accordion><amp-youtube><amp-vimeo>';
		return $html.$amp;
	}
	// Ignoring not allowed attribute of AMP for all tags.
	private static function ignoreHelper($m) {
		$tag = $m[0];
		$attributes = $m[2];
		preg_match_all('/([\w-]+)\s*(?:=\s*(?:"([^"]*)"|\'([^\']*)\'|(\w*[^\s>]*)))?/usix', $attributes, $matches);
		foreach ($matches[1] as $key => $val) {
			$attr = $matches[2][$key];
			if (!$attr) $attr = $matches[3][$key];
			if (!$attr) $attr = $matches[4][$key];
			if ($val == 'href' && strpos($attr, 'javascript:') !== false) {
				$tag = str_replace($attr, '#', $tag);
			}
			if (preg_match('/^(on(click|mousedown|mouseup|mousemove|mouseout|mouseover|load)|style|summary)/i', $val)) {
				$tag = str_replace($matches[0][$key], '', $tag);
			}
		}
		return $tag;
	}
	// An helper for image tag
	private static function imageHelper($m) {
		$attr = $m[1];
		$args = array();
		preg_match_all('/([\w-]+)\s*(?:=\s*(?:"([^"]*)"|\'([^\']*)\'|(\w*[^\s>]*)))?/usix', $attr, $matches);
		# Allowed attr of <amp-img>
		foreach ($matches[1] as $key => $val) {
			if (preg_match('/(src|srcset|layout|heights?|alt|role|on|tabindex|placeholder|widths?|data-*|type|class)/i', $val)) {
				$attr = $matches[2][$key];
				if (!$attr) $attr = $matches[3][$key];
				if (!$attr) $attr = $matches[4][$key];
				$args[$val] = $attr;
			}
		}
		if (!$args['layout']) {
			$args['layout'] = 'responsive';
		}
		if (strpos($args['width'], '%') !== false) {
			$args['width'] = 0;
		}
		if (strpos($args['height'], '%') !== false) {
			$args['height'] = 0;
		}
		if (!$args['width'] || !$args['height']) {
			require_once('lib/fastimage.php');
			$src = $args['src'];
			if (preg_match('/\/\/(m\.)?firejune(\.cafe24)?.com/i', $src)) {
				$src = preg_replace('/https?:\/\/(m\.)?firejune(\.cafe24)?.com/i', '', $src);
			}
			$src = preg_replace('/^\.\.?\//', '/', $src);
			if (!preg_match('/(https?:)?\/\/[^\/]+\//i', $src) && preg_match('/^\/images\//i', $src)) {
				$src = '/public'.$src;
			}
			if (strpos($src, '/public/images/') === 0) {
				$args['layout'] = 'fixed';
			}
			$img = new FastImage('./'.$src);
			$size = $img->getSize();
			if ($args['width']) {
				$args['height'] = intval($size[1] / $size[0] * $args['width'], 10);
			} elseif($args['height']) {
				$args['width'] = intval($size[0] / $size[1] * $args['height'], 10);
			} else {
				$args['width'] = $size[0];
				$args['height'] = $size[1];
			}
		}
		if ($args['width'] < 240 && $args['height'] < 240) {
			$args['layout'] = 'fixed';
		}
		if (!$args['src'] || !$args['width'] || !$args['height']) {
			return '';
		}
		$attr = '';
		foreach ($args as $key => $val) {
			$attr .= ' '.$key.'="'.$val.'"';
		}
		return '<amp-img'.$attr.'></amp-img>';
	}
	// An helper for video tag
	private static function videoHelper($m) {
		$tag = $m[0];
		$src = $m[1];
		$tag = preg_replace('/<amp-video(.*?)>/', '<amp-video$1 layout="responsive">', $tag);
		return str_replace($src, strip_tags($src, '<source>'), $tag);
	}
	// An helper for iframe tag
	private static function iframeHelper($m) {
		$tag = $m[0];
		$src = $m[1];
		preg_match('/<iframe.*width="([^"]\d+)".*height="([^"]\d+)".*>/', $tag, $m);
		$width = $m[1];
		$height = $m[2];
		# amp-youtube
		if (preg_match("/^(?:http(?:s)?:)?\/\/(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $src, $id)) {
			return '<amp-youtube data-videoid="'.$id[1].'" layout="responsive" width="'.$width.'" height="'.$height.'"></amp-youtube>';
		}
		# amp-vimeo
		if (preg_match('/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([‌​0-9]{6,11})[?]?.*/', $src, $id)) {
			return '<amp-vimeo data-videoid="'.$id[5].'" layout="responsive" width="'.$width.'" height="'.$height.'"></amp-vimeo>';
		}
		# amp-iframe
		return '<amp-iframe src="'.$src.'" width="'.$width.'" height="'.$height.'" sandbox="allow-scripts" layout="responsive" class="bdr1" frameborder="0"></amp-iframe>';
	}
	// Add using AMP extend compoenent to list
	public static function add($component) {
		if (is_array($component)) {
			$component = 'amp-'.$component[1];
		}
		if (!in_array($component, self::$components)) {
			self::$components[] = $component;
		}
	}
	// Convert contents to AMP compoents
	public static function content($html) {
  	$html = self::html($html);
		$html = self::html($html);
		# Remove unnecessary tags
		$html = preg_replace('/<(script|style).*?<\/\1>/s', '', $html);
		# Replace iframe tag to AMP component
		$html = preg_replace_callback('#<iframe.*?src="([^"]*)".*?[^>]*>.*?</iframe>#si', 'ampify::iframeHelper', $html);
		# Reduce ignore attributes
		$html = preg_replace_callback('/<([\w-]+) ([^>]*)?>/si', 'ampify::ignoreHelper', $html);
		# Code syntax hightlight
		$html = preg_replace_callback('#<pre.*?class="([^"]*)".*?[^>]*><code>(.*?)?</code></pre>#is', 'ampify::codeHelper', $html);
		# Whitelist of HTML tags allowed by AMP
		$html = strip_tags($html, self::getAllowTags());
		# Dynamic load amp components
		preg_replace_callback('/<\/amp-(youtube|vimeo|iframe|accordion|carousel)>/', 'ampify::add', $html);
		return $html;
	}
	// Convert generic HTML to AMP
	public static function html($html) {
		# Replace img, audio, and video elements with amp custom elements
		$html = str_ireplace(
			array('<img', '<video', '/video>', '<audio', '/audio>'),
			array('<amp-img', '<amp-video', '/amp-video>', '<amp-audio', '/amp-audio>'),
			$html
		);
		# Add amp attribute to html tag
		$html = preg_replace("/<[ ]*html( [^>]*)?>/i", '<html amp$1> ', $html);
		# Fix display amp custom elements
		$html = preg_replace_callback('/<amp-img(.*?)\/?>/', 'ampify::imageHelper', $html);
		$html = preg_replace_callback('#<amp-video.*?[^>]*>(.*?)?</amp-video>#is', 'ampify::videoHelper', $html);
		return $html;
	}
	// Return stylesheets string from files
	public static function css($path, $name) {
		$css = file_get_contents($path.$name.'/css/master.css');
		$css .= file_get_contents($path.$name.'/css/article.css');
		$css .= file_get_contents($path.$name.'/css/responsive.css');
		if (!empty(self::$highlights)) {
			$css .= self::getCodeStyle();
		}
		return $css;
	}
	// Return extended components when it using
	public static function js() {
		foreach (self::$components as $component) {
			$js .= '<script async custom-element="'.$component.'" src="https://cdn.ampproject.org/v0/'.$component.'-0.1.js"></script>';
		}
		return $js;
	}
}