<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2016 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Delete;

use Zend\Console\ColorInterface as Color;
use ZFrapidCore\Command\AbstractCommand;

/**
 * Class DeleteInputFilter
 *
 * @package ZF2rapid\Command\Delete
 */
class DeleteInputFilter extends AbstractCommand
{
    /**
     * @var array
     */
    protected $tasks
        = [
            'ZF2rapid\Task\Setup\WorkingPath',
            'ZF2rapid\Task\Setup\ConfigFile',
            'ZF2rapid\Task\Setup\Params',
            'ZF2rapid\Task\Check\ModulePathExists',
            'ZF2rapid\Task\Check\InputFilterExists',
            'ZF2rapid\Task\DeleteClass\DeleteInputFilter',
            'ZF2rapid\Task\DeleteFactory\DeleteInputFilterFactory',
            'ZF2rapid\Task\RemoveConfig\RemoveInputFilterConfig',
        ];

    /**
     * Start the command
     */
    public function startCommand()
    {
        // start output
        $this->console->writeGoLine('command_delete_input_filter_start');
    }

    /**
     * Stop the command
     */
    public function stopCommand()
    {
        $this->console->writeOkLine(
            'command_delete_input_filter_stop',
            [
                $this->console->colorize(
                    $this->params->paramInputFilter, Color::GREEN
                ),
                $this->console->colorize(
                    $this->params->paramModule, Color::GREEN
                )
            ]
        );
    }
}
