<?php
namespace App\Salon\Services\V2;

use App\Salon\Services\ProductService as ProductSer;
use App\Salon\Repositories\V2\ProductRepository;
use App\Salon\Repositories\V2\BarberProductRepository;
use App\Salon\Repositories\V2\ProductCategoryRepository;

/**
 *
 *
 * @desc 产品服务层
 * @author helei <helei@bnersoft.com>
 * @date 2015年11月10日
 */
class ProductService extends ProductSer
{
    /**
     * 产品数据仓库
     * @var ProductRepository
     */
    protected $productRe;
    
    /**
     * The BarberProductRepository instance.
     * @var BarberProductRepository
     */
    protected $barberProductRe;
    
    /**
     * The ProductCategoryRepository instance.
     * @var ProductCategoryRepository
     */
    protected $categoryRe;
    
    /**
     * The OrderService instance.
     * @var OrderService
     */
    protected $orderSer;
    
    public function __construct(
            ProductRepository $productRe,
            BarberProductRepository $barberProductRe,
            ProductCategoryRepository $categoryRe,
            OrderService $orderSer
    ){
        parent::__construct($productRe, $barberProductRe, $categoryRe, $orderSer);
    }
    
    /**
     * 获取门店或理发师产品列表
     * 
     * @param integer $user_id 根据门店id获取产品列表
     * @param string $user_type 获取的用户类型，supplier barber
     * @param integer $category_id 分类的id,默认不传
     * @param integer $req_type 请求类型，仅在用户类型为barber时有效 1:获取自身拥有的项目，2：获取所有项目
     * 
     * @return Illuminate\Support\Collection
     */
    public function listProduct($user_id, $user_type, $category_id = 0, $req_type = 1)
    {
        $where['status'] = [0, 1];
        $where['is_delete'] = 0;
        if ($category_id !== 0) {
            $where['category_id'] = (int)$category_id;
        }
        
        switch ($user_type) {
            case 'supplier':
                $where['supplier_id'] = $user_id;
                $list = $this->listSupplierProduct($where);
                break;
            case 'barber':
                $where['barber_id'] = $user_id;
                $list = $this->listBarberProduct($where, $req_type);
                break;
            default:
                $list = null;
                break;
        }
        
        if (is_null($list) || $list->isEmpty()) {
            return $list;
        }
        
        foreach ($list as $key => $product) {
            $this->handleData($product, $user_type);
        }
        
        return $list;
    }
    
    /**
     * 获取门店项目列表
     *
     * @param array $where 查询条件
     *
     * @return Illuminate\Support\Collection
     */
    protected function listSupplierProduct($where)
    {
        $list = $this->productRe->index($where, ['category_id'=>'asc']);
        return $list;
    }
    
    /**
     * 获取理发师产品列表
     *
     *  @param array $where 查询条件
     *  @param integer $req_type 请求类型， 1:获取自身拥有的项目，2：获取所有项目
     *
     * @return Illuminate\Support\Collection
     */
    protected function listBarberProduct($where, $req_type = 1)
    {
        $barberProducts = $this->barberProductRe->index($where, ['category_id'=>'asc']);
        if ($barberProducts->isEmpty() || (int)$req_type == 1) {
            return $barberProducts;
        }
        
        $where = array_remove_keys($where, ['barber_id']);
        $where['supplier_id'] = $barberProducts[0]->supplier_id;
        
        $supplierProducts = $this->listSupplierProduct($where);
        
        foreach ($supplierProducts as $sProduct) {
            foreach ($barberProducts as $bProduct) {
                if ($bProduct->product_id == $sProduct->id) {
                    $sProduct->hasProduct = 1;
                    break;
                } else {
                    $sProduct->hasProduct = 0;
                }
            }
        }
        
        return $supplierProducts;
    }
}