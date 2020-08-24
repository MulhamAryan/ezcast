<?php

    include_once 'config.inc';

    require_once __DIR__.'/../commons/lib_syncrhonize.php';

    $album = $argv[1];
    $asset = $argv[2];
    if ($argc!=3) {
        echo "Usage: ".$argv[0]." <album_name> <asset_time>" . PHP_EOL;
        exit(1);
    }
    else{
        $sync = new Synchronizer($album,$asset);
        $sync->push_All_Changes();
        //print ($repository_path. "/" . $album . "/" . $asset);
    }
?>