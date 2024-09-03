<?php

namespace Source\Framework\Support;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Handler\SendGridHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\TelegramBotHandler;
use Monolog\Logger;

class Monolog
{
    private $Logger;
    private $Message;

    private $fileConfig;

    public function __construct($name, $message, $fileConfig = null)
    {
        $logger = new Logger($name);

        $logger->pushProcessor(function ($record){
            $record["extra"]["HTTP_HOST"] = $_SERVER["HTTP_HOST"];
            $record["extra"]["REQUEST_URI"] = $_SERVER["REQUEST_URI"];
            $record["extra"]["REQUEST_METHOD"] = $_SERVER["REQUEST_METHOD"];

            if(!empty($_SERVER["HTTP_USER_AGENT"])) {
                $record["extra"]["HTTP_USER_AGENT"] = $_SERVER["HTTP_USER_AGENT"];
            }

            return $record;
        });

        $this->Logger = $logger;
        $this->Message = $message;
        $this->fileConfig = $fileConfig;
    }

    private function levelController($level)
    {
        switch ($level){
            case"debug":
                $this->Logger->pushHandler(new BrowserConsoleHandler(Logger::DEBUG));
                break;
            case"file":
                $this->Logger->pushHandler(new StreamHandler(
                    (!empty($this->fileConfig) ? 
                        __DIR__ . "/../{$this->fileConfig['path']}/{$this->fileConfig['filename']}" : 
                        __DIR__ . "/../storage/logs/api/log.txt")
                    , Logger::WARNING));
                break;
            case"email":
                // Bluware Key -> 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
                $this->Logger->pushHandler(new SendGridHandler(
                    "apikey",
                    "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",
                    "mensagensdosistema@bluware.com.br",
                    "mensagensdosistema@bluware.com.br",
                    "Erro em bluware.info/api " . date("d/m/Y H:i:s"),
                    Logger::CRITICAL
                ));
                break;
            case"telegram":
                $bot_key = "5137243564:AAGR7Lary03N_oRhoiMUvz8wGc_yiWcwKqg";
                $bot_channel = "-1001760379792"; // @BluwareEliteApi
                $tele_handler = new TelegramBotHandler($bot_key, $bot_channel, Logger::EMERGENCY);
                $tele_handler->setFormatter(new LineFormatter("%level_name%: %message%"));
                $this->Logger->pushHandler($tele_handler);
                break;
        }
    }

    // DEBUG
    public function debug()
    {
        $this->levelController("debug");
        $this->Logger->debug($this->Message, ["logger" => true]);
    }

    public function info()
    {
        $this->levelController("debug");
        $this->Logger->info($this->Message, ["logger" => true]);
    }

    public function notice()
    {
        $this->levelController("debug");
        $this->Logger->notice($this->Message, ["logger" => true]);
    }

    // FILE
    public function warning()
    {
        $this->levelController("file");
        $this->Logger->warning($this->Message, ["logger" => true]);
    }

    public function error()
    {
        $this->levelController("file");
        $this->Logger->error($this->Message, ["logger" => true]);
    }

    // E-MAIL
    public function critical()
    {
        $this->levelController("email");
        $this->Logger->critical($this->Message, ["logger" => true]);
    }

    public function alert()
    {
        $this->levelController("email");
        $this->Logger->alert($this->Message, ["logger" => true]);
    }

    // TELEGRAM
    public function emergency()
    {
        $this->levelController("telegram");
        $this->Logger->emergency($this->Message);
    }
}