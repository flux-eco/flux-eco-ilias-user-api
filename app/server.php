<?php
$server = new Swoole\HTTP\Server('0.0.0.0', getenv('FLUX_ILIAS_USER_IMPORT_API_PORT'));
$server->set([
    'worker_num' => 4,      // The number of worker processes to start
    'task_worker_num' => 4,  // The amount of task workers to start
    'backlog' => 128,       // TCP backlog connection number
]);
$server->on("WorkerStart", function($server, $workerId)
{
    echo "worker started";
});

// Triggered when the HTTP Server starts, connections are accepted after this callback is executed
$server->on("Start", function($server, $workerId)
{
    echo "http server started";
});

// The main HTTP server request callback event, entry point for all incoming HTTP requests
$server->on('Request', function(Swoole\Http\Request $request, Swoole\Http\Response $response)
{
    $response->end('<h1>Hello World!</h1>');
});

// Triggered when the server is shutting down
$server->on("Shutdown", function($server, $workerId)
{
    echo "http server is shutting down";
});

// Triggered when worker processes are being stopped
$server->on("WorkerStop", function($server, $workerId)
{
    echo "worker processes are being stopped";
});