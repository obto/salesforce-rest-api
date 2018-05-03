# salesforce-rest-api
A simple PHP client for the Salesforce REST API

## Installation

Install with composer:
```
composer require "Obto/salesforce-rest-api"
```

## Usage

Initialize the `Salesforce\Client` class, call the APIs you want.

```php
use Obto\Salesforce;
use Obto\Salesforce\Exception;

$authentication = new Salesforce\Authentication\PasswordAuthentication(
	"ClientId",
	"ClientSecret",
	"Username",
	"Password",
	"SecurityToken"
);
$salesforce = new Salesforce\Client($authentication, "na5");

try {
	$contactQueryResults = $salesforce->query("SELECT AccountId, LastName
		FROM Contact
		WHERE FirstName = ?",
		array('Alice')
	);
	foreach($contactQueryResults as $queryResult) {
		print_r($queryResult);  // The output of a single record from the query API JSON, converted to associative array
	}

    $contactQueryResults2 = $salesforce->query("SELECT AccountId, LastName
        FROM Contact
        WHERE FirstName = :firstName",
        array('firstName' => 'Bob')
    );
    foreach($contactQueryResults2 as $queryResult) {
        print_r($queryResult);  // The output of a single record from the query API JSON, converted to associative array
    }

} catch(Exception\SalesforceNoResults $e) {
	// Do something when you have no results from your query
} catch(Exception\Salesforce $e) {
	// Error handling
}
```


