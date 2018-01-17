<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Production extends Fzhao_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('admin/system_model');
    }
    
    /**
     * products
     * 简介：产品管理
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/4
     */
    function products() {
        if (IS_POST) {
            $data['currPage'] = $this->input->post('currPage', true);
            $data['rows'] = $this->input->post('rows', true);
            $data['condition'] = $this->input->post('condition', true);
            if (empty($data['condition']) || !is_array($data['condition'])) {
                unset($data['condition']);
            } else {
                $data['condition'] = $data['condition'][0];
            }
            $result = $this->system_model->getProductsList($data);
            $this->doJson($result);
        } else {
            $data['product_term'] = $this->system_model->getTermByTaxonomy('products');
            $this->view('admin/products', $data);
        }
    }

    /**
     * addProducts
     * 简介：添加产品
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/5
     */
    function addProducts() {
        if (IS_POST) {
            $post = $this->input->post(NULL, TRUE);
            $data = array();
            $data['term_id'] = intval($post['term_id']) ? intval($this->input->post('term_id', true)) : 0;
            $data['is_commend'] = isset($post['is_commend']) ? intval($this->input->post('is_commend', true)) : 0;
            $data['is_issue'] = isset($post['is_issue']) ? intval($this->input->post('is_issue', true)) : 0;
            $data['title'] = trim($post['title']) ? $this->input->post('title', true) : '';
            if(!trim($data['title'])){
                $this->doIframe('产品标题不能为空',0);
            }
            $result = $this->system_model->getData(array(
                'fields' => '*',
                'table' => 'products',
                '_conditions' => array(array('is_valid'=>'1'),array('title'=>trim($data['title']))),
                'row' => true
            ));
            if($result){
                $this->doIframe('产品标题已存在',0);
            }
            $data['ft_title'] = wordSegment($data['title']);
            $data['summary'] = trim($post['summary']) ? htmlspecialchars($this->input->post('summary', true)) : '';
            //$data['content']		 = str_replace(site_url(''),'LWWEB_LWWEB_DEFAULT_URL',trim($post['content'])?htmlspecialchars($this->input->post('content')):'');
            $data['content'] = serialize($_POST['contents']);
            $data['SEOKeywords'] = trim($post['SEOKeywords']) ? htmlspecialchars($this->input->post('SEOKeywords', true)) : '';
            $data['SEODescription'] = trim($post['SEODescription']) ? htmlspecialchars($this->input->post('SEODescription', true)) : '';
            //$data['video_url']		 = trim($post['video_url'])?$this->input->post('video_url',true):'';
            $data['sort'] = intval($this->input->post('sort', true));
            $data['owner'] = $this->userId;
            $data['create_time'] = date('Y-m-d H:i:s');
            $data['thumbPic'] = '';
            $tid = $data['term_id'];
            $data['lang'] = _LANGUAGE_;

            $upload = array();
            $_FILES['thumbPic1']['tmp_name'] && $upload['thumbPic1'] = $_FILES['thumbPic1'];
            $_FILES['thumbPic2']['tmp_name'] && $upload['thumbPic2'] = $_FILES['thumbPic2'];
            $_FILES['thumbPic3']['tmp_name'] && $upload['thumbPic3'] = $_FILES['thumbPic3'];
            $_FILES['thumbPic4']['tmp_name'] && $upload['thumbPic4'] = $_FILES['thumbPic4'];
            $_FILES['thumbPic5']['tmp_name'] && $upload['thumbPic5'] = $_FILES['thumbPic5'];

            if (!empty($upload)) {
                $thumbPic = '';
                for ($i = 1; $i <= 5; $i++) {
                    $imagePath = '';
                    if (isset($upload['thumbPic' . $i])) {
                        $imagePath = $this->uploadPic($upload['thumbPic' . $i], 'p', 'uploads/products/images/images');
                        $this->zoomImage($imagePath, 'products/images');
                    }
                    $thumbPic .= $imagePath . '+++' . (str_replace('###', '***', (trim($post['caption' . $i]) ? $this->input->post('caption' . $i, true) : ''))) . '###';
                }
                $data['thumbPic'] = $thumbPic;
            } else {
                $data['thumbPic'] = next(explode(site_url(''), pregpic($this->input->post('content'))));
                $data['thumbPic'] && $this->zoomImage($data['thumbPic'], 'products/images');
                $data['thumbPic'] .= '+++' . $data['title'] . '###+++###+++###+++###+++###';
            }
            $result = $this->system_model->addProducts($data);
            if ($result) {
                $this->system_model->iUpdate(array('table' => 'term', 'field' => 'count', 'val' => 'count+1', 'id' => $tid));
            }
            $this->doIframe($result);
        } else {
            $data['product_term'] = $this->system_model->getTermByTaxonomy('products');
            $this->view('admin/productsAdd', $data);
        }
    }

    function zoomImage_bak($path) {
        $imageInfo = getimagesize($path);
        $pPath = pathinfo($path);
        //生成small缩略图
        $this->load->library('image_lib');
        $img_config['create_thumb'] = TRUE;
        $img_config['maintain_ratio'] = TRUE;
        $img_config['master_dim'] = 'height';
        $img_config['source_image'] = $path;
        $img_config['new_image'] = 'uploads/products/images/' . $pPath["basename"]; //指定生成图片的路径
        $img_config['height'] = 200;
        $img_config['width'] = 200 * $imageInfo[0] / $imageInfo[1];
        $this->image_lib->initialize($img_config);
        if (!$this->image_lib->resize()) {
            $this->doIframe("生成small缩略图失败" . $img_config['new_image']);
        }
        $this->image_lib->clear();

        //生成tiny缩略图
        $img_config['create_thumb'] = TRUE;
        $img_config['source_image'] = $path;
        $img_config['maintain_ratio'] = TRUE;
        $img_config['master_dim'] = 'auto';
        $img_config['new_image'] = 'uploads/products/thumbPic/' . $pPath["basename"]; //指定生成图片的路径
        $img_config['width'] = 50;
        $img_config['height'] = 50 * $imageInfo[1] / $imageInfo[0];
        $this->image_lib->initialize($img_config);
        if (!$this->image_lib->resize()) {
            $this->doIframe("生成tiny缩略图失败" . $img_config['new_image']);
        }
        $this->image_lib->clear();
    }

    /**
     * editProducts
     * 简介：修改产品
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/6
     */
    function editProducts() {
        if (IS_POST) {
            if ($this->input->post('act', true) == 'checkLP') {
                $this->json_success('success');
                exit();
            }
            $post = $this->input->post(NULL, TRUE);
            $data = array();
            $id = intval($post['id']) ? intval($this->input->post('id', true)) : 0;
            $data['term_id'] = intval($post['term_id']) ? intval($this->input->post('term_id', true)) : 0;
            $data['is_commend'] = isset($post['is_commend']) ? intval($this->input->post('is_commend', true)) : 0;
            $data['is_issue'] = isset($post['is_issue']) ? intval($this->input->post('is_issue', true)) : 0;
            //$data['views']			 = intval($post['views'])?intval($this->input->post('views',true)):0;
            $data['title'] = trim($post['title']) ? $this->input->post('title', true) : '';
            $data['ft_title'] = wordSegment($data['title']);
            $data['summary'] = trim($post['summary']) ? htmlspecialchars($this->input->post('summary', true)) : '';
            //$data['content']		 = str_replace(site_url(''),'LWWEB_LWWEB_DEFAULT_URL',trim($post['content'])?htmlspecialchars($this->input->post('content')):'');
            $data['content'] = serialize($_POST['contents']);
            $data['SEOKeywords'] = trim($post['SEOKeywords']) ? htmlspecialchars($this->input->post('SEOKeywords', true)) : '';
            $data['SEODescription'] = trim($post['SEODescription']) ? htmlspecialchars($this->input->post('SEODescription', true)) : '';
            $data['sort'] = intval($this->input->post('sort', true));
            //$data['video_url']		 = trim($post['video_url'])?$this->input->post('video_url',true):'';
            $data['update_time'] = date('Y-m-d H:i:s');

            $upload = array();
            $_FILES['thumbPic1']['tmp_name'] && $upload['thumbPic1'] = $_FILES['thumbPic1'];
            $_FILES['thumbPic2']['tmp_name'] && $upload['thumbPic2'] = $_FILES['thumbPic2'];
            $_FILES['thumbPic3']['tmp_name'] && $upload['thumbPic3'] = $_FILES['thumbPic3'];
            $_FILES['thumbPic4']['tmp_name'] && $upload['thumbPic4'] = $_FILES['thumbPic4'];
            $_FILES['thumbPic5']['tmp_name'] && $upload['thumbPic5'] = $_FILES['thumbPic5'];

            if (!empty($upload)) {
                $thumbPic = explode('###', rtrim($post['thumbPic'], '###'));
                for ($i = 1; $i <= 5; $i++) {
                    $imagePath = '';
                    if (isset($upload['thumbPic' . $i])) {
                        $imagePath = $this->uploadPic($upload['thumbPic' . $i], 'p', 'uploads/products/images/images');
                        $imagePath && $this->zoomImage($imagePath, 'products/images');
                        if (file_exists(current(explode('+++', $thumbPic[$i - 1])))) {
                            $this->dropPic(current(explode('+++', $thumbPic[$i - 1])));
                        }
                        array_splice($thumbPic, $i - 1, 1, $imagePath . '+++' . (str_replace('###', '***', (trim($post['caption' . $i]) ? $this->input->post('caption' . $i, true) : ''))));
                    } else {
                        $thumbArr = explode('+++', $thumbPic[$i - 1]);
                        $thumbArr[1] = str_replace('###', '***', (trim($post['caption' . $i]) ? $this->input->post('caption' . $i, true) : ''));
                        array_splice($thumbPic, $i - 1, 1, implode('+++', $thumbArr));
                    }
                }
                $data['thumbPic'] = implode('###', $thumbPic);
            } else {
                if ($post['thumbPic'] == '+++###+++###+++###+++###+++###') {
                    $data['thumbPic'] = next(explode(site_url(''), pregpic($this->input->post('content'))));
                    $data['thumbPic'] && $this->zoomImage($data['thumbPic'], 'products/images');
                    $data['thumbPic'] .= '+++' . $data['title'] . '###+++###+++###+++###+++###';
                } else {
                    $thumbPic = explode('###', rtrim($post['thumbPic'], '###'));
                    for ($i = 1; $i <= 4; $i++) {
                        $thumbArr = explode('+++', $thumbPic[$i - 1]);
                        $thumbArr[1] = str_replace('###', '***', (trim($post['caption' . $i]) ? $this->input->post('caption' . $i, true) : ''));
                        array_splice($thumbPic, $i - 1, 1, implode('+++', $thumbArr));
                    }
                    $data['thumbPic'] = implode('###', $thumbPic);
                }
            }

            $result = $this->system_model->editProducts($data, $id);
            $this->doIframe($result);
        } else {
            $n = in_array($this->uri->segment(1), array('cn', 'en')) ? 5 : 4;
            $id = intval($this->uri->segment($n));
            $data['product_term'] = $this->system_model->getTermByTaxonomy('products');
            $data['product_data'] = $this->system_model->getProductById($id);
            if (isset($data['product_data']['thumbPic']) && $data['product_data']['thumbPic'] && strpos($data['product_data']['thumbPic'], '###') !== FALSE) {
                foreach (explode('###', rtrim($data['product_data']['thumbPic'], '###')) as $key => $item) {
                    $thumbPic = explode('+++', $item);
                    $thumbPic[2] = $thumbPic[0];
                    $thumbPic[0] = $this->getPImageFormat($thumbPic[0]);
                    $data['product_data']['pics'][] = $thumbPic;
                }
            }//ww($data['product_data']['pics']);
            $this->view('admin/productsEdit', $data);
        }
    }

    /**
     * getPImageFormat
     * 简介：根据产品图片路径读取tiny缩略图、small缩略图路径
     * 参数：$path
     * 返回：String
     * 作者：Fzhao
     * 时间：2013/3/26
     */
    function getPImageFormat($path, $format = 'tiny') {
        if ($path && file_exists($path)) {
            $imagePath = explode('/', $path);
            $c = count($imagePath);
            $imagePath[$c - 2] = $format;
            $fileName = explode('.', end($imagePath));
            $imagePath[$c - 1] = $fileName[0] . '_thumb.' . $fileName[1];
            return implode('/', $imagePath);
        } else {
            return false;
        }
    }

    /**
     * 删除图片
     * Fzhao
     * 2013/3/26
     */
    function dropPic() {
        $result = true;
        $id = $this->input->post('id', true);
        $index = $this->input->post('index', true);
        $path = $this->input->post('src', true);
        if (!$path || !file_exists($path)) {
            $this->doJson(false);
        }
        $small = $this->getPImageFormat($path, 'small');
        if (file_exists($small)) {
            unlink($small);
        }
        $tiny = $this->getPImageFormat($path, 'tiny');
        if (file_exists($tiny)) {
            unlink($tiny);
        }
        if (file_exists($path)) {
            unlink($path);
        }
        $this->doJson($result);
    }

    /**
     * productsRecycleList
     * 简介：产品回收站
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/6
     */
    function productsRecycleList() {
        if (IS_POST) {
            $data['currPage'] = $this->input->post('currPage', true);
            $data['rows'] = $this->input->post('rows', true);
            $data['condition'] = $this->input->post('condition', true);
            if (empty($data['condition']) || !is_array($data['condition'])) {
                unset($data['condition']);
            } else {
                $data['condition'] = $data['condition'][0];
            }
            $result = $this->system_model->getProductsRecycleList($data);
            $this->doJson($result);
        } else {
            $data['product_term'] = $this->system_model->getTermByTaxonomy('products');
            $this->view('admin/productsRecycleList', $data);
        }
    }

    /**
     * delProduct
     * 简介：删除(放入回收站)产品
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/6
     */
    function delProduct() {
        if (IS_POST) {
            $id = $this->input->post('id', true);
            if (is_numeric($id) || is_array($id)) {
                $result = $this->system_model->delProduct($id);
            } else {
                $result = false;
            }
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * dumpProducts
     * 简介：彻底删除产品信息
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-12-9
     */
    function dumpProducts() {
        if (IS_POST) {
            $id = $this->input->post('id', true);
            $result = $this->system_model->dumpProducts($id);
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * recoverProducts
     * 简介：还原产品信息
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-12-9
     */
    function recoverProducts() {
        if (IS_POST) {
            $id = $this->input->post('id', true);
            $result = $this->system_model->recoverProducts($id);
            $this->doJson($result);
        } else {
            show_404();
        }
    }

}
