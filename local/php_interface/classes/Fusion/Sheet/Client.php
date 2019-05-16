<?php

namespace Fusion\Sheet;

class Client
{
	private function __construct()
	{}

	private function __clone()
	{}

	private function __wakeup()
	{}

	public static function get( $params = [] )
	{
		$client = new \Google_Client();
		$client->setApplicationName('Fusion sheet client');

		$scopes = [\Google_Service_Drive::DRIVE];
		if ( !empty($params['scopes']))
		{
			$scopes = $params['scopes'];
		}
		$client->setScopes($scopes);

		$client->setAccessType("offline");

		//$client->setDeveloperKey($api_key);
		/*
define('GS_SHEET_API_KEY', '3e09062a82611c0d943e9b70d0cd44beb6f1d04b');

// client id:  581177354109-41497am1t3op69lg72tl9u8lo3irv0bp.apps.googleusercontent.com 
// client secret: CCFLyoH3WbrVSYW1DV0ENkKX
		*/

		$credentialsFile = GS_CREDENTIAL_FILE;
		if ( !empty($params['credentialsFile']) && file_exists($params['credentialsFile']) )
		{
			$credentialsFile = $params['credentialsFile'];
		}

		$client->setAuthConfig( $credentialsFile );

		$tokenPath = GS_TOKEN_FILE;

		if ( !empty($params['tokenPath']) && file_exists($params['tokenPath']) )
		{
			$tokenPath = $params['tokenPath'];
		}

		if ( \file_exists($tokenPath) )
		{
			$accessToken = \json_decode(
				\file_get_contents($tokenPath), 
				true
			);
			$client->setAccessToken($accessToken);
		}

		if ( $client->isAccessTokenExpired() )
		{
		    if ( $client->getRefreshToken() )
		    {
		        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
		    }
		    else
		    {
		        $authUrl = $client->createAuthUrl();
		        fwrite(STDOUT, "Open the following link in your browser:\n{$authUrl}\n\r");
		        fwrite(STDOUT, 'Enter verification code: ');
		        $authCode = trim(fgets(STDIN));

		        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
		        $client->setAccessToken($accessToken);

		        if ( \array_key_exists('error', $accessToken))
		        {
		            throw new \Exception(join(', ', $accessToken));
		        }
		    }

		    if ( !file_exists(dirname($tokenPath)) )
		    {
		        \mkdir(
		        	\dirname($tokenPath),
		        	0700,
		        	true
		       	);
		    }
		    
		    \file_put_contents(
		    	$tokenPath, 
		    	\json_encode($client->getAccessToken())
		    );
		}
		return $client;
    }
 
}