<?php


namespace App\Services\Facebook;

use Facebook\Facebook;

class FacebookInsight {
	private $fb;
	private $since;
	private $until;
	private $metric = 'page_fans';
	private $pageId;

	public function __construct(Facebook $fb, $pageId){
		$this->fb = $fb;
		$this->pageId = $pageId;
	}

	public function fields($fields){
		
	}

	public function since($since){
		if(is_null($since)){
			$this->since = $since;
			return $this;
		}
        $since = date('Y-m-d', strtotime($since . '-1 days'));
		$this->since = $since;
		return $this;
	}

	public function until($until){
		if(is_null($until)){
			$this->until = $until;
			return $this;
		}
		$until = date('Y-m-d', strtotime($until . '+1 days'));
		$this->until = $until;
		return $this;
	}

	public function metric($metric){
		$this->metric = $metric;
		return $this;
	}

	public function checkDate(){
		if(is_null($this->since) && is_null($this->until)) {
		    $this->until = date('Y-m-d', strtotime('+1 day'));
            $this->since = date('Y-m-d', strtotime('-7 days'));
        }
        else if(is_null($this->since) && !is_null($this->until)) {
            $date = \DateTime::createFromFormat('Y-m-d', $this->until);
            $date->modify('-8 day');
            $this->since = $date->format('Y-m-d');
        }
        else if(!is_null($this->since) && is_null($this->until)){
        	$this->until = date('Y-m-d', strtotime('+1 day'));
        } 

	}

	public function get(){
		$this->checkDate();

		try {
            $graphEdge = $this->fb->get("/{$this->pageId}/insights?metric={$this->metric}&since={$this->since}&until={$this->until}")->getGraphEdge();
        } catch (FacebookSDKException $e) {
            echo $e->getMessage();
        }

        return $graphEdge->asArray();
	}
}