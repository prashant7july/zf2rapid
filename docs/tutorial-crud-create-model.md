# ZF2rapid tutorial

In this tutorial you will learn how to create an application step by step with
ZF2rapid.

 * [Create new project](tutorial-create-project.md)
 * [Create new module](tutorial-create-module.md)
 * [Create controllers and actions](tutorial-create-controllers-actions.md)
 * [Create routing and generate maps](tutorial-create-routing-maps.md)
 * [Create controller plugin and view helper](tutorial-create-controller-plugin-view-helper.md)
 * [Create model classes](tutorial-crud-create-model.md)

## PLEASE READ CAREFULLY

The CRUD commands of ZF2rapid do not have the goal to create a full-featured
object-relational-mapper (ORM) for you. If you need such a tool please refer to 
[Doctrine](http://www.doctrine-project.org/), [Propel](http://propelorm.org/), 
[ReadBeanPHP](http://www.redbeanphp.com/) etc.

The classes generated by the CRUD commands try to consider the best practices of 
implementing a simple model layer with the Zend Framework 2. The entities represent 
your data as objects, the table gateways allow the access to database tables and 
the hydrators help to convert the data passed from the database to your 
entities. The generated repositories are meant to be used within your 
controllers or other classes which access to the data.

You can extend the generated classes and add more logic to it. But please 
note, that your amendments will be lost when you try to rebuild the classes 
after you made changes to your database structure. 

The generated classes should only be used for rapid prototyping or as a base for your
own implementations! 

## Create model classes

To create model classes for an existing database table, you need to setup a 
database first. Please create a MySQL database `zf2rapid-tutorial` first and 
grant the needed privileges to a user `zf2rapid` with the password `zf2rapid`.
To proceed this tutorial you can easily use the 
[MySQL database dump](tutorial-crud-database-structure.md)
to create a database structure. 

Afterwards you need to setup the database connection in your current project. 
This should be done in the file `/config/autoload/development.php`, for example. 
Please enter your database configuration.

    return array(
        'db' => array(
            'driver'  => 'pdo',
            'dsn'     => 'mysql:dbname=zf2rapid-tutorial;host=localhost;charset=utf8',
            'user'    => 'zf2rapid',
            'pass'    => 'zf2rapid',
        ),
        [...]
    );

To use the CRUD commands you need to setup the database connection in the 
project you want to create the classes and views in. This should be done in the 
file `/config/autoload/development.php`, for example. Please enter your own 
database configuration.

    return array(
        'db' => array(
            'driver'  => 'pdo',
            'dsn'     => 'mysql:dbname=DATEBASE;host=localhost;charset=utf8',
            'user'    => 'USER',
            'pass'    => 'PASS',
        ),
        [...]
    );

Now you can check if your database configuration is correct and if you can 
access your database.
 
    $ zf2rapid crud-check-db

If you get the message `The connection to the database was successful.` you 
show a list of all tables which are accessible for this database 
configuration. 

    $ zf2rapid crud-show-tables

Ww want to create a new `Customer` module first. All classes and files from the 
CRUD commands should be placed within this module. 
  
    $ zf2rapid create-module Customer

Now you are ready to create all model classes for the tables within the new 
`Customer` module . We will start with the `customer` table.
 
    $ zf2rapid crud-create-model Customer customer

Oops. You should get the error message `Due to a foreign key constraint you 
need to process table country from database zf2rapid-example as well.`. The 
table `customer` is connected to the table `country` with a foreign key 
constraint. To fully create the model classes for the `customer` table you also 
need to specify the `country` table as well.

    $ zf2rapid crud-create-model Customer customer,country

The following tasks are executed when creating new model classes:

 * Load tables from database
 * Create directory structure for model classes
 * Create entity class(es)
 * (optional) Create hydrator strategy class(es)
 * Create hydrator class(es)
 * Create hydrator factory(ies)
 * Create table gateway class(es)
 * Create table gateway factory(ies)
 * Create repository class(es)
 * Create repository factory(ies)
 * Writing model configuration for module

## Structure of new module

The generated structure of the `Customer` module should look like this:

    --- module
      +--- Application
      +--- Customer
         +--- config
         |  +--- module.config.php
         +--- src
         |  +--- Customer
         |       +--- Model                                    <---- new directory
         |           +--- Entity                               <---- new directory
         |           |  +--- CountryEntity.php                 <---- new file
         |           |  +--- CustomerEntity.php                <---- new file
         |           +--- Hydrator                             <---- new directory
         |           |  +--- Strategy                          <---- new directory
         |           |     +--- CountryStrategy.php            <---- new file
         |           |  +--- CountryHydrator.php               <---- new file
         |           |  +--- CountryHydratorFactory.php        <---- new file
         |           |  +--- CustomerHydrator.php              <---- new file
         |           |  +--- CustomerHydratorFactory.php       <---- new file
         |           +--- Repository                           <---- new directory
         |           |  +--- CountryRepository.php             <---- new file
         |           |  +--- CountryRepositoryFactory.php      <---- new file
         |           |  +--- CustomerRepository.php            <---- new file
         |           |  +--- CustomerRepositoryFactory.php     <---- new file
         |           +--- TableGateway                         <---- new directory
         |              +--- CountryTableGateway.php           <---- new file
         |              +--- CountryTableGatewayFactory.php    <---- new file
         |              +--- CustomerTableGateway.php          <---- new file
         |              +--- CustomerTableGatewayFactory.php   <---- new file
         +--- view
         |  +--- customer
         +--- autoload_classmap.php
         +--- Module.php
         +--- template_map.php
      +--- Shop
         
To the `/module/Customer/config/module.config.php` file the configuration for the 
model classes should be added. 

    <?php
    /**
     * ZF2rapid Tutorial
     *
     * @copyright (c) 2015 John Doe
     * @license All rights reserved
     */
    
    return array(
        [...]
        'hydrators' => array(
            'factories' => array(
                'Customer\\Db\\Customer' => 'Customer\\Model\\Hydrator\\CustomerHydratorFactory',
                'Customer\\Db\\Country' => 'Customer\\Model\\Hydrator\\CountryHydratorFactory',
            ),
        ),
        'service_manager' => array(
            'factories' => array(
                'Customer\\Model\\TableGateway\\Customer' => 'Customer\\Model\\TableGateway\\CustomerTableGatewayFactory',
                'Customer\\Model\\Repository\\Customer' => 'Customer\\Model\\Repository\\CustomerRepositoryFactory',
                'Customer\\Model\\TableGateway\\Country' => 'Customer\\Model\\TableGateway\\CountryTableGatewayFactory',
                'Customer\\Model\\Repository\\Country' => 'Customer\\Model\\Repository\\CountryRepositoryFactory',
            ),
        ),
    );

## Generated entity classes

The `/module/Customer/src/Customer/Model/Entity/CountryEntity.php` file contains 
the `CountryEntity` class, which consists of the properties `code` and `name` 
from the database table `country` and the corresponding setter and getter 
methods.

    <?php
    /**
     * ZF2 Application built by ZF2rapid
     *
     * @copyright (c) 2015 John Doe
     * @license http://opensource.org/licenses/MIT The MIT License (MIT)
     */
    
    namespace Customer\Model\Entity;
    
    use ZF2rapidDomain\Entity\AbstractEntity;
    
    /**
     * CountryEntity
     *
     * Provides the CountryEntity entity for the Customer Module
     *
     * @package Customer\Model\Entity
     */
    class CountryEntity extends AbstractEntity
    {
    
        /**
         * code property
         *
         * @var string
         */
        protected $code = null;
    
        /**
         * name property
         *
         * @var string
         */
        protected $name = null;
    
        /**
         * Get the primary identifier
         *
         * @return string
         */
        public function getIdentifier()
        {
            return $this->getCode();
        }
    
        /**
         * Set code
         *
         * @param string $code
         */
        protected function setCode($code)
        {
            $this->code = (string) $code;
        }
    
        /**
         * Get code
         *
         * @return string
         */
        public function getCode()
        {
            return $this->code;
        }
    
        /**
         * Set name
         *
         * @param string $name
         */
        protected function setName($name)
        {
            $this->name = (string) $name;
        }
    
        /**
         * Get name
         *
         * @return string
         */
        public function getName()
        {
            return $this->name;
        }
    }

The `/module/Customer/src/Customer/Model/Entity/CustomerEntity.php` file 
contains the `CustomerEntity` class, which consists all the properties from the 
database table `customer` and the corresponding setter and getter methods.

    <?php
    /**
     * ZF2 Application built by ZF2rapid
     *
     * @copyright (c) 2015 John Doe
     * @license http://opensource.org/licenses/MIT The MIT License (MIT)
     */
    
    namespace Customer\Model\Entity;
    
    use ZF2rapidDomain\Entity\AbstractEntity;
    
    /**
     * CustomerEntity
     *
     * Provides the CustomerEntity entity for the Customer Module
     *
     * @package Customer\Model\Entity
     */
    class CustomerEntity extends AbstractEntity
    {
    
        /**
         * id property
         *
         * @var integer
         */
        protected $id = null;
    
        /**
         * created property
         *
         * @var string
         */
        protected $created = null;
    
        /**
         * changed property
         *
         * @var string
         */
        protected $changed = null;
    
        /**
         * status property
         *
         * @var string
         */
        protected $status = null;
    
        /**
         * firstName property
         *
         * @var string
         */
        protected $firstName = null;
    
        /**
         * lastName property
         *
         * @var string
         */
        protected $lastName = null;
    
        /**
         * street property
         *
         * @var string
         */
        protected $street = null;
    
        /**
         * zip property
         *
         * @var string
         */
        protected $zip = null;
    
        /**
         * city property
         *
         * @var string
         */
        protected $city = null;
    
        /**
         * country property
         *
         * @var CountryEntity
         */
        protected $country = null;
    
        /**
         * Get the primary identifier
         *
         * @return integer
         */
        public function getIdentifier()
        {
            return $this->getId();
        }
    
        /**
         * Set id
         *
         * @param integer $id
         */
        protected function setId($id)
        {
            $this->id = (integer) $id;
        }
    
        /**
         * Get id
         *
         * @return integer
         */
        public function getId()
        {
            return $this->id;
        }
    
        /**
         * Set created
         *
         * @param string $created
         */
        protected function setCreated($created)
        {
            $this->created = (string) $created;
        }
    
        /**
         * Get created
         *
         * @return string
         */
        public function getCreated()
        {
            return $this->created;
        }
    
        /**
         * Set changed
         *
         * @param string $changed
         */
        protected function setChanged($changed)
        {
            $this->changed = (string) $changed;
        }
    
        /**
         * Get changed
         *
         * @return string
         */
        public function getChanged()
        {
            return $this->changed;
        }
    
        /**
         * Set status
         *
         * @param string $status
         */
        protected function setStatus($status)
        {
            $this->status = (string) $status;
        }
    
        /**
         * Get status
         *
         * @return string
         */
        public function getStatus()
        {
            return $this->status;
        }
    
        /**
         * Set firstName
         *
         * @param string $firstName
         */
        protected function setFirstName($firstName)
        {
            $this->firstName = (string) $firstName;
        }
    
        /**
         * Get firstName
         *
         * @return string
         */
        public function getFirstName()
        {
            return $this->firstName;
        }
    
        /**
         * Set lastName
         *
         * @param string $lastName
         */
        protected function setLastName($lastName)
        {
            $this->lastName = (string) $lastName;
        }
    
        /**
         * Get lastName
         *
         * @return string
         */
        public function getLastName()
        {
            return $this->lastName;
        }
    
        /**
         * Set street
         *
         * @param string $street
         */
        protected function setStreet($street)
        {
            $this->street = (string) $street;
        }
    
        /**
         * Get street
         *
         * @return string
         */
        public function getStreet()
        {
            return $this->street;
        }
    
        /**
         * Set zip
         *
         * @param string $zip
         */
        protected function setZip($zip)
        {
            $this->zip = (string) $zip;
        }
    
        /**
         * Get zip
         *
         * @return string
         */
        public function getZip()
        {
            return $this->zip;
        }
    
        /**
         * Set city
         *
         * @param string $city
         */
        protected function setCity($city)
        {
            $this->city = (string) $city;
        }
    
        /**
         * Get city
         *
         * @return string
         */
        public function getCity()
        {
            return $this->city;
        }
    
        /**
         * Set country
         *
         * @param CountryEntity $country
         */
        protected function setCountry(CountryEntity $country)
        {
            $this->country = $country;
        }
    
        /**
         * Get country
         *
         * @return CountryEntity
         */
        public function getCountry()
        {
            return $this->country;
        }
    }

## Generated hydrator classes, strategies and factories

## Generated table gateway classes and factories

## Generated repository classes and factories






![Screen shot activated module](screen_activated_module.jpg)
  