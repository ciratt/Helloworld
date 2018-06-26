<?php
namespace Ciratt\Helloworld\Observer;

use Magento\Framework\Event\ObserverInterface;

class MyObserver implements ObserverInterface
{
    /**
     * Logging instance
     * @var \Ciratt\Helloworld\Logger\Logger
     */
    protected $_logger;
    
  public function __construct(\Ciratt\Helloworld\Logger\Logger $logger)
  {
      $this->_logger = $logger;
      
  }

  public function execute(\Magento\Framework\Event\Observer $observer)
  {
      $quote_item = $observer->getEvent()->getData('quote_item');
      
      //$string_item = implode(',', array_keys($quote_item) );
      if ($quote_item) {
        $this->_logger->info('prod id '.$quote_item->getProductId());
          
      }
      
  }
}