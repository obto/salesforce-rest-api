<?php
namespace Obto\Salesforce\Exception;

class SessionExpired extends SalesforceAuthentication
{
    const ERROR_CODE = 401;
}
