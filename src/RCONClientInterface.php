<?php

namespace Cbwar\FactorioRcon;

interface RCONClientInterface
{
    public function execute(string $command): string;

}
