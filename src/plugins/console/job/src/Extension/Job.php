<?php
/**
 * @package   Job
 * @copyright Copyright (c)2021 Alikon
 * @license   GNU General Public License version 3, or later
 */

namespace Joomla\Plugin\Console\Job\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\Application\ApplicationEvents;
use Joomla\Application\Event\ApplicationEvent;
use Joomla\CMS\Application\ConsoleApplication;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Console\Command\AbstractCommand;
use Joomla\Database\DatabaseDriver;
use Joomla\Event\SubscriberInterface;
use Joomla\Component\Jobs\Administrator\Extension;
use Psr\Container\ContainerInterface;
use Joomla\CMS\Console\Loader\WritableLoaderInterface;

class Job extends CMSPlugin implements SubscriberInterface
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  4.0.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Application object.
	 *
	 * @var    ConsoleApplication
	 * @since  4.0.0
	 */
	protected $app;

	/**
	 * Returns an array of events this subscriber will listen to.
	 *
	 * @return  array
	 *
	 * @since   4.0.0
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			ApplicationEvents::BEFORE_EXECUTE => 'registerCommands',
		];
	}

	/**
	 * Constructor
	 *
	 * @param   DispatcherInterface  $subject   The object to observe
	 * @param   array                $config    An optional associative array of configuration settings.
	 *
	 * @since   4.0.0
	 */
	public function __construct($subject, $config = [])
	{
		parent::__construct($subject, $config);
	}

	/**
	 * Registers command classes to the CLI application.
	 *
	 * This is an event handled for the ApplicationEvents::BEFORE_EXECUTE event.
	 *
	 * @param   ApplicationEvent  $event  The before_execute application event being handled
	 *
	 * @since   4.0.0
	 */
	public function registerCommands(): void
	{
		$serviceId = '\Joomla\Component\Jobs\Administrator\Extension\JobListCommand';

		Factory::getContainer()->share(
			$serviceId,
			function (ContainerInterface $container) {
				return new \Joomla\Component\Jobs\Administrator\Extension\JobListCommand($container->get('db'));
			},
			true
		);

		Factory::getContainer()->get(WritableLoaderInterface::class)->add('jobs:list', $serviceId);

		$serviceId = '\Joomla\Component\Jobs\Administrator\Extension\SchedulerCommand';

		Factory::getContainer()->share(
			$serviceId,
			function (ContainerInterface $container) {
				return new \Joomla\Component\Jobs\Administrator\Extension\SchedulerCommand();
			},
			true
		);

		Factory::getContainer()->get(WritableLoaderInterface::class)->add('jobs:run', $serviceId);
	}
}
