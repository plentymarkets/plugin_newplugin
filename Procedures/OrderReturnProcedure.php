<?php

namespace newplugin\Procedures;

use Plenty\Modules\Authorization\Services\AuthHelper;
use Plenty\Modules\EventProcedures\Events\EventProceduresTriggered;
use Plenty\Modules\Order\Models\Order;
use Plenty\Plugin\Log\Loggable;
use newplugin\Configuration\PluginConfiguration;
use Throwable;

/**
 * Class TestProcedure
 * @package newplugin\Procedures
 */
class TestProcedure
{
    use Loggable;

    /**
     * @return void
     */
    public function run(

    ) {
    }
}
