<?php

require( dirname(__FILE__) . '/password.php' );

class sspmod_guarani3_Auth_Source_GuaraniAuth extends sspmod_core_Auth_UserPassBase {

	private $pdo;

	public function __construct($info, $config) {
		parent::__construct($info, $config);
		$dsn = 'pgsql:host=%host%;port=%port%;dbname=%dbname%;user=%user%;password=%password%';

		$camposConf = array('host', 'port', 'dbname', 'user', 'password');

		foreach ($camposConf as $campo) {
			if (!isset($config[$campo])) {
				throw new Exception("No esta configurado el campo $campo");
			}
			$dsn = str_replace("%$campo%", $config[$campo], $dsn);
		}

		$this->pdo = new PDO($dsn);
	}

	protected function login($username, $password) {

		$sql = "SELECT *
			FROM
				negocio.mdp_personas
			WHERE
				usuario = '$username'";

		$result = $this->pdo->query($sql);
		$datos = $result->fetchAll();

		// Verifico si recupere algun usuario
		if (count($datos) == 0) {
			throw new SimpleSAML_Error_Error('WRONGUSERPASS');
		}

		$alumno = $datos[0];

		// Verifico la clave
                if (!password_verify(md5($password), $alumno['clave'])) {
			throw new SimpleSAML_Error_Error('WRONGUSERPASS');
                }

		$user = array();
		$user['uid'] = array($alumno['persona']);
		$user['eduPersonPrincipalName'] = array($alumno['persona']);
		//$user['o'] = array($alumno['UNIDAD_ACADEMICA']);
		$user['givenName'] = array($alumno['nombres']);
		$user['sn'] = array($alumno['apellido']);
		$user['mail'] = array($this->getMail($alumno['persona']));
                $user['cn'] = array($alumno['apellido'].', '.$alumno['nombres']);

		// Para el enrolment
		//$cursos = $this->getCursos($alumno['LEGAJO']);
		/*$cursos = array();

		$user['schacUserStatus'] = array();
		foreach ($cursos as $curso) {
			$user['schacUserStatus'][] = "urn:mace:terena.org:schac:userStatus:ar:campus.unaj.edu.ar:{$curso['COMISION']}:student:active";
		}*/
		//var_dump($user);
		//die();
		return $user;
	}

	private function getMail($persona)
	{
		$sql = "SELECT * FROM negocio.mdp_personas_contactos  WHERE persona = $persona";
		$result = $this->pdo->query($sql);
		$datos = $result->fetchAll();
		return $datos[0]['email'];
	}

	private function getCursos($legajo)
	{
		$sql = "SELECT * FROM sga_insc_cursadas WHERE legajo = '$legajo'";
                $result = $this->pdo->query($sql);
                $datos = $result->fetchAll();
		return $datos;
	}
}
