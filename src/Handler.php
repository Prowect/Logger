<?php

namespace Drips\Logger;

use Monolog\Handler\AbstractHandler;
use Drips\Debugbar\Debugbar;
use Monolog\Logger as Monolog;

class Handler extends AbstractHandler
{
    protected $logLevels = array(
        Monolog::DEBUG     => '#CCD1D9',
        Monolog::INFO      => '#4FC1E9',
        Monolog::NOTICE    => '#A0D468',
        Monolog::WARNING   => '#FFC354',
        Monolog::ERROR     => '#E9573F',
        Monolog::CRITICAL  => '#FC6E51',
        Monolog::ALERT     => '#DA4453',
        Monolog::EMERGENCY => '#F5F7FA',
    );

   public function handle(array $record)
   {
       if (!$this->isHandling($record)) {
           return false;
       }

       if (class_exists('Drips\Debugbar\Debugbar')) {
           $debugbar = Debugbar::getInstance();
           if (!array_key_exists('logger', $debugbar->getTabs())) {
               $debugbar->registerTab('logger', 'Logs', '<style>'.file_get_contents(__DIR__.'/style.css').'</style>');
           }
           $log_message = '<pre class="drips-logger-record" style="color: '.$this->logLevels[$record['level']].'"><strong>['.$record['level_name'].']</strong>: '.$record['message'].'</pre>';
           $debugbar->appendTab('logger', $log_message);
       }

       return false === $this->bubble;
   }
}