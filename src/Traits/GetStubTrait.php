<?php

namespace Sheenazien8\Hascrudactions\Traits;

/**
 * Class GetStubTrait
 * @author sheenazien8
 */
trait GetStubTrait
{
    private function getStub($type): ?string
    {
        return file_get_contents(__DIR__ . "/../../resources/stubs/$type.stub");
    }
}
