<?php
namespace Joomla\Plugin\Console\Updatefromcli\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;
use Joomla\Application\ApplicationEvents;
use Joomla\Plugin\Console\Updatefromcli\CliCommand\UpdatefromcliCommand;

class UpdatefromcliConsolePlugin extends CMSPlugin implements SubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            // Register commands BEFORE the CLI app executes
            ApplicationEvents::BEFORE_EXECUTE => 'registerCommands',
            //\Joomla\Application\ApplicationEvents::BEFORE_EXECUTE => 'registerCommands',
        ];
    }

    public function registerCommands(): void
    {
        $this->getApplication()->addCommand(new UpdatefromcliCommand());
        //$app = $this->getApplication();
        //$app->addCommand(new UpdatefromcliCommand());
    }
}
