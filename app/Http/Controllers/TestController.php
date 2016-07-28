<?php

namespace App\Http\Controllers;

use App\Salon\Services\QiNiuService;
use App\Events\ConsumerLoginEvent;
use App\Salon\Consumer;
use Illuminate\Http\Request;
use App\Salon\Supplier;
use App\Salon\Address;
use App\Libary\Util\Geohash;
use App\Events\PushInfoEvent;
use App\Events\ConsumerCouponEvent;
use App\Salon\ConsumerCoupon;
use Cache;
use App\Salon\Services\ProductService;
use App\Events\ClickHeartEvent;
use App\Salon\AvailReview;
use App\Events\FreeOrderEvent;
use App\Salon\OrderInfo;
use App\Events\SupplierBalanceChangeEvent;
use App\Salon\Services\WxpayService;
use App\Salon\Services\PayNotifyService;
use App\Libary\Util\Location;
use App\Salon\Review;
use App\Salon\BarberProduct;
use App\Events\MarkUserfulAddScoreEvent;
use App\Libary\Rate\User;
use App\Libary\Rate\Item;
use App\Events\OrderPushEvent;
use App\Events\UserConsumeEvent;
use App\Salon\OrderProduct;
use Jenssegers\Mongodb\Connection;
use DB;
use MongoClient;
use Symfony\Component\Debug\header;
use App\Libary\Location\NearbySeller;
use App\Salon\Repositories\SupplierRepository;
use App\Salon\Barber;
use App\Salon\BarberSample;
use App\Salon\SupplierCache;
use App\Salon\BarberCache;
use App\Salon\Services\CouponService;
use App\Salon\Repositories\CouponRepository;
use App\Salon\Coupon;
use App\Salon\Repositories\ConsumerCouponRepository;
use App\Salon\Repositories\V2\BarberProductRepository;
use App\Salon\Services\AppService;
use App\Salon\Repositories\V2\BannerRepository;
use App\Libary\Util\String;

class TestController extends Controller
{
    public function test(Request $request)
    {
        /* $srcLongLat = '30.554526,104.075151';
        $destLongLat = '30.648312,104.100652'; */
        /* $srcLongLat = '104.075131,30.554527';
        $destLongLat = '104.07172,30.574907';
        echo Location::getP2PDistance($srcLongLat, $destLongLat); */
        /* $value = '85109911';
        echo bcrypt($value); */
        /* $mongodb = DB::connection('mongodb');
        $address = $mongodb->collection('address'); */
        //$data = $mongodb->collection('users')->where('username', 'user13')->first();
        //$mongodb = new MongoClient('mongodb://192.168.2.4');

        // 查询的弧度距离
        //$where = ['coordinate' => ['$geoWithin' => ['$centerSphere' => [[104.074476, 30.554680], 100/6371]]]];
        
        // 搜索全部数据 按照由近到远排序
        /* $page = $request->input('page');
        $pre_page = 10; */
        // 该方法，必须是2d索引，
        //$where1 = ['loc' => ['$near' => [104.074476, 30.554680], '$maxDistance' => 2]];
        // 获取所有门店，由近到远
        //$where1 = ['loc' => ['$nearSphere' => [104.074476, 30.554680]]];
        // 精确获取多少米范围内的门店信息
        /* $where1 = ['loc' => ['$nearSphere' => ['$geometry' => ['type' => 'Point', 'coordinates' => [104.074476, 30.554680]], '$maxDistance' => 5000]]];
        $data1 = $address->whereRaw($where1)->skip(($page-1) * $pre_page)->take($pre_page)->get();
        
        foreach ($data1 as $key => $val) {
            $srcLongLat = '104.074476,30.554680';
            $destLongLat = $val['loc']['longitude'] . ',' . $val['loc']['latitude'];
            $data1[$key]['dis'] = Location::getP2PDistance($srcLongLat, $destLongLat);
        }
        var_dump($data1); */
        
        // 获取半径是3km范围内的所有门店
        /* $where2 = ['loc' => ['$geoWithin' => ['$centerSphere' => [[104.074476,30.554680], 3/6371]]]];
        $data2 = $address->whereRaw($where2)->skip(($page-1) * $pre_page)->take($pre_page)->get();
        foreach ($data2 as $key => $val) {
            $srcLongLat = '104.074476,30.554680';
            $destLongLat = $val['loc']['longitude'] . ',' . $val['loc']['latitude'];
            $data2[$key]['dis'] = Location::getP2PDistance($srcLongLat, $destLongLat);
        }
        var_dump($data2); */
        
        // 更新数据
        /* $data = [
                'coordinate' => ['longitude' => 104.074476, 'latitude' => 30.554679],
                'title' => 'More Fun Studio',
                'address' => '希顿国际-c座',
        ];
        $ret = $address->where('_id', 1)->update($data);
        var_dump($ret); */
        
        // 向mongodb中插入数据
        //{ "_id" : 4, "coordinate" : { "longitude" : 104.07172, "latitude" : 30.574907 }, "title" : "冬日暖锅", "address" : "xinhanglu631" }
        /* $values = [
                '_id' => 11,
                'coordinate' => [
                        'longitude' => 104.284332,
                        'latitude' => 30.565742,
                ],
                'title' => '阿杰发艺.龙泉2店',
                'address' => '阿杰发艺(龙泉二店)',
        ];
        $address->insert($values); */
    }
    
