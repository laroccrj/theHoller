<?php
	class HollerModel
	{
		private function getConnection()
		{
			return new Mongo('localhost');
		}
		
		private function getCollection($conn)
		{
			$db = $conn->holler;
			return $db->users;
		}

		public function addUser($firstName, $lastName, $email, $password)
		{
			$conn = $this->getConnection();
			$collection = $this->getCollection($conn);
			
			$query = array(
						'first' => $firstName,
						'last' => $lastName,
						'email' => $email,
						'password' => $password
					);
			
			$collection->insert($query);
			
			$conn->close();
		}
		
		public function addRecentLocation($id, $location)
		{
			$conn = $this->getConnection();
			$collection = $this->getCollection($conn);
			
			if(trim($location) == null){
				$conn->close();
				return null;
			}
			
			$where = array("_id" => $id);
			$query = array('$push' => array("locations" => $location));
			$options = array('$upsert' => true);
			
			$collection->update($where, $query, $options);
			
			$conn->close();
			return null;
		}
		
		public function getUserByEmail($email)
		{
			$conn = $this->getConnection();
			$collection = $this->getCollection($conn);
			
			$query = array('email' => $email);
			
			$info = $collection->findOne($query);
			
			return $info;
		}
		
		public function getUserById($id)
		{
			$conn = $this->getConnection();
			$collection = $this->getCollection($conn);
			
			$query = array('_id' => new MongoId($id));
			
			$info = $collection->findOne($query);
			
			return $info;
		}
	}
?>