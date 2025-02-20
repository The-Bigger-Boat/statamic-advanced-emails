<?php

namespace TheBiggerBoat\StatamicAdvancedEmails\Tests;

use TheBiggerBoat\StatamicAdvancedEmails\ServiceProvider;
use Statamic\Testing\AddonTestCase;

abstract class TestCase extends AddonTestCase
{
    protected string $addonServiceProvider = ServiceProvider::class;
}
