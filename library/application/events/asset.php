<?php

namespace Application;


return [

    'asset.collect' => function() {
        $assets = func_get_arg(0);
    },

    // Must return string
    'asset.render' => function() {
        $assets = func_get_arg(0);

        $assets->each(function($asset) use (&$assets) {
            if ($assets->compress) {
                $assets->content .= $asset->compress();
            } else {
                $assets->content .= $asset->render();
            }
        });
    },
];
