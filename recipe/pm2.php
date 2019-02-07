<?php

namespace Deployer;

task('pm2:restart', function () {
    run("pm2 delete all || true");
    run("cd {{release_path}} && pm2 start socket.sh");
});
