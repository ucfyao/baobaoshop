<?php
/**
 *		子商品数据层
 *      [HeYi] (C)2013-2099 HeYi Science and technology Yzh.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.yaozihao.cn
 *      tel:18519188969
 */
class goods_sku_table extends table {
    protected $result = array();
	protected $_validate = array(
       
    );
    protected $_auto = array(
        array('up_time','time',1,'function'),
        array('update_time','time',2,'function')
    );
    public function detail($id,$field = TRUE,$goods = '',$detail = true){

        $result = array();
        
        if((int)$id > 0){
            $result['sku'] = $this->field($field)->find($id);
            if(empty($result['sku'])) return $this;
            if($result['sku']['imgs']){
                $result['sku']['img_list'] = json_decode($result['sku']['imgs'],TRUE);
            }
            $result['sku']['thumb'] = $result['sku']['thumb'] ? $result['sku']['thumb'] : $this->load->table('goods/goods_spu')->where(array('id'=>$result['sku']['spu_id']))->getField('thumb');
            if($goods == 'goods'){
                $result['goods'] = $this->load->table('goods/goods_spu')->find($result['sku']['spu_id']);
                if(empty($result['sku']['imgs'])){
                    $result['sku']['img_list'] = json_decode($result['goods']['imgs'],TRUE);
                }
                unset($result['goods']['thumb']);
                unset($result['goods']['sn']);
                if(!$result['sku']['subtitle']) unset($result['sku']['subtitle'],$result['sku']['style']); 
                if(!$result['sku']['warn_number']) unset($result['sku']['warn_number']);
                if(!$result['sku']['keyword']) unset($result['sku']['keyword']);  
                if(!$result['sku']['description']) unset($result['sku']['description']);
                if(!$result['sku']['content']) unset($result['sku']['content']);    
                $result['sku'] = array_merge($result['goods'],$result['sku']);
                $prom_price = 0;
                if ($result['sku']['prom_type'] == 'time' && $result['sku']['prom_id'] > 0 ) {
                    $pro_map = array();
                    $pro_map['id'] = $result['sku']['prom_id'];
                    $pro_map['start_time'] = array("LT", time());
                    $pro_map['end_time'] = array("GT", time());
                    $promotion = $this->load->table('promotion/promotion_time')->where($pro_map)->find();
                    if ($promotion) {
                        $sku_prom = json_decode($promotion['sku_info'],TRUE);
                        $prom_price = sprintf("%.2f", $sku_prom[$result['sku']['sku_id']]);
                        $result['sku']['prom_time'] = $promotion['end_time'] - time();
                    }else{
                        $prom_price = sprintf("%.2f", $result['sku']['shop_price']);
                    }
                }else{
                    $member = $this->load->service('member/member')->init();
                    if (!$member['id']) {
                        $prom_price = sprintf("%.2f", $result['sku']['shop_price']);
                    } else {
                        $discount = (!$member['_group']['discount']) ? 100 : $member['_group']['discount'] ;
                        $prom_price = sprintf("%.2f", $result['sku']['shop_price']/100*$discount);
                    }
                }
                $result['sku']['url'] = url('goods/index/detail',array('sku_id' => $id));
                $result['sku']['prom_price'] = $prom_price;
                $result['sku']['brand'] = model('goods/brand')->where(array('id'=>$result['goods']['brand_id']))->find();
                if($detail){
                    $result['sku']['cat_name'] = model('goods/goods_category')->where(array('id'=>$result['goods']['catid']))->getField('name');
                }
           }
        }
        hd_hook::listen('goods_sku_detail', $result);
        $this->result = $result;
        return $this;
    }
    public function create_spec(){
         if (!empty($this->result['sku']['spec'])) {
            $this->result['sku']['spec'] = json_decode($this->result['sku']['spec'],TRUE);
        }
        return $this;
    }
    public function show_index(){
        $this->result['index'] = $this->load->table('goods/goods_index')->find($this->result['sku']['sku_id']);
        $sales = $this->load->table('goods/goods_index')->where(array('spu_id'=>$this->result['sku']['spu_id']))->Field('sales')->select();
        $sales_total = 0;
        foreach ($sales as $num) {
            $sales_total += $num['sales'];
        }
        $this->result['sku']['sales'] = $sales_total;
        $this->result['sku']['hits'] = $this->result['index']['hits'];
        $this->result['sku']['favorites'] = $this->result['index']['favorites'];
        return $this;
    }
    public function output(){
        return $this->result['sku'];
    }
}