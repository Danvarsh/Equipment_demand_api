# Equipment_demand_api by Danvarsh Badribabu

This is a simple equipment demand API. This API enables an imaginary client to display per station:
  1) A timeline (for every future calendar day) where each day of this timeline will contain the total amount of each equipment type booked per day for that particular station (for example: today -> 5 x toilets and 2 x sets of bed sheets)
  2) A count of equipment on hand for this particular day, for this particular station. This calculation is based on existing orders in the system. The system is able to represent the data as a REST API endpoints that feed san imaginary frontend client.
 
This solution is implemented in Slim PHP framework(version 4) and mysql and developed and tested with PhpUnit tests
