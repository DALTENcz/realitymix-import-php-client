<?php
/**
 * @license MIT
 * @copyright (C) 2013 Dalten s.r.o.
 */
require_once dirname(__FILE__) . '../Rmix/LoggerInterface.php';
require_once dirname(__FILE__) . '../Rmix/StdOutLogger.php';
require_once dirname(__FILE__) . '../Rmix/Client.php';

// Do následujících proměných je potřeba dosadit hodnoty.
$rkId = 0; // Id realitní kanceláře.
$rkPassword = ''; // Heslo do importního rozhraní.
$rkSwKey = ''; // SW klíč do importního rozhraní.

// Vytvoříme si instanci xml rpc klienta.
$client = new Rmix_Client('https://realitymix.cz/import/rpc/', 'utf-8');
// Pokud chceme zobrazit xml komunikaci nastavíme instanci loggeru.
$client->setLogger(new Rmix_StdOutLogger());

// Zavoláme metodu getHash s parametrem $clientId
$hash = $client->getHash($rkId);

// Vytvoříme si hash hesla.
$password = md5(md5($rkPassword) . $hash['output'][1]);

$sessionId = $hash['output'][0];

// Pomocí vytvořeného hesla a získaného sessionId se přihlásíme.
$response = $client->login($sessionId, $password, $rkSwKey);

$statusMessage = isset($response['statusMessage'])
	? $response['statusMessage']
	: 'Server nevrátil žádnou odpověď';

echo $statusMessage;
