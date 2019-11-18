<?php
namespace Tsum\TrainBrain\Controller\Widget;

use Magento\Framework\App\Action\Context;
class Father
{
    private $msg;

    public function __construct($text)
    {
        $this->msg = $text;
    }

     public static function getMsg()
    {
        return  '!__Y';
    }

    public function __toString()
    {
//        $origin = [1,2,3,5];
//        $insert = 4;
//        $position = 3;
//        array_splice($origin, $position, 0, $insert);
//        var_dump($origin);

        $html = <<<HTML
<!DOCTYPE html>
<html>
<body>

<h2>1.An Unordered HTML List</h2>

<ul>
  <li>Coffee</li>
  <li>Tea</li>
  <li>Milk</li>
</ul>  

<h2>2.An Ordered HTML List</h2>

<ol>
  <li>Coffee</li>
  <li>Tea</li>
  <li>Milk</li>
</ol> 

</body>
</html>
HTML;

        $xml = new \SimpleXMLElement($html);

        /* Search for <a><b><c> */
        $result = $xml->xpath('/html/body/h2');
        var_dump($result);
        return substr(sha1(rand()),0,15);
//        return $result;
    }
}

class Child extends Father
{
    public static function getMsg()
    {
        return '!!!';
    }
}

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;


    public function __construct(
        Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;

        parent::__construct($context);
    }

    public function execute()
    {
//        try {
//            $test = new Child('Gyewgrjewgtjhegrwhj');
//            echo "Father::getMsg()  $test";
//        } catch (\Exception $e) {
//            echo $e->getMessage();
//        }

$url = 'https://api.instagram.com/v1/users/self/media/recent/?access_token=576610754.b298015.a3f533d1c69c48efaf44d15d40ddf39c&count=20';
$cURL = curl_init();
curl_setopt(
    $cURL,
    CURLOPT_HTTPHEADER,
    array(
        'Content-Type: application/json',
        'Accept: application/json'
    )
);
curl_setopt_array($cURL, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPGET => true
));
$result = curl_exec($cURL);
$data = json_decode($result, true);
$data = $data['data'];
//var_dump($data);

        echo "<div class='flexslider carousel'>
  <ul class='slides'>";

foreach($data as $image){
    $url = $image['images']["low_resolution"]["url"];
    $fullurl = $image['images']["standard_resolution"]["url"];
    $caption = $image['caption']["text"];

    echo "<li><a href='$fullurl' 
			class='fancybox' 
			title='$caption'
			rel='group1'>";
    echo "<img width='320px' height='320px' class='img-fluid' src='$url'/>";
    echo "</a></li>";
}

echo "</ul></div>";


return $this->resultPageFactory->create();
    }
}
