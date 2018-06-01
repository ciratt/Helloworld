<?php

namespace Ciratt\Helloworld\Block;

class Indexinfo extends \Magento\Framework\View\Element\Template {

    /**
     * Returns action url for contact form
     *
     * @return string
     */
    public function getFormAction()
    {
        return $this->getUrl('contact/index/post', ['_secure' => true]);
    }
}
