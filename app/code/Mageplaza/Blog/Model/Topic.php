<?php
/**
 * Mageplaza_Blog extension
 *                     NOTICE OF LICENSE
 *
 *                     This source file is subject to the MIT License
 *                     that is bundled with this package in the file LICENSE.txt.
 *                     It is also available through the world-wide-web at this URL:
 *                     http://opensource.org/licenses/mit-license.php
 *
 *                     @category  Mageplaza
 *                     @package   Mageplaza_Blog
 *                     @copyright Copyright (c) 2016
 *                     @license   http://opensource.org/licenses/mit-license.php MIT License
 */
namespace Mageplaza\Blog\Model;

/**
 * @method Topic setName($name)
 * @method Topic setDescription($description)
 * @method Topic setEnabled($enabled)
 * @method Topic setUrlKey($urlKey)
 * @method Topic setMetaTitle($metaTitle)
 * @method Topic setMetaDescription($metaDescription)
 * @method Topic setMetaKeywords($metaKeywords)
 * @method Topic setMetaRobots($metaRobots)
 * @method mixed getName()
 * @method mixed getDescription()
 * @method mixed getEnabled()
 * @method mixed getUrlKey()
 * @method mixed getMetaTitle()
 * @method mixed getMetaDescription()
 * @method mixed getMetaKeywords()
 * @method mixed getMetaRobots()
 * @method Topic setCreatedAt(\string $createdAt)
 * @method string getCreatedAt()
 * @method Topic setUpdatedAt(\string $updatedAt)
 * @method string getUpdatedAt()
 * @method Topic setPostsData(array $data)
 * @method array getPostsData()
 * @method Topic setIsChangedPostList(\bool $flag)
 * @method bool getIsChangedPostList()
 * @method Topic setAffectedPostIds(array $ids)
 * @method bool getAffectedPostIds()
 */
class Topic extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Cache tag
     *
     * @var string
     */
    const CACHE_TAG = 'mageplaza_blog_topic';

    /**
     * Cache tag
     *
     * @var string
     */
    protected $_cacheTag = 'mageplaza_blog_topic';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'mageplaza_blog_topic';

    /**
     * Post Collection
     *
     * @var \Mageplaza\Blog\Model\ResourceModel\Post\Collection
     */
    protected $postCollection;

    /**
     * Post Collection Factory
     *
     * @var \Mageplaza\Blog\Model\ResourceModel\Post\CollectionFactory
     */
    protected $postCollectionFactory;

    /**
     * constructor
     *
     * @param \Mageplaza\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Mageplaza\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
    
        $this->postCollectionFactory = $postCollectionFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }


    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Mageplaza\Blog\Model\ResourceModel\Topic');
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * get entity default values
     *
     * @return array
     */
    public function getDefaultValues()
    {
        $values = [];
        $values['enabled'] = '1';
        return $values;
    }
    /**
     * @return array|mixed
     */
    public function getPostsPosition()
    {
        if (!$this->getId()) {
            return [];
        }
        $array = $this->getData('posts_position');
        if (is_null($array)) {
            $array = $this->getResource()->getPostsPosition($this);
            $this->setData('posts_position', $array);
        }
        return $array;
    }

    /**
     * @return \Mageplaza\Blog\Model\ResourceModel\Post\Collection
     */
    public function getSelectedPostsCollection()
    {
        if (is_null($this->postCollection)) {
            $collection = $this->postCollectionFactory->create();
            $collection->join(
                'mageplaza_blog_post_topic',
                'main_table.post_id=mageplaza_blog_post_topic.post_id AND mageplaza_blog_post_topic.topic_id='.$this->getId(),
                ['position']
            );
            $this->postCollection = $collection;
        }
        return $this->postCollection;
    }
}