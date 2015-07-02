<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Customer\Test\Unit\Model;

use Magento\Customer\Model\AccountManagement;
use Magento\Framework\App\Area;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AccountManagementTest extends \PHPUnit_Framework_TestCase
{
    /** @var AccountManagement */
    protected $accountManagement;

    /** @var ObjectManagerHelper */
    protected $objectManagerHelper;

    /** @var \Magento\Customer\Model\CustomerFactory|\PHPUnit_Framework_MockObject_MockObject */
    protected $customerFactory;

    /** @var \Magento\Framework\Event\ManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $manager;

    /** @var \Magento\Store\Model\StoreManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $storeManager;

    /** @var \Magento\Framework\Math\Random|\PHPUnit_Framework_MockObject_MockObject */
    protected $random;

    /** @var \Magento\Customer\Model\Metadata\Validator|\PHPUnit_Framework_MockObject_MockObject */
    protected $validator;

    /** @var \Magento\Customer\Api\Data\ValidationResultsInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject */
    protected $validationResultsInterfaceFactory;

    /** @var \Magento\Customer\Api\AddressRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $addressRepository;

    /** @var \Magento\Customer\Api\CustomerMetadataInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $customerMetadata;

    /** @var \Magento\Customer\Model\CustomerRegistry|\PHPUnit_Framework_MockObject_MockObject */
    protected $customerRegistry;

    /** @var \Magento\Framework\Url|\PHPUnit_Framework_MockObject_MockObject */
    protected $url;

    /** @var \Psr\Log\LoggerInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $logger;

    /** @var \Magento\Framework\Encryption\EncryptorInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $encryptor;

    /** @var \Magento\Customer\Model\Config\Share|\PHPUnit_Framework_MockObject_MockObject */
    protected $share;

    /** @var \Magento\Framework\Stdlib\String|\PHPUnit_Framework_MockObject_MockObject */
    protected $string;

    /** @var \Magento\Customer\Api\CustomerRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $customerRepository;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $scopeConfig;

    /** @var \Magento\Framework\Mail\Template\TransportBuilder|\PHPUnit_Framework_MockObject_MockObject */
    protected $transportBuilder;

    /** @var \Magento\Framework\Reflection\DataObjectProcessor|\PHPUnit_Framework_MockObject_MockObject */
    protected $dataObjectProcessor;

    /** @var \Magento\Framework\Registry|\PHPUnit_Framework_MockObject_MockObject */
    protected $registry;

    /** @var \Magento\Customer\Helper\View|\PHPUnit_Framework_MockObject_MockObject */
    protected $customerViewHelper;

    /** @var \Magento\Framework\Stdlib\DateTime|\PHPUnit_Framework_MockObject_MockObject */
    protected $dateTime;

    /** @var \Magento\Customer\Model\Customer|\PHPUnit_Framework_MockObject_MockObject */
    protected $customer;

    /** @var \Magento\Framework\ObjectFactory|\PHPUnit_Framework_MockObject_MockObject */
    protected $objectFactory;

    /** @var \Magento\Framework\Api\ExtensibleDataObjectConverter|\PHPUnit_Framework_MockObject_MockObject */
    protected $extensibleDataObjectConverter;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Store\Model\Store
     */
    protected $store;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Customer\Model\Data\CustomerSecure
     */
    protected $customerSecure;

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function setUp()
    {
        $this->customerFactory = $this->getMock('Magento\Customer\Model\CustomerFactory', [], [], '', false);
        $this->manager = $this->getMock('Magento\Framework\Event\ManagerInterface');
        $this->store = $this->getMockBuilder('Magento\Store\Model\Store')
            ->disableOriginalConstructor()
            ->getMock();
        $this->storeManager = $this->getMock('Magento\Store\Model\StoreManagerInterface');
        $this->random = $this->getMock('Magento\Framework\Math\Random');
        $this->validator = $this->getMock('Magento\Customer\Model\Metadata\Validator', [], [], '', false);
        $this->validationResultsInterfaceFactory = $this->getMock(
            'Magento\Customer\Api\Data\ValidationResultsInterfaceFactory',
            [],
            [],
            '',
            false
        );
        $this->addressRepository = $this->getMock('Magento\Customer\Api\AddressRepositoryInterface');
        $this->customerMetadata = $this->getMock('Magento\Customer\Api\CustomerMetadataInterface');
        $this->customerRegistry = $this->getMock('Magento\Customer\Model\CustomerRegistry', [], [], '', false);
        $this->url = $this->getMock('Magento\Framework\Url', [], [], '', false);
        $this->logger = $this->getMock('Psr\Log\LoggerInterface');
        $this->encryptor = $this->getMock('Magento\Framework\Encryption\EncryptorInterface');
        $this->share = $this->getMock('Magento\Customer\Model\Config\Share', [], [], '', false);
        $this->string = $this->getMock('Magento\Framework\Stdlib\String');
        $this->customerRepository = $this->getMock('Magento\Customer\Api\CustomerRepositoryInterface');
        $this->scopeConfig = $this->getMock('Magento\Framework\App\Config\ScopeConfigInterface');
        $this->transportBuilder = $this->getMock(
            'Magento\Framework\Mail\Template\TransportBuilder',
            [],
            [],
            '',
            false
        );
        $this->dataObjectProcessor = $this->getMock(
            'Magento\Framework\Reflection\DataObjectProcessor',
            [],
            [],
            '',
            false
        );
        $this->registry = $this->getMock('Magento\Framework\Registry');
        $this->customerViewHelper = $this->getMock('Magento\Customer\Helper\View', [], [], '', false);
        $this->dateTime = $this->getMock('Magento\Framework\Stdlib\DateTime');
        $this->customer = $this->getMock('Magento\Customer\Model\Customer', [], [], '', false);
        $this->objectFactory = $this->getMock('Magento\Framework\ObjectFactory', [], [], '', false);
        $this->extensibleDataObjectConverter = $this->getMock(
            'Magento\Framework\Api\ExtensibleDataObjectConverter',
            [],
            [],
            '',
            false
        );

        $this->customerSecure = $this->getMockBuilder('Magento\Customer\Model\Data\CustomerSecure')
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->accountManagement = $this->objectManagerHelper->getObject(
            'Magento\Customer\Model\AccountManagement',
            [
                'customerFactory' => $this->customerFactory,
                'eventManager' => $this->manager,
                'storeManager' => $this->storeManager,
                'mathRandom' => $this->random,
                'validator' => $this->validator,
                'validationResultsDataFactory' => $this->validationResultsInterfaceFactory,
                'addressRepository' => $this->addressRepository,
                'customerMetadataService' => $this->customerMetadata,
                'customerRegistry' => $this->customerRegistry,
                'url' => $this->url,
                'logger' => $this->logger,
                'encryptor' => $this->encryptor,
                'configShare' => $this->share,
                'stringHelper' => $this->string,
                'customerRepository' => $this->customerRepository,
                'scopeConfig' => $this->scopeConfig,
                'transportBuilder' => $this->transportBuilder,
                'dataProcessor' => $this->dataObjectProcessor,
                'registry' => $this->registry,
                'customerViewHelper' => $this->customerViewHelper,
                'dateTime' => $this->dateTime,
                'customerModel' => $this->customer,
                'objectFactory' => $this->objectFactory,
                'extensibleDataObjectConverter' => $this->extensibleDataObjectConverter
            ]
        );
    }

    /**
     * @expectedException \Magento\Framework\Exception\InputException
     */
    public function testCreateAccountWithPasswordHashWithExistingCustomer()
    {
        $websiteId = 1;
        $storeId = 1;
        $customerId = 1;
        $customerEmail = 'email@email.com';
        $hash = '4nj54lkj5jfi03j49f8bgujfgsd';

        $website = $this->getMockBuilder('Magento\Store\Model\Website')->disableOriginalConstructor()->getMock();
        $website->expects($this->once())
            ->method('getStoreIds')
            ->willReturn([1, 2, 3]);
        $customer = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')->getMock();
        $customer->expects($this->once())
            ->method('getId')
            ->willReturn($customerId);
        $customer->expects($this->once())
            ->method('getEmail')
            ->willReturn($customerEmail);
        $customer->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn($websiteId);
        $customer->expects($this->atLeastOnce())
            ->method('getStoreId')
            ->willReturn($storeId);
        $this->customerRepository
            ->expects($this->once())
            ->method('get')
            ->with($customerEmail)
            ->willReturn($customer);
        $this->share
            ->expects($this->once())
            ->method('isWebsiteScope')
            ->willReturn(true);
        $this->storeManager
            ->expects($this->once())
            ->method('getWebsite')
            ->with($websiteId)
            ->willReturn($website);
        $this->accountManagement->createAccountWithPasswordHash($customer, $hash);
    }

    /**
     * @expectedException \Magento\Framework\Exception\State\InputMismatchException
     */
    public function testCreateAccountWithPasswordHashWithCustomerWithoutStoreId()
    {
        $websiteId = 1;
        $storeId = null;
        $defaultStoreId = 1;
        $customerId = 1;
        $customerEmail = 'email@email.com';
        $hash = '4nj54lkj5jfi03j49f8bgujfgsd';

        $address = $this->getMockBuilder('Magento\Customer\Api\Data\AddressInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $store = $this->getMockBuilder('Magento\Store\Model\Store')->disableOriginalConstructor()->getMock();
        $store->expects($this->once())
            ->method('getId')
            ->willReturn($defaultStoreId);
        $website = $this->getMockBuilder('Magento\Store\Model\Website')->disableOriginalConstructor()->getMock();
        $website->expects($this->once())
            ->method('getStoreIds')
            ->willReturn([1, 2, 3]);
        $website->expects($this->once())
            ->method('getDefaultStore')
            ->willReturn($store);
        $customer = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')->getMock();
        $customer->expects($this->once())
            ->method('getId')
            ->willReturn($customerId);
        $customer->expects($this->once())
            ->method('getEmail')
            ->willReturn($customerEmail);
        $customer->expects($this->atLeastOnce())
            ->method('getWebsiteId')
            ->willReturn($websiteId);
        $customer->expects($this->atLeastOnce())
            ->method('getStoreId')
            ->willReturn($storeId);
        $customer->expects($this->once())
            ->method('setStoreId')
            ->with($defaultStoreId);
        $customer
            ->expects($this->once())
            ->method('getAddresses')
            ->willReturn([$address]);
        $customer
            ->expects($this->once())
            ->method('setAddresses')
            ->with(null);
        $this->customerRepository
            ->expects($this->once())
            ->method('get')
            ->with($customerEmail)
            ->willReturn($customer);
        $this->share
            ->expects($this->once())
            ->method('isWebsiteScope')
            ->willReturn(true);
        $this->storeManager
            ->expects($this->atLeastOnce())
            ->method('getWebsite')
            ->with($websiteId)
            ->willReturn($website);
        $exception = new \Magento\Framework\Exception\AlreadyExistsException(new \Magento\Framework\Phrase('Exception message'));
        $this->customerRepository
            ->expects($this->once())
            ->method('save')
            ->with($customer, $hash)
            ->willThrowException($exception);

        $this->accountManagement->createAccountWithPasswordHash($customer, $hash);
    }

    /**
     * @expectedException \Magento\Framework\Exception\LocalizedException
     */
    public function testCreateAccountWithPasswordHashWithLocalizedException()
    {
        $websiteId = 1;
        $storeId = null;
        $defaultStoreId = 1;
        $customerId = 1;
        $customerEmail = 'email@email.com';
        $hash = '4nj54lkj5jfi03j49f8bgujfgsd';

        $address = $this->getMockBuilder('Magento\Customer\Api\Data\AddressInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $store = $this->getMockBuilder('Magento\Store\Model\Store')->disableOriginalConstructor()->getMock();
        $store->expects($this->once())
            ->method('getId')
            ->willReturn($defaultStoreId);
        $website = $this->getMockBuilder('Magento\Store\Model\Website')->disableOriginalConstructor()->getMock();
        $website->expects($this->once())
            ->method('getStoreIds')
            ->willReturn([1, 2, 3]);
        $website->expects($this->once())
            ->method('getDefaultStore')
            ->willReturn($store);
        $customer = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')->getMock();
        $customer->expects($this->once())
            ->method('getId')
            ->willReturn($customerId);
        $customer->expects($this->once())
            ->method('getEmail')
            ->willReturn($customerEmail);
        $customer->expects($this->atLeastOnce())
            ->method('getWebsiteId')
            ->willReturn($websiteId);
        $customer->expects($this->atLeastOnce())
            ->method('getStoreId')
            ->willReturn($storeId);
        $customer->expects($this->once())
            ->method('setStoreId')
            ->with($defaultStoreId);
        $customer
            ->expects($this->once())
            ->method('getAddresses')
            ->willReturn([$address]);
        $customer
            ->expects($this->once())
            ->method('setAddresses')
            ->with(null);
        $this->customerRepository
            ->expects($this->once())
            ->method('get')
            ->with($customerEmail)
            ->willReturn($customer);
        $this->share
            ->expects($this->once())
            ->method('isWebsiteScope')
            ->willReturn(true);
        $this->storeManager
            ->expects($this->atLeastOnce())
            ->method('getWebsite')
            ->with($websiteId)
            ->willReturn($website);
        $exception = new \Magento\Framework\Exception\LocalizedException(new \Magento\Framework\Phrase('Exception message'));
        $this->customerRepository
            ->expects($this->once())
            ->method('save')
            ->with($customer, $hash)
            ->willThrowException($exception);

        $this->accountManagement->createAccountWithPasswordHash($customer, $hash);
    }

    /**
     * @expectedException \Magento\Framework\Exception\LocalizedException
     */
    public function testCreateAccountWithPasswordHashWithAddressException()
    {
        $websiteId = 1;
        $storeId = null;
        $defaultStoreId = 1;
        $customerId = 1;
        $customerEmail = 'email@email.com';
        $hash = '4nj54lkj5jfi03j49f8bgujfgsd';

        $address = $this->getMockBuilder('Magento\Customer\Api\Data\AddressInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $address->expects($this->once())
            ->method('setCustomerId')
            ->with($customerId);
        $store = $this->getMockBuilder('Magento\Store\Model\Store')->disableOriginalConstructor()->getMock();
        $store->expects($this->once())
            ->method('getId')
            ->willReturn($defaultStoreId);
        $website = $this->getMockBuilder('Magento\Store\Model\Website')->disableOriginalConstructor()->getMock();
        $website->expects($this->once())
            ->method('getStoreIds')
            ->willReturn([1, 2, 3]);
        $website->expects($this->once())
            ->method('getDefaultStore')
            ->willReturn($store);
        $customer = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')->getMock();
        $customer->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($customerId);
        $customer->expects($this->once())
            ->method('getEmail')
            ->willReturn($customerEmail);
        $customer->expects($this->atLeastOnce())
            ->method('getWebsiteId')
            ->willReturn($websiteId);
        $customer->expects($this->atLeastOnce())
            ->method('getStoreId')
            ->willReturn($storeId);
        $customer->expects($this->once())
            ->method('setStoreId')
            ->with($defaultStoreId);
        $customer
            ->expects($this->once())
            ->method('getAddresses')
            ->willReturn([$address]);
        $customer
            ->expects($this->once())
            ->method('setAddresses')
            ->with(null);
        $this->customerRepository
            ->expects($this->once())
            ->method('get')
            ->with($customerEmail)
            ->willReturn($customer);
        $this->share
            ->expects($this->once())
            ->method('isWebsiteScope')
            ->willReturn(true);
        $this->storeManager
            ->expects($this->atLeastOnce())
            ->method('getWebsite')
            ->with($websiteId)
            ->willReturn($website);
        $this->customerRepository
            ->expects($this->once())
            ->method('save')
            ->with($customer, $hash)
            ->willReturn($customer);

        $exception = new \Magento\Framework\Exception\InputException(new \Magento\Framework\Phrase('Exception message'));
        $this->addressRepository
            ->expects($this->atLeastOnce())
            ->method('save')
            ->with($address)
            ->willThrowException($exception);
        $this->customerRepository
            ->expects($this->once())
            ->method('delete')
            ->with($customer);

        $this->accountManagement->createAccountWithPasswordHash($customer, $hash);
    }

    /**
     * @return void
     */
    public function testCreateAccountWithPasswordHashWithoutException()
    {
        $websiteId = 1;
        $storeId = null;
        $defaultStoreId = 1;
        $customerId = 1;
        $customerEmail = 'email@email.com';
        $hash = '4nj54lkj5jfi03j49f8bgujfgsd';
        $newLinkToken = '2jh43j5h2345jh23lh452h345hfuzasd96ofu';
        $customerName = 'Customer Name';
        $templateIdentifier = 'Template Identifier';
        $sender = 'Sender';

        $address = $this->getMockBuilder('Magento\Customer\Api\Data\AddressInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $address->expects($this->once())
            ->method('setCustomerId')
            ->with($customerId);
        $store = $this->getMockBuilder('Magento\Store\Model\Store')->disableOriginalConstructor()->getMock();
        $store->expects($this->once())
            ->method('getId')
            ->willReturn($defaultStoreId);
        $website = $this->getMockBuilder('Magento\Store\Model\Website')->disableOriginalConstructor()->getMock();
        $website->expects($this->atLeastOnce())
            ->method('getStoreIds')
            ->willReturn([1, 2, 3]);
        $website->expects($this->once())
            ->method('getDefaultStore')
            ->willReturn($store);
        $customer = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')->getMock();
        $customer->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($customerId);
        $customer->expects($this->atLeastOnce())
            ->method('getEmail')
            ->willReturn($customerEmail);
        $customer->expects($this->atLeastOnce())
            ->method('getWebsiteId')
            ->willReturn($websiteId);
        $customer->expects($this->atLeastOnce())
            ->method('getStoreId')
            ->willReturn($storeId);
        $customer->expects($this->once())
            ->method('setStoreId')
            ->with($defaultStoreId);
        $customer->expects($this->once())
            ->method('getAddresses')
            ->willReturn([$address]);
        $customer->expects($this->once())
            ->method('setAddresses')
            ->with(null);
        $this->customerRepository
            ->expects($this->once())
            ->method('get')
            ->with($customerEmail)
            ->willReturn($customer);
        $this->share->expects($this->once())
            ->method('isWebsiteScope')
            ->willReturn(true);
        $this->storeManager->expects($this->atLeastOnce())
            ->method('getWebsite')
            ->with($websiteId)
            ->willReturn($website);
        $this->customerRepository->expects($this->atLeastOnce())
            ->method('save')
            ->willReturn($customer);
        $this->addressRepository->expects($this->atLeastOnce())
            ->method('save')
            ->with($address);
        $this->customerRepository->expects($this->once())
            ->method('getById')
            ->with($customerId)
            ->willReturn($customer);
        $this->random->expects($this->once())
            ->method('getUniqueHash')
            ->willReturn($newLinkToken);
        $customerSecure = $this->getMockBuilder('Magento\Customer\Model\Data\CustomerSecure')
            ->disableOriginalConstructor()
            ->getMock();
        $customerSecure->expects($this->any())
            ->method('setRpToken')
            ->with($newLinkToken);
        $customerSecure->expects($this->any())
            ->method('setRpTokenCreatedAt');
        $this->customerRegistry->expects($this->atLeastOnce())
            ->method('retrieveSecureData')
            ->willReturn($customerSecure);
        $this->dataObjectProcessor->expects($this->once())
            ->method('buildOutputDataArray')
            ->willReturn([]);
        $this->scopeConfig->expects($this->at(1))
            ->method('getValue')
            ->willReturn($templateIdentifier);
        $this->scopeConfig->expects($this->at(2))
            ->method('getValue')
            ->willReturn($sender);
        $transport = $this->getMockBuilder('Magento\Framework\Mail\TransportInterface')->getMock();
        $this->transportBuilder->expects($this->once())
            ->method('setTemplateIdentifier')
            ->with($templateIdentifier)
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('setTemplateOptions')
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('setTemplateVars')
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('setFrom')
            ->with($sender)
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('addTo')
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('getTransport')
            ->willReturn($transport);
        $transport->expects($this->once())
            ->method('sendMessage');

        $this->accountManagement->createAccountWithPasswordHash($customer, $hash);
    }

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testSendPasswordReminderEmail()
    {
        $customerId = 1;
        $customerStoreId = 2;
        $customerEmail = 'email@email.com';
        $passwordToken = 'token';
        $isFrontendSecure = true;
        $resetUrl = 'reset url';
        $customerData = ['key' => 'value'];
        $customerName = 'Customer Name';
        $templateIdentifier = 'Template Identifier';
        $sender = 'Sender';

        $customer = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')
            ->getMock();
        $customer->expects($this->any())
            ->method('getStoreId')
            ->willReturn($customerStoreId);
        $customer->expects($this->any())
            ->method('getId')
            ->willReturn($customerId);
        $customer->expects($this->any())
            ->method('getEmail')
            ->willReturn($customerEmail);

        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->with($customerStoreId)
            ->willReturn($this->store);

        $this->store->expects($this->any())
            ->method('isFrontUrlSecure')
            ->willReturn($isFrontendSecure);

        $this->url->expects($this->once())
            ->method('getUrl')
            ->with(
                'customer/account/createPassword',
                [
                    '_query' => ['id' => $customerId, 'token' => $passwordToken],
                    '_store' => $customerStoreId,
                    '_secure' => $isFrontendSecure,
                ]
            )->willReturn($resetUrl);

        $this->customerRegistry->expects($this->once())
            ->method('retrieveSecureData')
            ->with($customerId)
            ->willReturn($this->customerSecure);

        $this->dataObjectProcessor->expects($this->once())
            ->method('buildOutputDataArray')
            ->with($customer, '\Magento\Customer\Api\Data\CustomerInterface')
            ->willReturn($customerData);

        $this->customerViewHelper->expects($this->any())
            ->method('getCustomerName')
            ->with($customer)
            ->willReturn($customerName);

        $this->customerSecure->expects($this->once())
            ->method('addData')
            ->with($customerData)
            ->willReturnSelf();
        $this->customerSecure->expects($this->once())
            ->method('setData')
            ->with('name', $customerName)
            ->willReturnSelf();
        $this->customerSecure->expects($this->any())
            ->method('setResetPasswordUrl')
            ->with($resetUrl);

        $this->scopeConfig->expects($this->at(0))
            ->method('getValue')
            ->with(AccountManagement::XML_PATH_REMIND_EMAIL_TEMPLATE, ScopeInterface::SCOPE_STORE, $customerStoreId)
            ->willReturn($templateIdentifier);
        $this->scopeConfig->expects($this->at(1))
            ->method('getValue')
            ->with(AccountManagement::XML_PATH_FORGOT_EMAIL_IDENTITY, ScopeInterface::SCOPE_STORE, $customerStoreId)
            ->willReturn($sender);

        $transport = $this->getMockBuilder('Magento\Framework\Mail\TransportInterface')
            ->getMock();

        $this->transportBuilder->expects($this->once())
            ->method('setTemplateIdentifier')
            ->with($templateIdentifier)
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('setTemplateOptions')
            ->with(['area' => Area::AREA_FRONTEND, 'store' => $customerStoreId])
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('setTemplateVars')
            ->with(['customer' => $this->customerSecure, 'store' => $this->store])
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('setFrom')
            ->with($sender)
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('addTo')
            ->with($customerEmail, $customerName)
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('getTransport')
            ->willReturn($transport);

        $transport->expects($this->once())
            ->method('sendMessage');

        $this->assertEquals(
            $this->accountManagement,
            $this->accountManagement->sendPasswordReminderEmail($customer, $passwordToken)
        );
    }

    /**
     * @param string $email
     * @param string $templateIdentifier
     * @param string $sender
     * @param int $storeId
     * @param int $customerId
     * @param string $hash
     */
    protected function prepareInitiatePasswordReset($email, $templateIdentifier, $sender, $storeId, $customerId, $hash)
    {
        $websiteId = 1;

        $dateTime = date(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);

        $customerData = ['key' => 'value'];
        $customerName = 'Customer Name';

        $this->store->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn($websiteId);
        $this->store->expects($this->any())
            ->method('getId')
            ->willReturn($storeId);

        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($this->store);

        $customer = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')
            ->getMock();
        $customer->expects($this->any())
            ->method('getEmail')
            ->willReturn($email);
        $customer->expects($this->any())
            ->method('getId')
            ->willReturn($customerId);
        $customer->expects($this->any())
            ->method('getStoreId')
            ->willReturn($storeId);

        $this->customerRepository->expects($this->once())
            ->method('get')
            ->with($email, $websiteId)
            ->willReturn($customer);
        $this->customerRepository->expects($this->once())
            ->method('save')
            ->with($customer)
            ->willReturnSelf();

        $this->random->expects($this->once())
            ->method('getUniqueHash')
            ->willReturn($hash);

        $this->customerViewHelper->expects($this->any())
            ->method('getCustomerName')
            ->with($customer)
            ->willReturn($customerName);

        $this->customerSecure->expects($this->any())
            ->method('setRpToken')
            ->with($hash)
            ->willReturnSelf();
        $this->customerSecure->expects($this->any())
            ->method('setRpTokenCreatedAt')
            ->with($dateTime)
            ->willReturnSelf();
        $this->customerSecure->expects($this->any())
            ->method('addData')
            ->with($customerData)
            ->willReturnSelf();
        $this->customerSecure->expects($this->any())
            ->method('setData')
            ->with('name', $customerName)
            ->willReturnSelf();

        $this->customerRegistry->expects($this->any())
            ->method('retrieveSecureData')
            ->with($customerId)
            ->willReturn($this->customerSecure);

        $this->dataObjectProcessor->expects($this->any())
            ->method('buildOutputDataArray')
            ->with($customer, '\Magento\Customer\Api\Data\CustomerInterface')
            ->willReturn($customerData);

        $this->prepareEmailSend($email, $templateIdentifier, $sender, $storeId, $customerName);
    }

    /**
     * @param $email
     * @param $templateIdentifier
     * @param $sender
     * @param $storeId
     * @param $customerName
     */
    protected function prepareEmailSend($email, $templateIdentifier, $sender, $storeId, $customerName)
    {
        $transport = $this->getMockBuilder('Magento\Framework\Mail\TransportInterface')
            ->getMock();

        $this->transportBuilder->expects($this->any())
            ->method('setTemplateIdentifier')
            ->with($templateIdentifier)
            ->willReturnSelf();
        $this->transportBuilder->expects($this->any())
            ->method('setTemplateOptions')
            ->with(['area' => Area::AREA_FRONTEND, 'store' => $storeId])
            ->willReturnSelf();
        $this->transportBuilder->expects($this->any())
            ->method('setTemplateVars')
            ->with(['customer' => $this->customerSecure, 'store' => $this->store])
            ->willReturnSelf();
        $this->transportBuilder->expects($this->any())
            ->method('setFrom')
            ->with($sender)
            ->willReturnSelf();
        $this->transportBuilder->expects($this->any())
            ->method('addTo')
            ->with($email, $customerName)
            ->willReturnSelf();
        $this->transportBuilder->expects($this->any())
            ->method('getTransport')
            ->willReturn($transport);

        $transport->expects($this->any())
            ->method('sendMessage');
    }

    public function testInitiatePasswordResetEmailReminder()
    {
        $customerId = 1;

        $email = 'test@example.com';
        $template = AccountManagement::EMAIL_REMINDER;
        $templateIdentifier = 'Template Identifier';
        $sender = 'Sender';

        $storeId = 1;
        $isFrontendSecure = true;

        $resetUrl = 'reset url';

        mt_srand(mt_rand() + (100000000 * microtime()) % PHP_INT_MAX);
        $hash = md5(uniqid(microtime() . mt_rand(0, mt_getrandmax()), true));

        $this->store->expects($this->once())
            ->method('isFrontUrlSecure')
            ->willReturn($isFrontendSecure);

        $this->url->expects($this->once())
            ->method('setScope')
            ->with($storeId)
            ->willReturnSelf();
        $this->url->expects($this->once())
            ->method('getUrl')
            ->with(
                'customer/account/createPassword',
                [
                    '_query' => ['id' => $customerId, 'token' => $hash],
                    '_store' => $storeId,
                    '_secure' => $isFrontendSecure,
                ]
            )
            ->willReturn($resetUrl);

        $this->scopeConfig->expects($this->at(0))
            ->method('getValue')
            ->with(AccountManagement::XML_PATH_REMIND_EMAIL_TEMPLATE, ScopeInterface::SCOPE_STORE, $storeId)
            ->willReturn($templateIdentifier);
        $this->scopeConfig->expects($this->at(1))
            ->method('getValue')
            ->with(AccountManagement::XML_PATH_FORGOT_EMAIL_IDENTITY, ScopeInterface::SCOPE_STORE, $storeId)
            ->willReturn($sender);

        $this->customerSecure->expects($this->any())
            ->method('setResetPasswordUrl')
            ->with($resetUrl)
            ->willReturnSelf();

        $this->prepareInitiatePasswordReset($email, $templateIdentifier, $sender, $storeId, $customerId, $hash);

        $this->assertTrue($this->accountManagement->initiatePasswordReset($email, $template));
    }

    public function testInitiatePasswordResetEmailReset()
    {
        $storeId = 1;
        $customerId = 1;

        $email = 'test@example.com';
        $template = AccountManagement::EMAIL_RESET;
        $templateIdentifier = 'Template Identifier';
        $sender = 'Sender';

        mt_srand(mt_rand() + (100000000 * microtime()) % PHP_INT_MAX);
        $hash = md5(uniqid(microtime() . mt_rand(0, mt_getrandmax()), true));

        $this->scopeConfig->expects($this->at(0))
            ->method('getValue')
            ->with(AccountManagement::XML_PATH_FORGOT_EMAIL_TEMPLATE, ScopeInterface::SCOPE_STORE, $storeId)
            ->willReturn($templateIdentifier);
        $this->scopeConfig->expects($this->at(1))
            ->method('getValue')
            ->with(AccountManagement::XML_PATH_FORGOT_EMAIL_IDENTITY, ScopeInterface::SCOPE_STORE, $storeId)
            ->willReturn($sender);

        $this->prepareInitiatePasswordReset($email, $templateIdentifier, $sender, $storeId, $customerId, $hash);

        $this->assertTrue($this->accountManagement->initiatePasswordReset($email, $template));
    }

    /**
     * @expectedException \Magento\Framework\Exception\InputException
     * @expectedExceptionMessage Invalid value of "" provided for the email type field
     */
    public function testInitiatePasswordResetNoTemplate()
    {
        $storeId = 1;
        $customerId = 1;

        $email = 'test@example.com';
        $template = null;
        $templateIdentifier = 'Template Identifier';
        $sender = 'Sender';

        mt_srand(mt_rand() + (100000000 * microtime()) % PHP_INT_MAX);
        $hash = md5(uniqid(microtime() . mt_rand(0, mt_getrandmax()), true));

        $this->prepareInitiatePasswordReset($email, $templateIdentifier, $sender, $storeId, $customerId, $hash);

        $this->accountManagement->initiatePasswordReset($email, $template);
    }
}
