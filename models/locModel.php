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
			return $db->locations;
		}

		public function getLocationByZip($zip)
		{
			$conn = $this->getConnection();
			$collection = $this->getCollection($conn);
			
			if(trim($zip) == null){
				$conn->close();
				return null;
			}
			
			$where = array("identifier" => $zip);
			
			$location = $collection->findOne($where);
			
			if($location != null) {
				$conn->close();
				return $location;
			} else {
				$location = $this->newLocaitonByZip($zip, $collection);
				$conn->close();
				return $location;
			}
			
		}
		
		public function newLocaitonByZip($zip, $collection)
		{
			$json = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=$zip&sensor=false");
			$data = json_decode($json, TRUE);
			
			$newLocation = array(
					"identifier" => $zip,
					"address" => $data["results"][0]["formatted_address"],
					"lat" => $data["results"][0]["geometry"]["location"]["lat"],
					"lng" => $data["results"][0]["geometry"]["location"]["lng"]
				);
			
			$collection->insert($newLocation);
			
			return $newLocation;
		}
		
		public function removeLocation($identifier)
		{
			$conn = $this->getConnection();
			$collection = $this->getCollection($conn);
			$where = array("identifier" => $identifier);
			
			$collection->remove($where);
		}
	}
?>