{*
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
*}

<form method="post">
    <div class="panel">
        <h3>Configuration</h3>
        <div>
            <label>Dummy</label>
            <input type="text" class="form-control" name="PSMOD_DUMMY_CONF" value="{$data->PSMOD_DUMMY_CONF}">
        </div>
        <hr>
        <div class="text-right">
            <button type="submit" name="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</form>

<div class="panel">
    <h3><i class="icon-code"></i> Example URLs</h3>
    <p class="help-block">This module includes two implementation examples that you can use as a base for your developments:</p>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>URL</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$data->example_urls item=url}
                    <tr>
                        <td><strong>{$url.name}</strong></td>
                        <td><a href="{$url.url}" target="_blank">{$url.url}</a></td>
                        <td>{$url.description}</td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>
