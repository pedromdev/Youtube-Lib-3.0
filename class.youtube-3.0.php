<?php

require('google-api/src/Google/Client.php');
require('google-api/src/Google/Service/YouTube.php');

session_start();

/**
*
*	Classe YoutubeAPI3
*
*	@author Pedro Marcelo de Sá Alves
*	@version 1.0
*/

class YoutubeV3
{

	/**
	*	Client ID do aplicativo
	*
	*	@since 1.0
	*	@access private
	*	@var string
	*/
	private $client_id = '';

	/**
	*	Client Secret do aplicativo
	*
	*	@since 1.0
	*	@access private
	*	@var string
	*/
	private $client_secret = '';

	/**
	*	Chave API do aplicativo
	*
	*	@since 1.0
	*	@access private
	*	@var string
	*/
	private $api_key = '';

	/**
	*	Variável de acesso da classe Google_Client
	*
	*	@since 1.0
	*	@access private
	*	@var object
	*/
	private $google_client = null;

	/**
	*	Variável de acesso da classe Google_Service_YouTube
	*
	*	@since 1.0
	*	@access private
	*	@var object
	*/
	private $youtube_client = null;

	/**
	*	Code retornado
	*
	*	@since 1.0
	*	@access private
	*	@var string
	*/
	private $code = '';

	/**
	*	Token de acesso
	*
	*	@since 1.0
	*	@access private
	*	@var string
	*/
	private $token = '';

	/**
	*	Método construtor
	*
	*	@since 1.0
	*	@param string $client_id Client ID do aplicativo
	*	@param string $client_scret Client Secret do aplicativo
	*	@param string $api_key API key do aplicativo
	*/
	public function __construct($client_id = '', $client_secret = '', $api_key = '')
	{
		$this->google_client = new Google_Client();

		if (is_string($client_id) && $client_id != '')
		{
			$this->google_client->setClientId($client_id);
			$this->client_id = $client_id;
		}

		if (is_string($client_secret) && $client_secret != '')
		{
			$this->google_client->setClientSecret($client_secret);
			$this->client_secret = $client_secret;
		}

		if (is_string($api_key) && $api_key != '')
		{
			$this->api_key = $api_key;
		}

	}

	/*
	|-----------------------------------
	|	Métodos acessores
	|-----------------------------------
	*/

	/**
	*	Método para inserir um Client ID
	*
	*	@since 1.0
	*	@param string $client_id Client ID do aplicativo
	*/
	public function setClientId($client_id)
	{
		if (is_string($client_id) && $client_id != '')
		{
			$this->client_id = $client_id;
		}
	}

	/**
	*	Método para recuperar o Client ID atual
	*
	*	@since 1.0
	*	@return string Retorna o Client ID do aplicativo
	*/
	public function getClientId()
	{
		return $this->client_id;
	}

	/**
	*	Método para inserir um Client Secret
	*
	*	@since 1.0
	*	@param string $client_secret Client Scret do aplicativo
	*/
	public function setClientSecret($client_secret)
	{
		if (is_string($client_secret) && $client_secret != '')
		{
			$this->client_secret = $client_secret;
		}
	}

	/**
	*	Método para recuperar o Client Secret atual
	*
	*	@since 1.0
	*	@return string Retorna o Client Secret do aplicativo
	*/
	public function getClientSecret()
	{
		return $this->client_secret;
	}

	/**
	*	Método para inserir uma API Key
	*
	*	@since 1.0
	*	@param string $api_key API Key do aplicativo
	*/
	public function setApiKey($api_key)
	{
		if (is_string($api_key) && $api_key != '')
		{
			$this->api_key = $api_key;
		}
	}

	/**
	*	Método para recuperar a API Key atual
	*
	*	@since 1.0
	*	@return string Retorna a API Key do aplicativo
	*/
	public function getApiKey()
	{
		return $this->api_key;
	}

	/**
	*	Método para inicializar as configurações
	*
	*	@since 1.0
	*/
	public function init()
	{
		$this->google_client->setScopes('https://www.googleapis.com/auth/youtube');
		$redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'], FILTER_SANITIZE_URL);
		$this->google_client->setRedirectUri($redirect);

		$this->youtube_client = new Google_Service_YouTube($this->google_client);

		if (isset($_GET['code']))
		{
			if (strval($_SESSION['state']) !== strval($_GET['state'])) {
				die('The session state did not match.');
			}

			$this->code = $_GET['code'];
			$this->google_client->authenticate($this->code);
			$_SESSION['token'] = $this->getAccessToken();
		}
	}
}