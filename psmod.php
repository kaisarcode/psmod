<?php
/**
* 2007 PrestaShop
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
*  @author    KaisarCode <info@kaisarcode.com>
*  @copyright 2022 KaisarCode
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class PSMod extends Module
{
    
    private $hooks = array(
        'header',
        'backOfficeHeader'
    );
    
    public function __construct()
    {
        $this->name = 'psmod';
        $this->displayName = $this->l('PS Mod');
        $this->description = $this->l('Generic module');
        $this->tab = 'others';
        $this->version = '1.0.0';
        $this->author = 'KaisarCode';
        
        $this->bootstrap = 1;
        $this->need_instance = 0;
        $this->path = realpath(dirname(__FILE__));
        $this->ps_version = Configuration::get('PS_VERSION_DB');
        $this->ps_version = explode('.', $this->ps_version);
        $this->ps_version = $this->ps_version[0].$this->ps_version[1];
        $this->ps_versions_compliancy = array(
            'min' => '1.7',
            'max' => _PS_VERSION_
        );
        
        parent::__construct();
    }
    
    // CONFIG PAGE
    public function getContent()
    {
        $data = new stdClass();
        
        $lnk = new Link();
        $ssl = Tools::usingSecureMode();
        $data->controller_service = $lnk->getModuleLink($this->name, 'service', [], Tools::usingSecureMode());
        $data->controller_dummy = $lnk->getModuleLink($this->name, 'dummy', [], Tools::usingSecureMode());
        
        $this->addMediaFiles('admin/config');
        $this->context->smarty->assign('data', $data);
        return $this->fetch("module:psmod/views/templates/admin/config.tpl");
    }
    
    // BACKOFFICE HEADER
    public function hookBackOfficeHeader()
    {
        $this->addMediaFiles('admin/header');
    }
    
    // FRONT HEADER
    public function hookHeader()
    {
        $this->addMediaFiles('front/header');
    }
    
    // INSTALL MODULE
    public function install()
    {
        parent::install();
        $this->installTabs();
        $this->registerHooks();
        include "{$this->path}/includes/install.php";
        return true;
    }
    
    // UNINSTALL MODULE
    public function uninstall()
    {
        parent::uninstall();
        $this->uninstallTabs();
        include "{$this->path}/includes/uninstall.php";
        return true;
    }
    
    // ENABLE MODULE
    public function enable($force_all = false)
    {
        parent::enable();
        $this->installTabs();
        $this->registerHooks();
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
    private function registerHooks()
    {
        foreach ($this->hooks as $hook) {
            if (!$this->isRegisteredInHook($hook)) {
                $this->registerHook($hook);
            }
        }
    }
    
    // INSTALL TABS
    private function installTabs()
    {
        $this->addTab($this->displayName);
        $this->addTab($this->l('Dummy'), 'Dummy');
    }
    
    // UNINSTALL TABS
    private function uninstallTabs()
    {
        $dbx = _DB_PREFIX_;
        $sql = "
        SELECT id_tab FROM {$dbx}tab
        WHERE module = '{$this->name}'";
        $tabs = Db::getInstance()->executeS($sql);
        foreach ($tabs as $t) {
            $tab = new Tab($t['id_tab']);
            $tab->delete();
        }
    }
    
    // ADD MENU TAB
    public function addTab($txt, $cls = '', $ico = 'settings')
    {
        $pfx = 'Admin'.get_class($this); // class prefix
        $pid = Tab::getIdFromClassName($pfx); // parent id
        $tid = Tab::getIdFromClassName($pfx.$cls); // tab id
        $lns = Language::getLanguages(false);
        if (!$tid) {
            $tab = new Tab();
            $tab->class_name = $pfx.$cls;
            $tab->module = $this->name;
            $tab->id_parent = 0;
            $cls && $tab->id_parent = $pid;
            $cls && $tab->icon = $ico;
            foreach($lns as $ln){
                $tab->name[$ln['id_lang']] = $txt;
            }
            $tab->save();
        }
    }
    
    // ADD CSS AND JS TO PAGE
    public function addMediaFiles($page, $data = [])
    {
        $name = $this->name;
        $path = rtrim($this->_path, "/");
        Media::addJsDef(array($name => $data));
        $this->context->controller->addJS("$path/views/js/$page.js");
        $this->context->controller->addCSS("$path/views/css/$page.css");
    }
}