    /**
     * 向mongodb中插入数据
     * 
     */
    public function toMongodb(Request $request)
    {
        //echo bcrypt('bns1104all');
        // 获取mongodb的链接
        /* $mongodb = DB::connection('mongodb');
        $address = $mongodb->collection('barber');
        
        $all = Barber::get();
        foreach ($all as $key => $val) {
            $values = [
                    '_id' => $val->id,
                    'loc' => [
                            'longitude' => (float)$val->longitude,
                            'latitude' => (float)$val->latitude,
                    ],
                    'province' => $val->province,
                    'city' => $val->city,
                    'district' => $val->district,
                    'detail' => $val->detail,
            ];
            
            $address->insert($values);
        } */
        
        $near = new NearbySeller('supplier', 104.07172, 30.574907);
        $page = $request->input('page', 1);
        $perPage = 3;
        
        // 测试获取由远到近的门店信息
        $list = $near->getFromNearToFar($perPage, $page);
        
        // 测试获取多少km以内的门店信息
        //$list = $near->getRangeBySort(8, $perPage, $page);
        
        // 测试多少半径内的门店信息
        //$list = $near->getRadiusBydisorder(4, $perPage, $page);
        //var_dump($list);
        
        //$array = ['age1' => 1, 'age2' => 2, 'age3' => 3, 'age4' => 4, 'age5' => 5];
        /* $array = [1, 2, 3, 4, 5];
        var_dump($array);

        var_dump(array_remove_key($array, [1, 88])); */
    }
    
    public function longlat(Geohash $geohash)
    {
        //these test hashes were made on geohash.org
        //and test various combinations of precision
        //and range
        $tests = array(
                "ezs42" => array(42.6,-5.6),
                "mh7w" => array(-20, 50),
                "t3b9m" => array(10.1, 57.2),
                "c2b25ps" => array(49.26, -123.26),
                "80021bgm" => array(0.005, -179.567),
                "k484ht99h2" => array(-30.55555, 0.2),
                "8buh2w4pnt" => array(5.00001, -140.6),
        );
        
        foreach ($tests as $actualhash => $coords) {
            // 编码
            $computed_hash = $geohash->encode($coords[0], $coords[1]);
            echo "Encode {$coords[0]}, {$coords[1]} as $actualhash : ";
            if ($computed_hash == $actualhash) {
                echo "OK<br>";
            } else {
                echo "FAIL (got $computed_hash)<br>";
            }
            echo "<hr>";
            
            // 解码
            $computed_coords = $geohash->decode($actualhash);
            echo "Decode $actualhash as {$coords[0]}, {$coords[1]} : ";
            if (($computed_coords[0] == $coords[0]) && ($computed_coords[1] == $coords[1])) {
        		echo "OK<br>";
        	} else {
        		echo "FAIL (got {$computed_coords[0]}, {$computed_coords[1]})<br>";
        	}
        	echo "<hr>";
        }
    }
    
    // 百度消息推送，测试
    public function push(ProductService $ser)
    {
        // 消息内容.
        $message = array (
                // 消息的标题.
                'title' => 'Hi!.',
                // 消息内容
                'description' => "hello!, this message from baidu push service."
        );
        $test_channel_id = '4468970955403134004';
        //event(new PushInfoEvent($message, $test_channel_id));
        $orderInfo = OrderInfo::where('id', 1)->first();
        event(new OrderPushEvent($orderInfo));
        /* $coupon = new ConsumerCoupon();
        $coupon->consumer_id = 1;
        $coupon->face_fee = 2000;
        event(new ConsumerCouponEvent($coupon));
        echo 1; */
        /* $id = 1;
        $ret = Cache::put('consumer'.$id, ['token'=>'haha', 'mobile'=>'123456'], config('appinit.exexpire'));
        $tmp = Cache::pull('consumer'.$id);
        var_dump($tmp); */
        
        /* $obj = $ser->getLowerPriceModel(1, 10);
        foreach ($obj as $v) {
            echo $v->get('id');
            echo '<br />';
        } */
        /* $avail = new AvailReview();
        $avail->consumer_id = 1;
        $avail->review_id = 1;
        event(new ClickHeartEvent($avail)); */
        /* $c = ConsumerCoupon::find(1);
        var_dump($c->toArray());
        $c->status = 1;
        echo $c->save(); */
    }
    
    // 事件测试
    public function event()
    {
        /* event(new FreeOrderEvent(5));
        echo 1; */
        
        // 测试关联更新
        /* $model = OrderInfo::find(6);
        $model->pay_status = 1;
        $model->orderProducts->product_status = 3;
        echo $model->push(); */
        
        // 测试更新余额事件
        //event(new SupplierBalanceChangeEvent(5, 995));
        
        // 测试统计消费者
        /* $orderProduct = OrderProduct::where('id', 10)->first();
        event(new UserConsumeEvent($orderProduct)); */
        
        $samples = BarberSample::get();
        
        foreach ($samples as $sample) {
            $sample->opus_img = serialize($sample->opus_img);
            $sample->save();
        }
    }
    
