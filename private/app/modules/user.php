<?php
class module_user extends module {
	
	const USER_VALID_NOMAP = -1;
	
	function __call($name, $arg) {
		$this->output();
	}
	
	function preload() {
		$doc = document::getInstance();
		$req = new request(array("module" => "user", "action" => "userJs"));
		module::loadToPosition($req, "userjs"); 
	} 
	
	function userJs() {
		$this->output();
	}
	
	function show() {
		$this->setTemplate("profile");
		$this->output();
	}
	
	function profile() {
		$this->output();
	}
	
	function registerRpx() {
		if ($token = request::post('token')) {
			$rpx = new rpx(); 
			if ($rpx->auth($token)) {
				// existing user?
				if ($rpx->hasMap()) {
					request::setFlash("You already signed up with this account, please log on here", "logon");
					document::getInstance()->setRedirect("logon");
					return false;					
				}
				$info = $this->mapRpxInfo($rpx);
				request::setFlash($info["username"], "username");
				request::setFlash($info["realname"], "realname");
				request::setFlash($info["email"], "email");
				request::set("identifier", $rpx->getIdent(), "session");
				$res = (array) $this->validateRpx($info);
				foreach ($res as $row) {	
					request::setFlash($row[0], $row[1]);
				}
			} else {
				error::fatal('A sign-in error occured');
			}
		} else {
			document::getInstance()->setRedirect("register");
		}
		$this->output();
	}
	
	function mapRpxInfo($rpx) {
		$info = array();
		$info["username"] 	= $rpx->getInfo("preferredUsername");
		$info["realname"] 	= $rpx->getInfo("name", "formatted");
		$info["email"]		= $rpx->getInfo("email");
		return $info;
	}
	
	function logout() {
		request::set("auth", null, "session");
		request::set("uid", null, "session");
		request::set("testpanel", null, "session");
		request::set("administrator", null, "session");
		session_destroy();
		session_start();
		request::setFlash("Logged out", "logon");
		document::getInstance()->setRedirect("logon");
		
	}
	
	function saveNative() {
		$data = array();
		$data["username"]	= request::post("username");
		$data["password"] 	= request::post("password");
		$data["realname"] 	= request::post("realname");		
		$data["email"] 		= request::post("email");
		$res = $this->validateNative($data);
		if ($res === true) {
			$user = new user();
			$user->insert($data);
			request::setFlash("Your account has been created, you can now login on the right!", "general");
			document::getInstance()->setRedirect("user/logonNative");
		} else {			
			foreach ($res as $row) {	
				request::setFlash($row[0], $row[1]);
			}
			request::setFlash($data["username"], "username");
			request::setFlash($data["realname"], "realname");
			request::setFlash($data["email"], "email");
			document::getInstance()->setRedirect("user/registerNative");
		}
	}
	
	function saveRpx() {
		$data = array();
		$data["username"]	= request::post("username");
		$data["realname"] 	= request::post("realname");		
		$data["email"] 		= request::post("email");
		$res = $this->validateRpx($data);
		if ($res === true) {
			$user = new user();
			$user->insert($data);
			$rpx_ident = request::session("identifier");
			if (empty($rpx_ident)) {
				request::setFlash("Session timeout", "general");
				document::getInstance()->setRedirect("user");
				return false;
			}
			$rpx = new rpx($rpx_ident);
			$rpx->map($user->getId());
			$this->setAuthRequest($user);
			request::setFlash("Your account has been created", "general");
			request::set("identifier", null, "session");
			document::getInstance()->setRedirect("profile");
		} else {	
			foreach ($res as $row) {	
				request::setFlash($row[0], $row[1]);
			}
			request::setFlash($data["username"], "username");
			request::setFlash($data["realname"], "realname");
			request::setFlash($data["email"], "email");
			$rpx_ident = request::session("identifier");
			if (empty($rpx_ident)) {
				request::setFlash("Session timeout", "general");
				document::getInstance()->setRedirect("user");
				return false;
			} else {
				$this->setTemplate("registerRpx");
				$this->output();
			}			
		}
	}
	
