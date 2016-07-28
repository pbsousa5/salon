<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * 
 * 
 * @desc 数据仓库服务提供者
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月29日
 */
class RepositoryServiceProvider extends ServiceProvider
{

    public function boot()
    {

    }
    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAdderss();
        $this->registerAvailReview();
        $this->registerBackOrder();
        $this->registerBackProduct();
        $this->registerBanner();
        $this->registerBarberCache();
        $this->registerBarberProduct();
        $this->registerBarber();
        $this->registerBarberSample();
        $this->registerConsumerLog();
        $this->registerConsumerCoupon();
        $this->registerConsumer();
        $this->registerConsumerWatch();
        $this->registerCoupon();
        $this->registerDevice();
        $this->registerFeedback();
        $this->registerFundAccount();
        $this->registerFundRecord();
        $this->registerIncomeCashLog();
        $this->registerJoinApply();
        $this->registerNotify();
        $this->registerOrderAction();
        $this->registerOrderInfo();
        $this->registerOrderProduct();
        $this->registerPaymentType();
        $this->registerProductCategory();
        $this->registerProduct();
        $this->registerReview();
        $this->registerReviewTag();
        $this->registerSupplierCache();
        $this->registerSupplierManager();
        $this->registerSupplier();
        $this->registerVersionApp();
        $this->registerWithdrawCashLog();
    }
    
    /**
     * 
     * 注册地址仓库数据
     */
    protected function registerAdderss()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\AddressRepositoryInterface',
                'App\Salon\Repositories\V2\AddressRepository'
        );
    }
    
    /**
     * 记录用户是否点赞
     * 
     */
    protected function registerAvailReview()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\AvailReviewRepositoryInterface',
                'App\Salon\Repositories\V2\AvailReviewRepository'
        );
    }
    
    /**
     *
     * 注册退单仓库数据
     */
    protected function registerBackOrder()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\BackOrderRepositoryInterface',
                'App\Salon\Repositories\V2\BackOrderRepository'
        );
    }
    /**
     *
     * 注册退单中产品仓库数据
     */
    protected function registerBackProduct()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\BackProductRepositoryInterface',
                'App\Salon\Repositories\V2\BackProductRepository'
        );
    }
    
    /**
     *
     * 注册横幅仓库数据
     */
    protected function registerBanner()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\BannerRepositoryInterface',
                'App\Salon\Repositories\V2\BannerRepository'
        );
    }
    
    /**
     *
     * 注册理发师的缓存记录数据仓库
     */
    protected function registerBarberCache()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\BarberCacheRepository',
                'App\Salon\Repositories\V2\BarberCacheRepository'
        );
    }
    
    /**
     * 注册理发师的产品数据仓库
     * 
     */
    protected function registerBarberProduct()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\BarberProductRepositoryInterface',
                'App\Salon\Repositories\V2\BarberProductRepository'
        );
    }
    
    /**
     * 注册理发师数据仓库
     *
     */
    protected function registerBarber()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\BarberRepositoryInterface',
                'App\Salon\Repositories\V2\BarberRepository'
        );
    }
    
    /**
     * 注册理发师作品数据仓库
     *
     */
    protected function registerBarberSample()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\BarberBarberSampleRepositoryInterface',
                'App\Salon\Repositories\V2\BarberBarberSampleRepository'
        );
    }
    
    /**
     *
     * 注册消费记录仓库数据
     */
    protected function registerConsumerLog()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\ConsumeLogRepositoryInterface',
                'App\Salon\Repositories\V2\ConsumeLogRepository'
        );
    }
    /**
     *
     * 注册消费者优惠券仓库数据
     */
    protected function registerConsumerCoupon()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\ConsumerCouponRepositoryInterface',
                'App\Salon\Repositories\V2\ConsumerCouponRepository'
        );
    }
    /**
     *
     * 注册消费者仓库数据
     */
    protected function registerConsumer()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\ConsumerRepositoryInterface',
                'App\Salon\Repositories\V2\ConsumerRepository'
        );
    }
    
    /**
     *
     * 注册消费者关注门店仓库数据
     */
    protected function registerConsumerWatch()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\ConsumerWatchRepositoryInterface',
                'App\Salon\Repositories\V2\ConsumerWatchRepository'
        );
    }
    
    /**
     *
     * 注册优惠券仓库数据
     */
    protected function registerCoupon()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\CouponRepositoryInterface',
                'App\Salon\Repositories\V2\CouponRepository'
        );
    }
    /**
     *
     * 注册设备仓库数据
     */
    protected function registerDevice()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\DeviceRepositoryInterface',
                'App\Salon\Repositories\V2\DeviceRepository'
        );
    }
    /**
     *
     * 注册反馈仓库数据
     */
    protected function registerFeedback()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\FeedbackRepositoryInterface',
                'App\Salon\Repositories\V2\FeedbackRepository'
        );
    }
    /**
     *
     * 注册资金账户仓库数据
     */
    protected function registerFundAccount()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\FundAccountRepositoryInterface',
                'App\Salon\Repositories\V2\FundAccountRepository'
        );
    }
    
    /**
     *
     * 注册资金记录仓库数据
     */
    protected function registerFundRecord()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\FundRecordRepositoryInterface',
                'App\Salon\Repositories\V2\FundRecordRepository'
        );
    }
    
    /**
     *
     * 注册收入现金记录仓库数据仓库
     */
    protected function registerIncomeCashLog()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\IncomeCashLogRepositoryInterface',
                'App\Salon\Repositories\V2\IncomeCashLogRepository'
        );
    }
    
    /**
     *
     * 注册申请加入数据仓库
     */
    protected function registerJoinApply()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\JoinApplyRepositoryInterface',
                'App\Salon\Repositories\V2\JoinApplyRepository'
        );
    }
    
    /**
     *
     * 注册通知信息仓库数据
     */
    protected function registerNotify()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\NotifyRepositoryInterface',
                'App\Salon\Repositories\V2\NotifyRepository'
        );
    }
    /**
     *
     * 注册订单活动仓库数据
     */
    protected function registerOrderAction()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\OrderActionRepositoryInterface',
                'App\Salon\Repositories\V2\OrderActionRepository'
        );
    }
    /**
     *
     * 注册订单信息仓库数据
     */
    protected function registerOrderInfo()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\OrderInfoRepositoryInterface',
                'App\Salon\Repositories\V2\OrderInfoRepository'
        );
    }
    /**
     *
     * 注册订单产品仓库数据
     */
    protected function registerOrderProduct()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\OrderProductRepositoryInterface',
                'App\Salon\Repositories\V2\OrderProductRepository'
        );
    }
    /**
     *
     * 注册支付类型仓库数据
     */
    protected function registerPaymentType()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\PaymentTypeRepositoryInterface',
                'App\Salon\Repositories\V2\PaymentTypeRepository'
        );
    }
    /**
     *
     * 注册产品分类仓库数据
     */
    protected function registerProductCategory()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\ProductCategoryRepositoryInterface',
                'App\Salon\Repositories\V2\ProductCategoryRepository'
        );
    }
    /**
     *
     * 注册产品仓库数据
     */
    protected function registerProduct()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\ProductRepositoryInterface',
                'App\Salon\Repositories\V2\ProductRepository'
        );
    }
    /**
     *
     * 注册评论仓库数据
     */
    protected function registerReview()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\ReviewRepositoryInterface',
                'App\Salon\Repositories\V2\ReviewRepository'
        );
    }
    /**
     *
     * 注册评论标签仓库数据
     */
    protected function registerReviewTag()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\ReviewTagRepositoryInterface',
                'App\Salon\Repositories\V2\ReviewTagRepository'
        );
    }
    /**
     *
     * 注册门店缓存仓库数据
     */
    protected function registerSupplierCache()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\SupplierCacheRepositoryInterface',
                'App\Salon\Repositories\V2\SupplierCacheRepository'
        );
    }
    /**
     *
     * 注册门店管理者仓库数据
     */
    protected function registerSupplierManager()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\SupplierManagerRepositoryInterface',
                'App\Salon\Repositories\V2\SupplierManagerRepository'
        );
    }
    /**
     *
     * 注册门店仓库数据
     */
    protected function registerSupplier()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\SupplierRepositoryInterface',
                'App\Salon\Repositories\V2\SupplierRepository'
        );
    }
    /**
     *
     * 注册app版本信息仓库数据
     */
    protected function registerVersionApp()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\VersionAppRepositoryInterface',
                'App\Salon\Repositories\V2\VersionAppRepository'
        );
    }
    /**
     *
     * 注册提现记录仓库数据
     */
    protected function registerWithdrawCashLog()
    {
        $this->app->bind(
                'App\Salon\Contracts\Repositories\WithdrawCashLogRepositoryInterface',
                'App\Salon\Repositories\V2\WithdrawCashLogRepository'
        );
    }
}