<?php
namespace newplugin\Providers;

use Plenty\Plugin\Routing\ApiRouter;
use Plenty\Plugin\RouteServiceProvider;

/**
 * Class newpluginRouteServiceProvider
 * @package newplugin\Providers
 */
class newpluginRouteServiceProvider extends RouteServiceProvider
{
    /**
     * @param  ApiRouter  $apiRouter
     */
    public function map(ApiRouter $apiRouter)
    {
        $apiRouter->version(['v1'], ['namespace' => 'newplugin\Controllers', 'middleware' => 'oauth'],
            function ($apiRouter) {

            }
        );
    }
}
