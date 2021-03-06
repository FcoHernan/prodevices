<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('YIT')) exit('Direct access forbidden.');

/**
 * Perform framework init
 *
 * @class YIT_Bootstrap
 * @package	Yithemes
 * @since 2.0.0
 * @author Your Inspiration Themes
 *
 */
class YIT_Bootstrap extends YIT_Object {

    /**
     * Constructor
     *
     * @since 2.0.0
     * @author Simone D'Amico <simone.damico@yithemes.com>
     */
    public function __construct() {
        //include required files
        $this->_includeFiles();

        //verify if WP meets the minimum requirements
        $this->_checkVersion();

        //run classes
        $this->run();
    }

    /**
     * Load required classes
     *
     * @since 2.0.0
     * @author Simone D'Amico <simone.damico@yithemes.com>
     */
    public function run() {
        if( is_admin() ) {
            YIT_Registry::get_instance()->load(
                'transient',
                'panel',
                'notifier',
                'font',
                'plugins',
                'cache',
                'dashboard',
                'free'
            );
        } else {
            // something to instance in the frontend
            YIT_Registry::get_instance()->load(
                'mobile',
                'asset'
            );
        }

        // something to instance both admin and frontend
        YIT_Registry::get_instance()->load(
            'request',
            'options',
            'css',
            'font',
            'icon',
            'navmenu',
            'image',
            'sidebar',
            'widgets',
            'update',
            'shortcodes'
        );
    }

    /**
     * Include required files
     *
     * @return void
     * @since 2.0.0
     * @author Simone D'Amico <simone.damico@yithemes.com>
     */
    protected function _includeFiles() {
        include_once( YIT_CORE_PATH . '/functions-core.php' );
        include_once( YIT_CORE_PATH . '/functions-template.php' );
        include_once( YIT_CORE_PATH . '/yit/Layout.php' );
        include_once( YIT_CORE_PLUGIN_PATH . '/yit-plugin.php' );
        include_once( YIT_THEME_PATH . '/functions-template.php' );
        include_once( YIT_THEME_PATH . '/functions-theme.php' );
        include_once( YIT_THEME_PATH . '/config.php' );
        include_once( YIT_THEME_PATH . '/hooks.php');
        include_once( YIT_THEME_PATH . '/metabox.php');
        if( YIT_IS_SHOP && function_exists( 'WC' ) ){
            include_once( YIT_THEME_PATH . '/woocommerce.php');
        }
    }

    /**
     * Verify if WP meets the minimum requirements
     *
     * @return void
     * @since 2.0.0
     * @author Simone D'Amico <simone.damico@yithemes.com>
     */
    protected function _checkVersion() {
        global $wp_version;

        if( version_compare($wp_version, YIT_MINIMUM_WP_VERSION, '<') && is_admin() ) {
            $this->getModel('message')
                ->addMessage( __('You are using an older version of Wordpress.
							   This theme should not work properly.
							   Please update your <strong>Wordpress</strong> version to the
							   latest as soon as possible.', 'yit'), 'error');
        }
    }
}
