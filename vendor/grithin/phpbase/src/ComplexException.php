<?
namespace Grithin;

/* About
Exception intended to accept an array as error details
*/


class ComplexException extends \Exception{
	public $details = [];
	public function __construct($details = null, $code = 0, \Exception $previous = null){
		# the message is extracted from the details, or it is a representation of the details
		$message = null;
		if(!Tool::is_scalar($details)){
			if(is_array($details)){
				if($details['message']){
					$message = $details['message'];
				}
			}else{
				if($details->message){
					$message = $details->message;
				}
			}
			if(!$message){
				$message = Tool::flat_json_encode(Arrays::from($details));
			}
			$this->details = $details;
		}else{
			$message = $details;
			$this->details = ['message'=>$message];
		}

		parent::__construct($message, $code, $previous);
	}
	public function getDetails(){
		return $this->details;
	}
	# alias
	public function get_details(){
		return call_user_func_array([$this,'getDetails'], func_get_args());
	}
	# getDetails on either complex exception or regular
	static function details_extract($e){
		if(method_exists($e, 'getDetails')){
			return  $e->getDetails();
		}else{
			return ['message'=>$e->getMessage()];
		}
	}
}