    // 微信支付
    public function wxpay(WxpayService $wx)
    {
        /* $param['body'] = 'APP测试支付下单';
        $param['detail'] = 'APP测试支付下单';
        $param['attach'] = 'IOS';
        $param['trade_no'] = 'BNS201508071326299801431';
        $param['total_fee'] = 995;
        $xml = $wx->unifiedorder($param);
        
        echo $xml; */
        //var_dump($xml);
        
        // 接受通知
        //$data = $GLOBALS['HTTP_RAW_POST_DATA'];
        
        $xml = <<<'EOT'
    <xml>
    <appid><![CDATA[wx2421b1c4370ec43b]]></appid>
    <attach><![CDATA[IOS]]></attach>
    	<bank_type><![CDATA[CFT]]></bank_type>
    	<fee_type><![CDATA[CNY]]></fee_type>
    	<is_subscribe><![CDATA[Y]]></is_subscribe>
    	<mch_id><![CDATA[10000100]]></mch_id>
    	<nonce_str><![CDATA[5d2b6c2a8db53831f7eda20af46e531c]]></nonce_str>
    	<openid><![CDATA[oUpF8uMEb4qRXf22hE3X68TekukE]]></openid>
    	<out_trade_no><![CDATA[BNS201508071330065146276]]></out_trade_no>
    	<result_code><![CDATA[SUCCESS]]></result_code>
    	<return_code><![CDATA[SUCCESS]]></return_code>
    	<sign><![CDATA[D2CE443255EDCDC1CD6715B4BE563D4B]]></sign>
    	<sub_mch_id><![CDATA[10000100]]></sub_mch_id>
    	<time_end><![CDATA[20140903131540]]></time_end>
    	<total_fee>1</total_fee>
    	<trade_type><![CDATA[JSAPI]]></trade_type>
    	<transaction_id><![CDATA[1004400740201409030005092168]]></transaction_id>
    </xml>
EOT;
        /* $temp_date = '20141030133525';
        echo date('Y-m-d H:i:s',strtotime($temp_date)); */
        /* $GLOBALS['HTTP_RAW_POST_DATA'] = $xml;
        $wx->Handle(false); */
        echo strtotime('2015-08-06 11:00:00');
        echo '<br />';
        echo strtotime('2015-08-15 18:00:00');
    }
    
    // 评分计算测试
    public function rateTest()
    {
        $user = new User(50);
        
        $aItem = new Item();
        // 评论环境
        $user->rate($aItem, $env, $service, $result);
    }
    
    // 格式化数据
    public function format(Request $request)
    {
        $good_tags = ['超值体验','效果很棒','发型师棒','无推销','位置好找','等待时间较短','无隐性消费','药水质量很好'];
        $type = $request->only('type');
        
        if ($type == 'supplier') {
            $caches = SupplierCache::get();
        } else {
            $caches = BarberCache::get();
        }
        
        foreach ($caches as $key1 => $cache) {
            
            if ($type == 'supplier') {
                $tags = unserialize($cache->tags);
            } else {
                $reviews = unserialize($cache->reviews);
                $tags = $reviews['tags'];
            }
            
            if (! empty($tags)) {
                $i = 0;
                $tmp = [];
                foreach ($tags as $key2 => $tag) {
                    if (! is_array($tag)) {
                        $tmp[$i]['name'] = $key2;
                        
                        if (in_array($key2, $good_tags)) {
                            $tmp[$i]['type'] = 1;
                        } else {
                            $tmp[$i]['type'] = 3;
                        }
                        $tmp[$i]['count'] = $tag;
                        
                        $i++;
                    }
                }
                
                if (! empty($tmp)) {
                    if ($type == 'supplier') {
                        $cache->tags = serialize($tmp);
                    } else {
                        $reviews['tags'] = $tmp;
                        $cache->reviews = serialize($reviews);
                    }
                    $ret = $cache->save();
                }
            }

        }
    }
    
    public function testCoupon()
    {
        $couponRe = new CouponRepository(new Coupon());
        $consumerCouponRe = new ConsumerCouponRepository(new ConsumerCoupon());
        $barberProductRe = new BarberProductRepository(new BarberProduct());
        $couponSer = new CouponService($couponRe, $consumerCouponRe, $barberProductRe);     
        $consumer_coupon_id = 26;
        $products[0] = [
                'supplier_id' => 34,
                'product_id' => 10,
                'barber_product_id' => 0
        ];
        
        $ret = $couponSer->getSignCoupon($consumer_coupon_id, $products);
        echo json_encode($ret);exit;
    }
    
    public function invitation()
    {
        $code = String::randString(6, 2);
        return $code;
    }
    
    public function seeder()
    {
        $user = new \App\Activity\User();
        
        $user->setConnection('mysql2');
        
        for ($i=1; $i < 10; $i++) {
            
            
            $user->create(['price'=>'45']);
          
        }
        
        echo 'ok';
    }
}