	function validate($data, $fields) {
		$return = array();
		$user = new user();
		
		if (in_array("username", $fields)) {
			// username
			if (empty($data["username"])) {
				$return[] = array("Username not given", "username_error");
			} elseif ($user->loadByField("username", $data["username"])) {
				$return[] = array("Username already taken", "username_error");
			}
		}
		
		if (in_array("password", $fields)) {
			// password
			if (empty($data["password"])) {
				$return[] = array("Password not given", "password_error");
			} elseif (strlen($data["password"]) < 6) {
				$return[] = array("Password must be at least 6 characters", "password_error");
			}
		}
		
		if (in_array("email", $fields)) {
			// email
			if (empty($data["email"])) {
				$return[] = array("Email address not given", "email_error");
			} elseif (!user::validateEmail($data["email"])) {
				$return[] = array("Email address not valid", "email_error");
			} elseif ($user->loadByField("email", $data["email"])) {
				$return[] = array("Email address already in use", "email_error");
			}
		}
		
		// return result
		if (count($return) > 0) {
			return $return;
		} else {
			return true;
		}
	}
	
	function validateNative($data) {
		return $this->validate($data, array("username", "password", "email"));
	}
	
	function validateRpx($data) {
		return $this->validate($data, array("username", "email"));	
	}
	
	function requestAuthAndRedir() {
		$result = $this->requestAuth();
		if ($result) {
			if ($redir = request::post("redirect")) {
				document::getInstance()->setRedirect($redir);			
			} else {
				document::getInstance()->setRedirect("profile");
			}
		} else {
			request::setFlash("Wrong username / password combination", "logon");
			document::getInstance()->setRedirect("logon");
		}
	}
	
	function requestRpxAuthAndRedir() {
		$result = $this->requestRpxAuth();
		if ($result > 0) {
			if ($redir = request::get("redirect")) {
				document::getInstance()->setRedirect($redir);			
			} else {
				document::getInstance()->setRedirect("profile");
			}
		} else if ($result === false) {
			request::setFlash("Logon failed", "logon");
			document::getInstance()->setRedirect("logon");
		} else if ($result == self::USER_VALID_NOMAP) {
			$this->setTemplate("registerRpx");
			$this->registerRpx();
		}
	}
	
	function requestRpxAuth() {
		if ($token = request::post('token')) {
			$rpx = new rpx(); 
			if ($rpx->auth($token)) {
				// existing user?
				if ($rpx->hasMap()) {
					$user = new user($rpx->getUserId());
					if ($user->getId()) {
						$this->setAuthRequest($user);
						return $user->getId();	
					} else {
						return false;					
					}					
				} else {
					//request::setFlash("No account associated with your logon request.<br/><a href='/user/register'>Want to create a new account?</a>", "logon");
					//document::getInstance()->setRedirect("user/logon");
					return self::USER_VALID_NOMAP;
				}
			} else {
				request::setFlash("Logon failed (no auth)", "logon");
				document::getInstance()->setRedirect("/logon");
			}
		} else {
			request::setFlash("Logon failed (no token)", "logon");
			document::getInstance()->setRedirect("/logon");
		}
	}
	
	function requestAuth() {
		$username = request::get("username", "post");
		$password = request::get("password", "post");
		$user = new user();
		$user->loadByField("username", $username);
		if ($user->getId()) {
			if (user::comparePassword($password, $user->getPassword())) {
				$this->setAuthRequest($user);
				return $user->getId();	
			} else {
				return false;
			}				
		} else {
			return false;
		}					
	}
	
	function setAuthRequest(user $user) {
		request::set("auth", true, "session");
		request::set("uid", $user->getId(), "session");
		request::set("testpanel", $user->getTestpanel(), "session");
		request::set("administrator", $user->getAdministrator(), "session");
	}
	
	function isAuth() {
		return request::get("auth", "session", false);
	}
	
	function isAdministrator() {
		return request::get("administrator", "session", false); 
	}
	
	function isTestpanel() {
		return request::get("testpanel", "session", false); 
	}
	
	function getUid() {
		$id = request::get("uid", "session", false);
		return $id ? $id : false;
	}
	
	function getUsername() {
		$id = request::get("uid", "session", false);
		$user = new user($id, false);
		if ($user->getId()) {
			return $user->getUsername();
		} else {
			return false;
		}
	}
	
	function getRealname() {
		$id = request::get("uid", "session", false);
		$user = new user($id, false);
		if ($user->getId()) {
			return $user->getRealname();
		} else {
			return false;
		}
	}
	
	
}
?>