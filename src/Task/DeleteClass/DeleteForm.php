<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2016 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\DeleteClass;

/**
 * Class DeleteForm
 *
 * @package ZF2rapid\Task\DeleteClass
 */
class DeleteForm extends AbstractDeleteClass
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        $result = $this->deleteClass(
            $this->params->formDir,
            $this->params->paramForm,
            'form'
        );

        return $result == true ? 0 : 1;
    }
}