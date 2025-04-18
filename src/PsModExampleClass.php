<?php
/**
 * This is an example class to demonstrate proper class structure
 * in a PrestaShop module
 * 
 * This file shows:
 * 1. How to use namespaces
 * 2. How to structure a class
 * 3. How to follow PrestaShop coding standards
 * 
 * You can use this as a template or create your own classes
 */

namespace Ps\PsMod;

if (!defined('_PS_VERSION_')) {
    exit;
}

class PsModExampleClass
{
    /**
     * Example method
     */
    public function exampleMethod()
    {
        return 'This is an example class';
    }
}
