<?php
/**
 * @author kieucv
 * @copyright TCV 2018
 */
class HLS{
    
    private $requestParameter;
    private $bookingServiceSoapClient;
    private $contentServiceSoapClient;
    private $inventoryServiceSoapClient;
    /** function get xml data from HLS **/
    function __construct() {
        $this->requestParameter['Request']['Language'] = 'en';
        $this->requestParameter['Request']['Credential']['ChannelManagerUsername'] = 'pms';
        $this->requestParameter['Request']['Credential']['ChannelManagerPassword'] = 'pms';
        $this->requestParameter['Request']['Credential']['HotelId']= '5994c2db-cd76-401c-ba2e-e178ae118a8d';
        $this->requestParameter['Request']['Credential']['HotelAuthenticationChannelKey'] = '7fd41009bbaa5c0464720b07f531d721';
        $this->contentServiceSoapClient = new SoapClient('http://hbe-api.whl-staging.com/services/content/soap?wsdl',array ('trace' => 1 ,'exceptions' => 0 ));
        $this->bookingServiceSoapClient = new SoapClient('http://hbe-api.whl-staging.com/services/booking/soap?wsdl',array ('trace' => 1 ,'exceptions' => 0 ));
        $this->inventoryServiceSoapClient = new SoapClient('http://hbe-api.whl-staging.com/services/inventory/soap?wsdl',array ('trace' => 1 ,'exceptions' => 0 ));
    }
    
    public function synchronize(){
        
    }

