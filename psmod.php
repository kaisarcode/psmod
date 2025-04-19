<?php
/**
* 2007-2022 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2022 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__.'/vendor/autoload.php';

class PsMod extends Module
{

    private $hooks = array(
        'moduleRoutes',
        'header',
        'displayBackOfficeHeader',
        'actionDispatcher'
    );

    public function __construct()
    {
        $this->name = 'psmod';
        $this->tab = 'others';
        $this->version = '1.1.0';
        $this->author = 'KaisarCode';
        $this->bootstrap = true;
        $this->need_instance = true;
        $this->displayName = 'PrestaShop Module';
        $this->description = 'Generic PrestaShop module.';
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => '8.99.99');
        parent::__construct();
    }

    // INSTALL TABS
    private function installTabs()
    {
        $this->addTab($this->displayName);
        $this->addTab('Configuration', 'Configuration');
    }

    // SET CONTROLLER ROUTES
    public function hookModuleRoutes()
    {
        return array(
            // Rutas de ejemplo
            'module-psmod-example-api' => array(
                'controller' => 'exampleApi',
                'rule' => 'example/api',
                'keywords' => array(),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'psmod'
                )
            ),
            'module-psmod-example-page' => array(
                'controller' => 'examplePage',
                'rule' => 'example/page',
                'keywords' => array(),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'psmod'
                )
            )
        );
    }

    // Initialize Tools in dispatcher
    public function hookActionDispatcher()
    {
        require_once(_PS_ROOT_DIR_.'/classes/Tools.php');
    }

    // ADD JS & CSS TO ADMIN
    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
        }
    }

    // ADD JS & CSS TO FRONT
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path . '/views/js/front.js');
        $this->context->controller->addCSS($this->_path . '/views/css/front.css');
    }

    // CONFIG PAGE
    public function getContent()
    {
        $this->regHooks();
        $this->postProcess();

        $data = new stdClass();
        $data->PSMOD_DUMMY_CONF = Configuration::get('PSMOD_DUMMY_CONF');

        // Add example URLs
        $shopUrl = Context::getContext()->shop->getBaseURL(true);
        $data->example_urls = array(
            array(
                'name' => 'Example API',
                'url' => $shopUrl . 'example/api',
                'description' => 'Example API endpoint that returns a JSON response. Useful as a base for implementing custom APIs.'
            ),
            array(
                'name' => 'Example Page',
                'url' => $shopUrl . 'example/page',
                'description' => 'Example page showing how to implement a view with Smarty template. Useful as a base for creating front-office pages.'
            )
        );

        // Render template
        $this->context->smarty->assign('data', $data);
        return $this->context->smarty->fetch("{$this->local_path}views/templates/admin/config.tpl");
    }

    // POSTPROCESS CONFIG
    public function postProcess()
    {
        if (Tools::isSubmit('submit')) {
            $val = pSQL(Tools::getValue('PSMOD_DUMMY_CONF'));
            Configuration::updateValue('PSMOD_DUMMY_CONF', $val);
        }
    }

    // INSTALL MODULE
    public function install()
    {
        $this->regHooks();
        $this->installTabs();
        require("{$this->local_path}sql/install.php");
        return parent::install();
    }

    // UNINSTALL MODULE
    public function uninstall()
    {
        $this->uninstallTabs();
        require("{$this->local_path}sql/uninstall.php");
        Configuration::deleteByName('PSMOD_DUMMY_CONF');
        return parent::uninstall();
    }

    // ENABLE MODULE
    public function enable($force_all = false)
    {
        parent::enable();
        $this->regHooks();
        $this->installTabs();
        return true;
    }

    // DISABLE MODULE
    public function disable($force_all = false)
    {
        parent::disable();
        $this->uninstallTabs();
        return true;
    }

    // REGISTER HOOKS
    private function regHooks()
    {
        foreach ($this->hooks as $hook) {
            try {
                if (!$this->isRegisteredInHook($hook)) {
                    $this->registerHook($hook);
                }
            } catch (Exception $e) {
                continue;
            }
        }
        return true;
    }

    // UNINSTALL TABS
    private function uninstallTabs()
    {
        $name = $this->name;
        $tabs = Tab::getCollectionFromModule($name);
        foreach ($tabs as $tab) {
            $tab->delete();
        }
    }

    // ADD MENU TAB
    public function addTab($title, $class = '', $icon = 'settings', $hidden = false)
    {
        $pfx = 'Admin'.get_class($this); // class prefix
        $id_pnt = Tab::getIdFromClassName($pfx); // parent id
        $id_tab = Tab::getIdFromClassName($pfx.$class); // tab id
        $langs = Language::getLanguages(false);
        if (!$id_tab) {
            $tab = new Tab();
            $tab->class_name = $pfx.$class;
            $tab->module = $this->name;
            $tab->id_parent = 0;
            $class && $tab->id_parent = $id_pnt;
            $class && $tab->icon = $icon;
            $hidden && $tab->id_parent = -1;
            foreach($langs as $lang){
                $tab->name[$lang['id_lang']] = $title;
            }
            $tab->save();
        }
    }
}
