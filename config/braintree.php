<?php

return [
	'environment' => 'sandbox',
	'merchartId' => env('BRAINTREE_MERCHARTID'),
	'publicKey' => env('BRAINTREE_PUBLICKEY'),
	'privateKey' => env('BRAINTREE_PRIVATEKEY'),
	'clientSideEncryptionKey' => env('BRAINTREE_CLIENTSIDE'),
];