    public function getBookings(){
        $request['StartDate'] = date('Y').'-'.date('m',(time()-50*86400)).'-'.date('d',(time()-50*86400));
        $request['EndDate'] = date('Y').'-'.date('m').'-'.date('d');
        $this->requestParameter['Request'] += $request;
        $bookingResponse = $this->bookingServiceSoapClient->GetBookings($this->requestParameter);
        if($bookingResponse){
            $bookingResponse=json_decode(json_encode($bookingResponse),true);
            if(isset($bookingResponse['GetBookingsResult']) and isset($bookingResponse['GetBookingsResult']['Bookings'])){
                return $bookingResponse['GetBookingsResult']['Bookings'];              
            }                           
        }
        else{
            return false;    
         }           
    }
    public function readNotification($bookingID){
        $request['Bookings'] = $bookingID;
        $this->requestParameter['Request'] += $request;
        return $this->bookingServiceSoapClient->ReadNotification($this->requestParameter);
    }
    public function getHotelRooms(){
        $bookingResponse =json_decode(json_encode($this->contentServiceSoapClient->GetHotelRooms($this->requestParameter)),true);
        return $bookingResponse['GetHotelRoomsResult']['Rooms'];
    }
    public function getRatePlans(){
        $ratePlansResponse =json_decode(json_encode($this->inventoryServiceSoapClient->GetRatePlans($this->requestParameter)),true);
        return $ratePlansResponse['GetRatePlansResult']['Rooms'];
    }
    public function updatePlans($plans){
        
    }
    public function updateAvailability($availability){
        
    }
    /** function update data to Newway database **/
    public function addBooking($booking){
        
    }
    public function updateBooking($booking){
        
    }
    public function getInventory($ratePlanId,$fromDate,$toDate){
        $request['RatePlans'][]=$ratePlanId;
        $request['DateRange']['From']=$fromDate;
        $request['DateRange']['To']=$toDate;
        $this->requestParameter['Request']+= $request;
        $inventoryResponse = json_decode(json_encode($this->inventoryServiceSoapClient->GetInventory($this->requestParameter)),true);
        return $inventoryResponse['GetInventoryResult']['Inventories'][0];
    }
    public function saveInventory($inventories){
        $this->requestParameter['Request']['inventories']['inventory']['RatePackages']=array();
        $this->requestParameter['Request']['inventories']['inventory']['Availabilities']=array();
        if($inventories['availability']){
            $list_id_update='';
            foreach($inventories['availability'] as $key=>$value){
                $from=explode('/',$value['from_date']);
                $to=explode('/',$value['to_date']);
                $this->requestParameter['Request']['Inventories']['inventory']['Availabilities']['Availability']['DateRange']['From']=$from[2].'-'.$from[1].'-'.$from[0];
                $this->requestParameter['Request']['Inventories']['inventory']['Availabilities']['Availability']['DateRange']['To']=$to[2].'-'.$to[1].'-'.$to[0];
                $this->requestParameter['Request']['Inventories']['inventory']['Availabilities']['Availability']['Quantity']=$value['total'];
                $this->requestParameter['Request']['Inventories']['inventory']['Availabilities']['Availability']['Action']='Set';
                $this->requestParameter['Request']['Inventories']['inventory']['RoomId']=$value['hotellink_room_id'];
                $result=json_decode(json_encode($this->inventoryServiceSoapClient->SaveInventory($this->requestParameter)),true);
                if($result['SaveInventoryResult']['Success']){
                    $list_id_update=$list_id_update?$list_id_update.=','.$value['list_id'].'':$value['list_id'];
                }
            }
            return $list_id_update;
        }
        if($inventories['ratePackages']){
            $list_id_update='';
            foreach($inventories['ratePackages'] as $key=>$value){
                $from=explode('/',$value['from_date']);
                $to=explode('/',$value['to_date']);
                $this->requestParameter['Request']['Inventories']['inventory']['RatePackages']['RatePackage']['DateRange']['From']=$from[2].'-'.$from[1].'-'.$from[0];
                $this->requestParameter['Request']['Inventories']['inventory']['RatePackages']['RatePackage']['DateRange']['To']=$to[2].'-'.$to[1].'-'.$to[0];
                $this->requestParameter['Request']['Inventories']['inventory']['RatePackages']['RatePackage']['RatePlanId']=$value['rate_plan_id'];
                /** rate **/
                $this->requestParameter['Request']['Inventories']['inventory']['RatePackages']['RatePackage']['Rate']['Amount']['Type']='FIXED_AMOUNT';
                $this->requestParameter['Request']['Inventories']['inventory']['RatePackages']['RatePackage']['Rate']['Amount']['Value']=$value['total'];
                $this->requestParameter['Request']['Inventories']['inventory']['RatePackages']['RatePackage']['Rate']['Amount']['Currency']='USD';
                $this->requestParameter['Request']['Inventories']['inventory']['RatePackages']['RatePackage']['Rate']['Action']='Set';
                /** extra adult **/
                $this->requestParameter['Request']['Inventories']['inventory']['RatePackages']['RatePackage']['ExtraAdultRate']['Amount']['Type']='FIXED_AMOUNT';
                $this->requestParameter['Request']['Inventories']['inventory']['RatePackages']['RatePackage']['ExtraAdultRate']['Amount']['Value']=$value['extra_adult'];
                $this->requestParameter['Request']['Inventories']['inventory']['RatePackages']['RatePackage']['ExtraAdultRate']['Amount']['Currency']='USD';
                $this->requestParameter['Request']['Inventories']['inventory']['RatePackages']['RatePackage']['ExtraAdultRate']['Action']='Set';
                /** extra child **/
                $this->requestParameter['Request']['Inventories']['inventory']['RatePackages']['RatePackage']['ExtraChildRate']['Amount']['Type']='FIXED_AMOUNT';
                $this->requestParameter['Request']['Inventories']['inventory']['RatePackages']['RatePackage']['ExtraChildRate']['Amount']['Value']=$value['extra_child'];
                $this->requestParameter['Request']['Inventories']['inventory']['RatePackages']['RatePackage']['ExtraChildRate']['Amount']['Currency']='USD';
                $this->requestParameter['Request']['Inventories']['inventory']['RatePackages']['RatePackage']['ExtraChildRate']['Action']='Set';
                $this->requestParameter['Request']['Inventories']['inventory']['RatePackages']['RatePackage']['StopSell']=$value['stop_sell'];
                /** extra stop sell **/
                $this->requestParameter['Request']['Inventories']['inventory']['RoomId']=$value['hotellink_room_id'];
                $result=json_decode(json_encode($this->inventoryServiceSoapClient->SaveInventory($this->requestParameter)),true);
                if($result['SaveInventoryResult']['Success']){
                    $list_id_update=$list_id_update?$list_id_update.=','.$value['list_id'].'':$value['list_id'];
                }
            }
            return $list_id_update;
        }
    }
}
//$hls=new HLS;
//$booking = $hls->getInventory();
//System::debug($booking);