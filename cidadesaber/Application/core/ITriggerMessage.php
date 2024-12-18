<?php
namespace Application\core;

interface ITriggerMessage
{
    const ERROR = 'error';
    const SUCCESS = 'success';
    const WARNING = 'warning';

    public function getType(): string;
    public function getMessage(): string;
}
