<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Create;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Command\AbstractCommand;

/**
 * Class CreateValidatorFactory
 *
 * @package ZF2rapid\Command\Create
 */
class CreateValidatorFactory extends AbstractCommand
{
    /**
     * @var array
     */
    protected $tasks
        = array(
            'ZF2rapid\Task\Setup\ProjectPath',
            'ZF2rapid\Task\Setup\ConfigFile',
            'ZF2rapid\Task\Setup\Params',
            'ZF2rapid\Task\Check\ModulePathExists',
            'ZF2rapid\Task\Check\ModuleExists',
            'ZF2rapid\Task\Check\ValidatorExists',
            'ZF2rapid\Task\GenerateFactory\GenerateValidatorFactory',
            'ZF2rapid\Task\UpdateConfig\UpdateValidatorConfig',
        );

    /**
     * Start the command
     */
    public function startCommand()
    {
        // start output
        $this->console->writeGoLine('command_create_validator_factory_start');
    }

    /**
     * Stop the command
     */
    public function stopCommand()
    {
        $this->console->writeOkLine(
            'command_create_validator_factory_stop',
            array(
                $this->console->colorize(
                    $this->params->paramValidator, Color::GREEN
                ),
                $this->console->colorize(
                    $this->params->paramModule, Color::GREEN
                )
            )
        );
    }
}
