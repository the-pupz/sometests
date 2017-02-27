<?php
namespace Principal;

class General
{
	function BubbleSort(){
		//Array [1 ... 20]
		$data =  range('1','20');
		//Everyday I'm shuffling.
		shuffle($data);
		$data1 = $data;
		//Doing BubbleSort
		$nowData = null;
		for ( $i = 0; $i < count($data); $i++){
			for ($j = 0; $j < count($data); $j++){
				if($data[$i] < $data[$j]){
					$nowData = $data[$i];
					$data[$i] = $data[$j];
					$data[$j] = $nowData;
				}
			}	
		}

		$return = array(
			'data' => $data, 
			'data1' => $data1
		);

		return $return;
	}	
}

class ListNode
{
    /* Data to hold */
    public $data;
 
    /* Link to next node */
    public $next;
 
 
    /* Node constructor */
    function __construct($data)
    {
        $this->data = $data;
        $this->next = NULL;
    }
 
    function readNode()
    {
        return $this->data;
    }
}
 
 
class LinkList
{
    /* Link to the first node in the list */
    private $firstNode;
 
    /* Link to the last node in the list */
    private $lastNode;
 
    /* Total nodes in the list */
    private $count;
 
 
 
    /* List constructor */
    function __construct()
    {
        $this->firstNode = NULL;
        $this->lastNode = NULL;
        $this->count = 0;
    }
 
    public function isEmpty()
    {
        return ($this->firstNode == NULL);
    }
 
    public function insertFirst($data)
    {
        $link = new ListNode($data);
        $link->next = $this->firstNode;
        $this->firstNode = &$link;
 
        /* If this is the first node inserted in the list
           then set the lastNode pointer to it.
        */
        if($this->lastNode == NULL)
            $this->lastNode = &$link;
 
        $this->count++;
    }
 
    public function insertLast($data)
    {
        if($this->firstNode != NULL)
        {
            $link = new ListNode($data);
            $this->lastNode->next = $link;
            $link->next = NULL;
            $this->lastNode = &$link;
            $this->count++;
        }
        else
        {
            $this->insertFirst($data);
        }
    }
 
    public function deleteFirstNode()
    {
        $temp = $this->firstNode;
        $this->firstNode = $this->firstNode->next;
        if($this->firstNode != NULL)
            $this->count--;
 
        return $temp;
    }
 
    public function deleteLastNode()
    {
        if($this->firstNode != NULL)
        {
            if($this->firstNode->next == NULL)
            {
                $this->firstNode = NULL;
                $this->count--;
            }
            else
            {
                $previousNode = $this->firstNode;
                $currentNode = $this->firstNode->next;
 
                while($currentNode->next != NULL)
                {
                    $previousNode = $currentNode;
                    $currentNode = $currentNode->next;
                }

                $previousNode->next = NULL;
                $this->lastNode = $previousNode;
                $this->count--;
            }
        }
    }
 
    public function deleteNode($key)
    {
        $current = $this->firstNode;
        $previous = $this->firstNode;
 
        while($current->data != $key)
        {
            if($current->next == NULL)
                return NULL;
            else
            {
                $previous = $current;
                $current = $current->next;
            }
        }
 
        if($current == $this->firstNode)
         {
              if($this->count == 1)
               {
                  $this->lastNode = $this->firstNode;
               }
               $this->firstNode = $this->firstNode->next;
        }
        else
        {
            if($this->lastNode == $current)
            {
                 $this->lastNode = $previous;
             }
            $previous->next = $current->next;
        }
        $this->count--;  
    }
 
    public function find($key)
    {
        $current = $this->firstNode;
        while($current->data != $key)
        {
            if($current->next == NULL)
                return null;
            else
                $current = $current->next;
        }
        return $current;
    }
 
    public function readNode($nodePos)
    {
        if($nodePos <= $this->count)
        {
            $current = $this->firstNode;
            $pos = 1;
            while($pos != $nodePos)
            {
                if($current->next == NULL)
                    return null;
                else
                    $current = $current->next;
 
                $pos++;
            }
            return $current->data;
        }
        else
            return NULL;
    }
 
    public function totalNodes()
    {
        return $this->count;
    }
 
    public function readList()
    {
        $listData = array();
        $current = $this->firstNode;
 
        while($current != NULL)
        {
            array_push($listData, $current->readNode());
            $current = $current->next;
        }
        return $listData;
    }
 
    public function reverseList()
    {
        if($this->firstNode != NULL)
        {
            if($this->firstNode->next != NULL)
            {
                $current = $this->firstNode;
                $new = NULL;
 
                while ($current != NULL)
                {
                    $temp = $current->next;
                    $current->next = $new;
                    $new = $current;
                    $current = $temp;
                }
                $this->firstNode = $new;
            }
        }
    }
}

class TwitterApi{

	//Private properties, so only this class can use and access them
	private $baseUrl = 'https://api.twitter.com/';

	private $consumerKey = 'vbGapbgB6J4wlfkBlY6Yag8EI';
	private $consumerSecret = 'wh8VbUDmu8ivrYCYX6MCybjQjah99RGyLUhlUSy3qMDnn1SL1Q';

	private $bearerToken;

	private $error = false;
	private $errMessage = '';

	private $searchResponse;

	//Function to get the Token for all calls API do
	private function getBearerToken(){

		$concat = $this->consumerKey.':'.$this->consumerSecret;

		$base64 = base64_encode($concat);	

		$curl = \curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $this->baseUrl."oauth2/token",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"grant_type\"\r\n\r\nclient_credentials\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
		  CURLOPT_HTTPHEADER => array(
		    "authorization: Basic ".$base64,
		    "cache-control: no-cache",
		    "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  $this->errMessage = "Auth Error, please check that! ".$err;
		  $this->error = true;
		} else {
		  $this->bearerToken = json_decode($response)->access_token;
		  $this->error = false;
		}
	}

	//Principal function, generates authorization token, get tweets from search API and return JSON with all Response
	public function call($string){

		$teste = $this->getBearerToken();

		if(!$this->error){
			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => $this->baseUrl."1.1/search/tweets.json?q=".$string."&count=10",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "authorization: Bearer ".$this->bearerToken,
			    "cache-control: no-cache"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  $this->errMessage = "Auth Error, please check that! ".$err;
			  $this->error = true;
			} else {
			  $this->searchResponse = json_decode($response, true);
			  $this->error = false;
			}

			if($this->error){
				return $this->errMessage;
			} else {
				return json_encode($this->searchResponse);
			}
		} else {
			return $this->errMessage;
		}
	}
}

class GameOfStones{

	private $testCases;

	private $messageResult;

	public function __construct($integer){
		$this->testCases = $integer;
	}

	public function loadResults(){
		/*
		This game is that simple, the only way player 2 wins is when the number of stones are equal 1 or a multiple of 7. Just do a board test from 1 to 22 stones and you'll see it. So, i just need to check if the number of stones in game are equal 1 or multiple of 7. :D
		*/
		for ($i=1; $i <= $this->testCases ; $i++) { 
			if($i >= 7)
            	$n = $i % 7;
        	else
    			$n = $i;

	        if($n == 0 || $i == 1)
	            $this->messageResult[$i] = "Second";
	        else
	            $this->messageResult[$i] = "First";
		}

		return $this->messageResult;
	}
}
?>