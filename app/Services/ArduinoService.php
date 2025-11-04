<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ArduinoService
{
    protected $port;
    protected $baud;

    public function __construct($port = "COM3", $baud = 9600)
    {
        $this->port = $port;
        $this->baud = $baud;
    }

    private function sendCommand($command)
    {
        $powershell = <<<PS
        $port = New-Object System.IO.Ports.SerialPort {$this->port},{$this->baud},None,8,one
        $port.Open()
        Start-Sleep -Seconds 2
        $port.WriteLine("$command")
        Start-Sleep -Seconds 1
        $port.Close()
        PS;

        $cmd = 'powershell -ExecutionPolicy Bypass -Command "' . $powershell . '"';
        $result = shell_exec($cmd);

        Log::info("Sent command to Arduino: {$command}");
        Log::info("Command executed: {$cmd}");
        Log::info("Result: " . print_r($result, true));
    }

    public function startAlarm()
    {
        $this->sendCommand("START");
    }

    public function stopAlarm()
    {
        $this->sendCommand("STOP");
    }
}