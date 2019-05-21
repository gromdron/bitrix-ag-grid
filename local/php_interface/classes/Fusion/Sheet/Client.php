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
		$client = static::getBaseClient();

		$scopes = [\Google_Service_Drive::DRIVE];
		if ( !empty($params['scopes']))
		{
			$scopes = $params['scopes'];
		}
		$client->setScopes($scopes);

		if ( \file_exists(GS_TOKEN_LAST_AUTH) )
		{
			$accessToken = \json_decode( \file_get_contents(GS_TOKEN_LAST_AUTH), true);
			$client->setAccessToken($accessToken);
		}

		if ($client->isAccessTokenExpired())
		{
			if ( !$client->getRefreshToken() )
			{
			    $authUrl = $client->createAuthUrl();

			    ob_start();
			    echo "Token file {GS_TOKEN_LAST_AUTH} corrupted. You need visit: {$authUrl}";
			    echo "Then open console and execute following script (replace #TOKEN# with goole auth code): ";
			    echo '\\Fusion\\Sheet\\Client::fetchAccessToken("#TOKEN#");';
			    throw new \Exception( ob_get_clean() );
			}

			$client->fetchAccessTokenWithRefreshToken( $client->getRefreshToken() );

			static::saveAccessToken( $client->getAccessToken() );
		}

		return $client;
    }

    /**
     * Return base client object
     * @return Google_Client
     */
	public static function getBaseClient()
	{
		$client = new \Google_Client();
		$client->setApplicationName('Fusion sheet client');
		$client->setAuthConfig( GS_TOKEN_CREDENTIALS );
		$client->setIncludeGrantedScopes(true);
		$client->setAccessType("offline");
		$client->setApprovalPrompt("force");
		$client->setPrompt('select_account consent');

		return $client;
	}

    /**
     * Assign new access token from auth file
     *     print result
     * @param string $authCode 
     * @return boolean
     */
    public static function fetchAccessToken( $authCode = '' )
    {
    	$client = static::getBaseClient();
		$accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
		$client->setAccessToken($accessToken);

		if (array_key_exists('error', $accessToken))
		{
			echo implode(', ', $accessToken);
			return false;
		}

		echo 'All ok';
		return true;
    }

    /**
     * Save access token to secure file
     * @param string $token 
     * @return void
     */
    public static function saveAccessToken( $token )
    {
    	if ( !file_exists( \dirname(GS_TOKEN_LAST_AUTH) ) )
		{
			mkdir(
				dirname(GS_TOKEN_LAST_AUTH),
				0700,
				true
			);
		}

		\file_put_contents(
			GS_TOKEN_LAST_AUTH, 
			\json_encode( $token )
		);
    }
 
}