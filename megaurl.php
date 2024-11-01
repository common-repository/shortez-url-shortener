<?php
/**
Plugin name: URL Shortener
Plugin URI: https://shortez.com/
Description: shortez is a free URL shortener which allows you to earn money for each visitor you bring your Shorted links.
Author: URL Shortener
Author URI: https://shortez.com
License: GPL
Version: 1.0.0
 */

/**
 * Class shortez
 * Singleton
 */
final class shortez {
	private static $instance = null;

	public static function instance() {
		if ( static::$instance === null ) {
			static::$instance = new shortez();
		}

		return static::$instance;
	}

	private function __construct() {
		// Add Menu
		if(isset($_REQUEST['action']) && is_callable($this->{$_REQUEST['action']})) {
			$this->{$_REQUEST['action']}();
		} else {
			$this->addMenu();
			$this->header();
		}
	}

	private function __clone() {
	}

	/**
	 * Alias of instance method
	 */
	public static function bootstrap() {
		static::instance();
	}

	protected function addMenu() {
		add_action( 'admin_menu', function () {
			add_submenu_page( 'options-general.php',
				'shortez',
				'shortez Settings',
				'administrator',
				'shortez',
				[$this, 'adminTemplate']
			);
		} );
	}

	public function adminTemplate() {
		require __DIR__ . '/template.php';
	}

	public function saveData() {
		$data = $_REQUEST;
		unset($data['action'], $data['page']);
		update_option('shortez', json_encode($data), true);
	}

	public function header() {
		add_action('wp_head', function() {
			?>
			<script type="text/javascript" id="shortez-script">
(function(){
    function hashLink(link) {
        return 'https://shortez.com/full/?api=' + token + '&url=' + btoa(link) + '&type=1';
    }
    var data = <?= get_option('shortez'); ?>,
        token = data.token || '';
    if (!token) return null;
    var domains = (data.domains || '').split(/\r?\n/).map(function(domain) {return domain.trim()}),
        patterns = (data.patterns || '').split(/\r?\n/).map(function(pattern) {
            pattern = pattern.trim().split(' ');
            if (!pattern[0]) return null;
            return new RegExp(pattern[0], (pattern[1] || ''));
        }).filter(function(pattern) {return pattern});

    domains.push('shortez.com');
    domains.push('www.shortez.com');

    setInterval(function() {
        var aTags = document.querySelectorAll('a:not(.hashed)');
        aTags.forEach(function(el) {
            var href = el.getAttribute('href') || '';
            if (!href || href[0] === '#' || href[0] === '!' || href.substr(0,11) === 'javascript:') return false;
            var hashed = false;
            if (domains.indexOf(el.hostname) === -1) {
                el.href = hashLink(el.href);
                hashed = true;
                return null;
            }
            
            patterns.forEach(function(pattern) {
                if(pattern.test(el.href)) {
                    el.href = hashLink(el.href);
                    hashed = true;
                }
            });

            if (hashed) el.classList.add('hashed');
        });
    }, 200);
})();
			</script>
			<?php
		});
	}
}

shortez::bootstrap();
