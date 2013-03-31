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
			return $db->hollers;
		}

		public function addHoller($lng, $lat, $holler, $user)
		{
			$conn = $this->getConnection();
			$collection = $this->getCollection($conn);
			
			$newHoller = array(
				"location" => array("lng" => $lng, "lat" => $lat),
				"holler" => $holler,
				"user" => $user,
				"time" => date("U")
			);
			
			$collection->insert($newHoller);
			
			$conn->close();
			return $newHoller;
		}
		
		public function findHollers($lat, $lng, $miles) 
		{
			$conn = $this->getConnection();
			$collection = $this->getCollection($conn);
			
			$range= $miles / 69;
			
			$query = 
			array("location" => 
				array(
					'$geoWithin' => array(
						'$center' => array (
							array($lng, $lat), $range
						)
					)
				)
			);
			$hollers = $collection->find($query);
			
			$conn->close();
			return $hollers;
		}
	}
?>