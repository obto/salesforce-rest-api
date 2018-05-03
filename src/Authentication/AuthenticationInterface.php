<?php
namespace Obto\Salesforce\Authentication;

use Obto\Salesforce\Exception;

interface AuthenticationInterface
{
    /**
     * @return string
     * @throws Exception\SalesforceAuthentication
     */
    public function getAccessToken();

    public function invalidateAccessToken();
}
