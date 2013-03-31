<?php
	class UserController
	{
		private $model;

		public __construct($model)
		{
			$this->model = $model; 
		}

		public function createAccount($firstName, $lastName, $email, $password, $confPassword)
		{
			$error = array();
			
			//Filtering
			if(!filter_var($email, FILTER_VALIDATE_EMAIL))
				$error[] = "Please enter a valid email";
			
			if(!$this->filterInput($firstName))
				$error[] = "Please enter a valid first name";
				
			if(!$this->filterInput($lastName))
				$error[] = "Please enter a valid last name";
				
			if(!$this->filterInput($passowrd))
				$error[] = "Please enter a valid password";
				
			if($password != $confPassword)
				$error[] = "Passwords do not match";
			
			if($model->getUserByEmail($email) != null)
				$error[] = "An account has already been created with that email";
			
			if($error != null)
				return $error;
				
			$this->model->addUser($firstName, $lastName, $email, $password);
			return null;
		}
		
		private function filterInput($input)
		{
			$newInput = filter_var($input, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
			$newInput = filter_var($newInput, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
			
			if($newInput == $input) return true;
			else return false;
		}
	}
?>