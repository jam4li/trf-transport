<?php
/**
 * Index
 * load initiators for woocommerce compatibilties
 * PHP version 7.2
 *
 * @category Class
 * @package  Trust
 * @author   Mojtaba Khodami <mojtabakh@hotmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.hashbangcode.com/
 */

namespace Trust\Woo;

defined('ABSPATH') || exit;

require_once TRUST_WOO . 'metabox.php';
require_once TRUST_WOO . 'hook.php';

use Trust\Woo\MetaBox\RegisterMetaBox;
use Trust\Woo\Hook\RegisterOrder;

/**
 * Initiate Woocommerce properties
 *
 * @category Class
 * @package  Trust
 * @author   Mojtaba Khodami <mojtabakh@hotmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.hashbangcode.com/
 */
class InitTrustWooIntegration
{
    /**
     * Call initiators
     */
    function __construct()
    {
        RegisterMetaBox::init();
        RegisterOrder::init();
    }
}
